<?php

namespace App\Listeners;

use App\Events\InvoicePaid;
use App\Jobs\SendWhatsAppJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendPaymentNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InvoicePaid $event): void
    {
        $invoice = $event->invoice;
        $customer = $invoice->customer;

        if (!$customer || !$customer->phone) {
            Log::warning("Skipping Payment Notification: Customer data or phone invalid for Invoice #{$invoice->invoice_number}");
            return;
        }

        $amount = number_format($invoice->amount, 0, ',', '.');
        $paymentMethod = ucfirst(str_replace('_', ' ', $invoice->payment_method));

        $message = "Halo, *{$customer->name}*!\n\n"
            . "Terima kasih, pembayaran untuk tagihan *{$invoice->invoice_number}* telah diterima.\n\n"
            . "💰 Nominal: *Rp {$amount}*\n"
            . "💳 Metode: *{$paymentMethod}*\n"
            . "✅ Status: *LUNAS*\n\n"
            . "Layanan internet Anda sudah aktif kembali. Terima kasih telah menggunakan ISP Jabbar.\n\n"
            . "*Admin ISP Jabbar*";

        SendWhatsAppJob::dispatch($customer->phone, $message);
    }
}
