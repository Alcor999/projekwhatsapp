<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MasterDocument;
use App\Models\NotificationLog;
use App\Services\CustomerImportService;
use App\Services\FonnteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return $this->masterCustomer();
    }

    public function masterCustomer(): View
    {
        return view('panel', [
            'page' => 'master-customer',
            'customers' => Customer::query()->latest()->get(),
            'documents' => MasterDocument::query()->latest()->get(),
            'defaultTemplate' => $this->defaultTemplate(),
        ]);
    }

    public function sendMessage(): View
    {
        return view('panel', [
            'page' => 'kirim-pesan',
            'customers' => Customer::query()->latest()->get(),
            'documents' => MasterDocument::query()->latest()->get(),
            'selectedDocument' => null,
            'documentUrl' => null,
            'defaultTemplate' => $this->defaultTemplate(),
        ]);
    }

    public function masterDocument(): View
    {
        return view('panel', [
            'page' => 'master-dokumen',
            'customers' => Customer::query()->latest()->get(),
            'documents' => MasterDocument::query()->latest()->get(),
            'defaultTemplate' => $this->defaultTemplate(),
        ]);
    }

    // ──────────────────────────────────────
    //  CUSTOMER CRUD
    // ──────────────────────────────────────

    public function storeCustomer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:20'],
            'debtor_name' => ['required', 'string', 'max:255'],
            'contract_number' => ['required', 'string', 'max:100', 'unique:customers,contract_number'],
            'vehicle_unit' => ['nullable', 'string', 'max:255'],
        ]);

        Customer::create($validated);

        return back()->with('success', 'Customer berhasil ditambahkan.');
    }

    public function updateCustomer(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:20'],
            'debtor_name' => ['required', 'string', 'max:255'],
            'contract_number' => ['required', 'string', 'max:100', 'unique:customers,contract_number,' . $customer->id],
            'vehicle_unit' => ['nullable', 'string', 'max:255'],
        ]);

        $customer->update($validated);

        return back()->with('success', "Customer '{$validated['debtor_name']}' berhasil diperbarui.");
    }

    public function destroyCustomer(Customer $customer): RedirectResponse
    {
        $name = $customer->debtor_name;
        $customer->delete();

        return back()->with('success', "Customer '{$name}' berhasil dihapus.");
    }

    public function importCustomers(Request $request, CustomerImportService $importService): RedirectResponse
    {
        $validated = $request->validate([
            'customer_file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        try {
            $result = $importService->importFromFile($validated['customer_file']->getRealPath());
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with(
            'success',
            "Import selesai. Data baru: {$result['inserted']}, update: {$result['updated']}, dilewati: {$result['skipped']}."
        );
    }

    // ──────────────────────────────────────
    //  DOCUMENT CRUD
    // ──────────────────────────────────────

    public function uploadDocument(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'document_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $originalName = $validated['document_file']->getClientOriginalName();
        $storedPath = $validated['document_file']->store('master-documents', 'public');

        $existingDocument = MasterDocument::where('title', $validated['title'])
                                          ->orWhere('original_name', $originalName)
                                          ->first();

        if ($existingDocument) {
            if (Storage::disk('public')->exists($existingDocument->file_path)) {
                Storage::disk('public')->delete($existingDocument->file_path);
            }

            $existingDocument->update([
                'title' => $validated['title'],
                'file_path' => $storedPath,
                'original_name' => $originalName,
            ]);

            return back()->with('success', "Dokumen '{$validated['title']}' berhasil diupdate dengan file baru.");
        }

        MasterDocument::create([
            'title' => $validated['title'],
            'file_path' => $storedPath,
            'original_name' => $originalName,
        ]);

        return back()->with('success', 'Dokumen PDF baru berhasil diupload.');
    }

    public function updateDocument(Request $request, MasterDocument $document): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'document_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $document->title = $validated['title'];

        if ($request->hasFile('document_file')) {
            // Delete old file from storage
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $storedPath = $validated['document_file']->store('master-documents', 'public');
            $document->file_path = $storedPath;
            $document->original_name = $validated['document_file']->getClientOriginalName();
        }

        $document->save();

        return back()->with('success', "Dokumen '{$validated['title']}' berhasil diperbarui.");
    }

    public function destroyDocument(MasterDocument $document): RedirectResponse
    {
        $title = $document->title;

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', "Dokumen '{$title}' berhasil dihapus.");
    }

    // ──────────────────────────────────────
    //  SEND NOTIFICATION
    // ──────────────────────────────────────

    public function sendNotifications(Request $request, FonnteService $fonnteService): RedirectResponse
    {
        $validated = $request->validate([
            'customer_ids' => ['required', 'array', 'min:1'],
            'customer_ids.*' => ['integer', 'exists:customers,id'],
            'template' => ['required', 'string', 'min:20'],
            'document_id' => ['nullable', 'integer', 'exists:master_documents,id'],
        ]);

        // Auto-save the template when they blast so they never lose their edits
        Storage::disk('local')->put('template.txt', $validated['template']);

        $customers = Customer::query()->whereIn('id', $validated['customer_ids'])->get();
        $selectedDocument = null;
        $documentUrl = null;

        if (! empty($validated['document_id'])) {
            $selectedDocument = MasterDocument::query()->find($validated['document_id']);
            if ($selectedDocument) {
                $appUrl = rtrim((string) config('app.url'), '/');
                if (! $this->isPublicBaseUrl($appUrl)) {
                    return back()->with(
                        'error',
                        '❌ Gagal: Fonnte (Basic Plan) tidak bisa membaca file dari localhost. Untuk testing lokal, jalankan tunnel misal: `ssh -R 80:localhost:8000 localhost.run` lalu set link public-nya di APP_URL pada file .env Anda.'
                    );
                }

                $documentUrl = rtrim($appUrl, '/').'/'.ltrim(Storage::disk('public')->url($selectedDocument->file_path), '/');
            }
        }

        $successCount = 0;
        $failedCount = 0;

        foreach ($customers as $customer) {
            $message = $this->renderTemplate($validated['template'], $customer);

            // Smart Fallback: If document exists, append the link to the message text
            // because Free Plan doesn't support real document attachments via API
            if ($documentUrl) {
                $message .= "\n\n📄 *Lampiran Dokumen:*\n" . $documentUrl;
            }

            try {
                $response = $fonnteService->sendMessage(
                    $customer->phone_number,
                    $message,
                    $documentUrl,
                    $selectedDocument?->original_name
                );
                $status = $response['ok'] ? 'sent' : 'failed';
                $response['ok'] ? $successCount++ : $failedCount++;
            } catch (\Throwable $exception) {
                $status = 'failed';
                $response = ['error' => $exception->getMessage()];
                $failedCount++;
            }

            NotificationLog::create([
                'customer_id' => $customer->id,
                'master_document_id' => $selectedDocument?->id,
                'phone_number' => $customer->phone_number,
                'message' => $message,
                'document_title' => $selectedDocument?->title,
                'document_url' => $documentUrl,
                'status' => $status,
                'provider_response' => $response,
            ]);
        }

        return back()->with(
            'success',
            "Pengiriman selesai. Berhasil: {$successCount}, gagal: {$failedCount}."
        );
    }

    // ──────────────────────────────────────
    //  PRIVATE HELPERS
    // ──────────────────────────────────────

    private function renderTemplate(string $template, Customer $customer): string
    {
        return strtr($template, [
            '{nama_debitur}' => $customer->debtor_name,
            '{nomor_kontrak}' => $customer->contract_number,
            '{nomor_hp}' => $customer->phone_number,
            '{unit_kendaraan}' => $customer->vehicle_unit ?? '-',
        ]);
    }

    public function saveTemplate(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'template' => ['required', 'string', 'min:20'],
        ]);

        Storage::disk('local')->put('template.txt', $validated['template']);

        return response()->json([
            'success' => true,
            'message' => 'Template pesan berhasil disimpan.',
        ]);
    }

    private function defaultTemplate(): string
    {
        if (Storage::disk('local')->exists('template.txt')) {
            return Storage::disk('local')->get('template.txt');
        }

        return "Selamat pagi/siang/sore Bapak/Ibu {nama_debitur},\n\nSesuai data kami, Bapak/Ibu mempunyai kontrak pembiayaan yang sudah lunas di Mandiri Utama Finance, dan sudah melebihi 90 hari kalender dari tanggal pembayaran angsuran terakhir dengan status BPKB Bapak/Ibu belum diambil yaitu:\nNo Kontrak\t  \t: {nomor_kontrak}\nUnit Kendaraan\t: {unit_kendaraan}\n\nUntuk tetap menjaga kenyamanan Bapak/Ibu {nama_debitur} kami megingatkan kembali bahwa BPKB yang tersimpan di Kantor Cabang kami lebih dari 90 hari kalender sejak tanggal pembayaran angsuran terakhir dikenakan biaya sebesar Rp 50.000,- per bulan per BPKB. \n\nOleh karena itu, kami mohon kepada Bapak/Ibu {nama_debitur} untuk segera melakukan pengambilan BPKB di Kantor kami dengan membawa KTP asli dan bukti pembayaran angsuran terakhir, serta biaya simpan BPKB yang dikenakan dan biaya lainnya.\n\nUntuk lebih jelasnya Bapak/Ibu dapat menghubungi nomor ini kembali. Atau bisa kami bantu jika ingin melakukan pengajuan pembiayaan kembali.";
    }

    private function isPublicBaseUrl(string $appUrl): bool
    {
        if ($appUrl === '') {
            return false;
        }

        $host = parse_url($appUrl, PHP_URL_HOST);
        if (! is_string($host) || $host === '') {
            return false;
        }

        $host = strtolower($host);
        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            return false;
        }

        return true;
    }
}
