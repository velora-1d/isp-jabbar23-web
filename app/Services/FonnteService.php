<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Send a WhatsApp message using Fonnte API.
     *
     * @param string $target The phone number (e.g., "08123456789")
     * @param string $message The message content
     * @return array The API response
     */
    public function sendMessage(string $target, string $message): array
    {
        $token = config('services.fonnte.token');

        // Normalize phone number (08xx -> 628xx)
        if (str_starts_with($target, '0')) {
            $target = '62' . substr($target, 1);
        }

        if (empty($token)) {
            Log::warning('Fonnte token is not configured. Message not sent.');
            return [
                'status' => false,
                'reason' => 'Token not configured'
            ];
        }

        try {
            // Fonnte API endpoint
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                // 'countryCode' => '62', // Optional, default is 62
            ]);

            $result = $response->json();

            Log::info('Fonnte API Response:', ['target' => $target, 'response' => $result]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Fonnte API Error: ' . $e->getMessage());
            return [
                'status' => false,
                'reason' => $e->getMessage()
            ];
        }
    }
}
