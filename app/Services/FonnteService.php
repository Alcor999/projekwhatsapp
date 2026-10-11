<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public function sendMessage(string $phoneNumber, string $message, ?string $fileUrl = null, ?string $fileName = null): array
    {
        $token = (string) config('services.fonnte.token');

        if ($token === '') {
            throw new \RuntimeException('FONNTE_TOKEN belum diatur di .env');
        }

        $payload = [
            'target' => $phoneNumber,
            'message' => $message,
        ];

        if ($fileUrl) {
            $payload['url'] = $fileUrl;
            if ($fileName) {
                $payload['filename'] = $fileName;
            }
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->asMultipart()->post('https://api.fonnte.com/send', $payload);

        $json = $response->json();

        $body = is_array($json) ? $json : ['raw' => $response->body()];
        $providerStatus = (bool) ($body['status'] ?? false);

        return [
            'ok' => $response->successful() && $providerStatus,
            'status_code' => $response->status(),
            'body' => $body,
        ];
    }
}
