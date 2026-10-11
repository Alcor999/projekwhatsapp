<?php

namespace App\Services;

use App\Models\Customer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class CustomerImportService
{
    public function importFromFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $highestRow = $worksheet->getHighestDataRow();
        $highestCol = $worksheet->getHighestDataColumn();

        if ($highestRow < 2) {
            return ['inserted' => 0, 'updated' => 0, 'skipped' => 0];
        }

        // Read header row as strings
        $headerRow = [];
        $colIndex = 1;
        foreach (range('A', $highestCol) as $col) {
            $cellValue = $worksheet->getCell($col . '1')->getValue();
            $headerRow[] = $this->normalizeHeader((string) ($cellValue ?? ''));
            $colIndex++;
        }

        $phoneIndex = $this->findColumnIndex($headerRow, ['nomor hp', 'no hp', 'hp', 'phone', 'phone number']);
        $nameIndex = $this->findColumnIndex($headerRow, ['nama debitur', 'nama', 'debitur', 'customer']);
        $contractIndex = $this->findColumnIndex($headerRow, ['nomor kontrak', 'no kontrak', 'kontrak', 'contract']);
        $vehicleIndex = $this->findColumnIndex($headerRow, ['unit kendaraan', 'kendaraan', 'unit', 'vehicle']);

        if ($phoneIndex === null || $nameIndex === null || $contractIndex === null) {
            throw new \RuntimeException('Format kolom tidak valid. Gunakan kolom: nomor hp, nama debitur, nomor kontrak.');
        }

        $columns = range('A', $highestCol);

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        for ($rowNum = 2; $rowNum <= $highestRow; $rowNum++) {
            // Read each cell as RAW string to preserve leading zeros
            $phoneRaw = $this->getCellAsString($worksheet, $columns[$phoneIndex] . $rowNum);
            $nameRaw = trim($this->getCellAsString($worksheet, $columns[$nameIndex] . $rowNum));
            $contractRaw = trim($this->getCellAsString($worksheet, $columns[$contractIndex] . $rowNum));
            $vehicleRaw = $vehicleIndex !== null ? trim($this->getCellAsString($worksheet, $columns[$vehicleIndex] . $rowNum)) : '';

            $phone = $this->normalizePhone($phoneRaw);
            $contract = $this->normalizeContract($contractRaw);

            if ($phone === '' || $nameRaw === '' || $contract === '') {
                $skipped++;
                continue;
            }

            // Try to find by contract number: exact match
            $customer = Customer::query()
                ->where('contract_number', $contract)
                ->first();

            // Try matching with un-normalized contract (for legacy data)
            if (! $customer && $contractRaw !== $contract) {
                $customer = Customer::query()
                    ->where('contract_number', $contractRaw)
                    ->first();
            }

            // Fuzzy match: strip leading zeros from both sides for comparison
            // This handles: DB has "030323008845" but Excel reads "30323008845" (or vice versa)
            if (! $customer) {
                $contractDigitsOnly = ltrim($contract, '0');
                $allCustomersWithSimilarContract = Customer::query()
                    ->where('contract_number', 'LIKE', '%' . $contractDigitsOnly)
                    ->get();

                foreach ($allCustomersWithSimilarContract as $candidate) {
                    if (ltrim($candidate->contract_number, '0') === $contractDigitsOnly) {
                        $customer = $candidate;
                        break;
                    }
                }
            }

            // Also try to find by phone number as a fallback
            if (! $customer) {
                $customer = Customer::query()
                    ->where('phone_number', $phone)
                    ->first();
            }

            if ($customer) {
                $customer->fill([
                    'phone_number' => $phone,
                    'debtor_name' => $nameRaw,
                    'contract_number' => $contract,
                    'vehicle_unit' => $vehicleRaw,
                ]);

                if ($customer->isDirty()) {
                    $customer->save();
                    $updated++;
                } else {
                    $skipped++;
                }
                continue;
            }

            try {
                Customer::create([
                    'phone_number' => $phone,
                    'debtor_name' => $nameRaw,
                    'contract_number' => $contract,
                    'vehicle_unit' => $vehicleRaw,
                ]);
                $inserted++;
            } catch (\Illuminate\Database\QueryException $e) {
                // Unique constraint violation — try to update instead
                $existingByContract = Customer::query()->where('contract_number', $contract)->first();
                if ($existingByContract) {
                    $existingByContract->update([
                        'phone_number' => $phone,
                        'debtor_name' => $nameRaw,
                        'vehicle_unit' => $vehicleRaw,
                    ]);
                    $updated++;
                } else {
                    $skipped++;
                }
            }
        }

        return compact('inserted', 'updated', 'skipped');
    }

    /**
     * Read a cell value as a raw string, preserving leading zeros.
     * PhpSpreadsheet's toArray() casts numeric cells to int/float, losing leading zeros.
     * This method reads the raw value and formats it properly.
     */
    private function getCellAsString(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet, string $coordinate): string
    {
        $cell = $worksheet->getCell($coordinate);
        $value = $cell->getValue();

        if ($value === null) {
            return '';
        }

        // If the cell is explicitly a string type, use as-is
        if ($cell->getDataType() === DataType::TYPE_STRING || $cell->getDataType() === DataType::TYPE_STRING2) {
            return (string) $value;
        }

        // For numeric values, use getFormattedValue to preserve display format
        $formatted = $cell->getFormattedValue();
        if ($formatted !== '' && $formatted !== null) {
            return (string) $formatted;
        }

        return (string) $value;
    }

    /**
     * Normalize contract number: ensure it's a clean string.
     * Pad with leading zero if the original had one (common Indonesian format starting with 0).
     */
    private function normalizeContract(string $contract): string
    {
        // Strip any whitespace
        $contract = trim($contract);
        
        if ($contract === '') {
            return '';
        }

        // Remove any decimal points from float conversion (e.g., "30323008845.0" → "30323008845")
        if (str_contains($contract, '.')) {
            $contract = rtrim(rtrim($contract, '0'), '.');
        }

        return $contract;
    }

    private function normalizeHeader(string $value): string
    {
        return trim(strtolower(preg_replace('/\s+/', ' ', $value)));
    }

    private function findColumnIndex(array $headers, array $candidates): ?int
    {
        foreach ($candidates as $candidate) {
            $index = array_search($candidate, $headers, true);
            if ($index !== false) {
                return $index;
            }
        }

        return null;
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (! str_starts_with($digits, '62')) {
            return '62'.$digits;
        }

        return $digits;
    }
}
