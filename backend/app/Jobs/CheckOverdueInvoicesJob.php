<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Setting;
use App\Services\RadiusService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWhatsAppJob;

class CheckOverdueInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(RadiusService $radius): void
    {
        // Get grace period setting (default 1 day after due date)
        $gracePeriodDays = (int) Setting::getValue('suspend_grace_period', 1);
        $thresholdDate = now()->subDays($gracePeriodDays);

        Log::info("Running CheckOverdueInvoicesJob. Threshold: {$thresholdDate}");

        // Find unpaid invoices past the threshold
        // We group by customer to avoid redundant suspend calls
        Invoice::where('status', 'unpaid')
            ->whereDate('due_date', '<', $thresholdDate)
            ->with('customer')
            ->chunk(100, function ($invoices) use ($radius) {
                foreach ($invoices as $invoice) {
                    $customer = $invoice->customer;

                    if (!$customer) {
                        continue;
                    }

                    // Only process checks for active customers
                    if ($customer->status === 'active') {
                        try {
                            Log::info("Suspending Customer: {$customer->name} (ID: {$customer->id}) due to Invoice #{$invoice->invoice_number}");

                            // 1. Update Local Status
                            $customer->update(['status' => 'suspended']);

                            // 2. Suspend in Radius (Block Access)
                            if ($customer->pppoe_username) {
                                $radius->suspendUser($customer->pppoe_username);
                            }

                            // 3. Send WhatsApp Notification
                            if ($customer->phone) {
                                $message = "Halaman Isolir\n\n" .
                                    "Yth. {$customer->name},\n" .
                                    "Layanan internet Anda telah di-NONAKTIFKAN sementara karena pemblokiran otomatis sistem (Lewat Jatuh Tempo).\n\n" .
                                    "No. Tagihan: {$invoice->invoice_number}\n" .
                                    "Total: Rp " . number_format($invoice->total_amount, 0, ',', '.') . "\n\n" .
                                    "Mohon segera lakukan pembayaran untuk mengaktifkan kembali layanan secara otomatis.\n\n" .
                                    "Terima Kasih - ISP Jabbar";

                                SendWhatsAppJob::dispatch($customer->phone, $message);
                            }

                        } catch (\Exception $e) {
                            Log::error("Failed to suspend customer {$customer->id}: " . $e->getMessage());
                        }
                    }
                }
            });
    }
}
