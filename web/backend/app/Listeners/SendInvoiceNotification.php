<?php

namespace App\Listeners;

use App\Events\InvoiceGenerated;
use App\Jobs\SendWhatsAppJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendInvoiceNotification implements ShouldQueue
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
    public function handle(InvoiceGenerated $event): void
    {
        $invoice = $event->invoice;
        $customer = $invoice->customer;

        if (!$customer || !$customer->phone) {
            Log::warning("Skipping Invoice Notification: Customer data or phone invalid for Invoice #{$invoice->invoice_number}");
            return;
        }

        $message = "Halo, *{$customer->name}*!\n\n"
            . "Tagihan internet Anda untuk periode ini telah terbit.\n\n"
            . "🧾 No. Tagihan: *{$invoice->invoice_number}*\n"
            . "📅 Periode: *" . ($invoice->period_start ? $invoice->period_start->translatedFormat('F Y') : '-') . "*\n"
            . "💰 Total: *Rp " . number_format($invoice->amount, 0, ',', '.') . "*\n"
            . "⏳ Jatuh Tempo: *" . $invoice->due_date->translatedFormat('d F Y') . "*\n\n"
            . "Mohon lakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari isolir otomatis.\n\n"
            . "Terima kasih,\n"
            . "*Admin ISP Jabbar*";

        SendWhatsAppJob::dispatch($customer->phone, $message);
    }
}
