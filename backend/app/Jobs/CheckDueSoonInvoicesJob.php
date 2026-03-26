<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckDueSoonInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Warn 3 days before due date (configurable?)
        $daysBefore = (int) Setting::getValue('due_warning_days', 3);
        $targetDate = now()->addDays($daysBefore)->toDateString();

        Log::info("Running CheckDueSoonInvoicesJob. Checking for due date: {$targetDate}");

        Invoice::where('status', 'unpaid')
            ->whereDate('due_date', $targetDate)
            ->with('customer')
            ->chunk(100, function ($invoices) use ($daysBefore) {
                foreach ($invoices as $invoice) {
                    $customer = $invoice->customer;

                    if (!$customer || !$customer->phone || $customer->status !== 'active') {
                        continue;
                    }

                    try {
                        Log::info("Sending Warning to: {$customer->name} (Invoice #{$invoice->invoice_number})");

                        $amount = number_format($invoice->amount, 0, ',', '.');
                        $dueDate = $invoice->due_date->translatedFormat('d F Y');

                        $message = "⚠️ *Peringatan Jatuh Tempo*\n\n" .
                            "Halo, *{$customer->name}*!\n\n" .
                            "Mengingatkan bahwa tagihan internet Anda akan jatuh tempo dalam *{$daysBefore} hari lagi* ({$dueDate}).\n\n" .
                            "🧾 No. Tagihan: *{$invoice->invoice_number}*\n" .
                            "💰 Total: *Rp {$amount}*\n\n" .
                            "Mohon segera lakukan pembayaran untuk menghindari isolir otomatis.\n" .
                            "Abaikan pesan ini jika sudah membayar.\n\n" .
                            "*ISP Jabbar*";

                        SendWhatsAppJob::dispatch($customer->phone, $message);

                    } catch (\Exception $e) {
                        Log::error("Failed to warn customer {$customer->id}: " . $e->getMessage());
                    }
                }
            });
    }
}
