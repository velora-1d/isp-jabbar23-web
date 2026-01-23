<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $token;

    public function __construct()
    {
        $this->apiUrl = config('fonnte.api_url');
        $this->token = config('fonnte.token');
    }

    /**
     * Send WhatsApp message via Fonnte.
     */
    public function send(string $phone, string $message): bool
    {
        try {
            /** @var Response $response */
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $this->formatPhone($phone),
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp sent successfully', ['phone' => $phone]);
                return true;
            }

            Log::error('WhatsApp send failed', ['response' => $response->body()]);
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp send exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send payment confirmation notification.
     */
    public function sendPaymentConfirmation($customer, $invoice): bool
    {
        $message = "Halo {$customer->name},\n\n"
            . "Pembayaran Anda telah kami terima ✅\n\n"
            . "📄 Invoice: {$invoice->invoice_number}\n"
            . "💰 Nominal: {$invoice->formatted_amount}\n"
            . "📅 Periode: {$invoice->formatted_period}\n"
            . "💳 Metode: {$invoice->payment_method}\n\n"
            . "Terima kasih telah menggunakan layanan JABBAR23 ISP! 🙏";

        return $this->send($customer->phone, $message);
    }

    /**
     * Format phone number to Indonesian format.
     */
    protected function formatPhone(string $phone): string
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert 08xx to 628xx
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
}
