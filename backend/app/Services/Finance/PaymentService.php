<?php

namespace App\Services\Finance;

use App\Models\Invoice;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use App\Services\Finance\InvoiceService;

class PaymentService
{
    public function __construct(
        protected InvoiceService $invoiceService
    ) {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create Midtrans Snap Token for an invoice.
     */
    public function createSnapToken(Invoice $invoice): ?string
    {
        $customer = $invoice->customer;
        
        $params = [
            'transaction_details' => [
                'order_id' => $invoice->invoice_number,
                'gross_amount' => (int) $invoice->total_after_tax,
            ],
            'customer_details' => [
                'first_name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
            ],
            'item_details' => [
                [
                    'id' => 'INET-' . ($customer->package->id ?? '0'),
                    'price' => (int) $invoice->total_after_tax,
                    'quantity' => 1,
                    'name' => 'Layanan Internet Jabbar23 - ' . ($customer->package->name ?? 'Internet'),
                ]
            ],
            'callbacks' => [
                'finish' => config('app.frontend_url') . '/dashboard/customer/invoices',
            ]
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error("Midtrans Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Set Snap Token to Invoice.
     */
    public function updateInvoiceSnapToken(Invoice $invoice): string
    {
        if (!$invoice->snap_token) {
            $token = $this->createSnapToken($invoice);
            $invoice->update(['snap_token' => $token]);
        }
        
        return $invoice->snap_token;
    }

    /**
     * Handle Midtrans Notification (Webhook).
     */
    public function handleNotification(array $payload): bool
    {
        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $signatureKey = $payload['signature_key'];

        // Verify Signature
        $serverKey = config('services.midtrans.server_key');
        $validSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $validSignature) {
            Log::warning("Invalid Midtrans Signature: " . $orderId);
            return false;
        }

        $transactionStatus = $payload['transaction_status'];
        $type = $payload['payment_type'];

        $invoice = Invoice::where('invoice_number', $orderId)->first();

        if (!$invoice) {
            Log::error("Invoice not found for Midtrans order_id: " . $orderId);
            return false;
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $this->invoiceService->markAsPaid($invoice, "Midtrans ({$type})");
        } 
        else if ($transactionStatus == 'pending') {
            $invoice->update(['status' => 'unpaid']);
        }
        else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $invoice->update(['status' => 'unpaid']);
        }

        return true;
    }
}
