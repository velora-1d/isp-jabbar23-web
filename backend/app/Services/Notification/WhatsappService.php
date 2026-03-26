<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $provider;

    public function __construct()
    {
        $this->provider = \App\Models\Setting::get('whatsapp_provider', 'fonnte');
        $this->baseUrl = \App\Models\Setting::get('whatsapp_base_url', 'https://api.fonnte.com');
        $this->apiKey = \App\Models\Setting::get('whatsapp_api_key', '');
    }

    /**
     * Send a basic text message.
     */
    public function sendMessage(string $to, string $message): bool
    {
        try {
            if ($this->provider === 'fonnte') {
                return $this->sendViaFonnte($to, $message);
            }
            
            if ($this->provider === 'waha') {
                return $this->sendViaWaha($to, $message);
            }

            Log::error("WhatsApp Provider [{$this->provider}] not supported.");
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsApp Service Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Implementation for Fonnte API.
     */
    protected function sendViaFonnte(string $to, string $message): bool
    {
        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
        ])->post($this->baseUrl . '/send', [
            'target' => $to,
            'message' => $message,
            'delay' => '2', // Prevent ban
        ]);

        if ($response->successful()) {
            return true;
        }

        Log::error("Fonnte API Error: " . $response->body());
        return false;
    }

    /**
     * Implementation for WAHA (Self-hosted) API.
     */
    protected function sendViaWaha(string $to, string $message): bool
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
        ])->post($this->baseUrl . '/api/sendText', [
            'chatId' => $to . '@c.us',
            'text' => $message,
            'session' => 'default',
        ]);

        return $response->successful();
    }
}
