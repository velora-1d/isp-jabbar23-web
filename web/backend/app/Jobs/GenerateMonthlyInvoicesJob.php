<?php

namespace App\Jobs;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $month;
    protected $year;

    /**
     * Create a new job instance.
     */
    public function __construct(?int $month = null, ?int $year = null)
    {
        $this->month = $month ?? now()->month;
        $this->year = $year ?? now()->year;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting batch invoice generation for {$this->month}/{$this->year}");

        // Optimize: Only select active customers
        // Chunk by 100 to avoid memory overflow
        Customer::where('is_active', true)
            ->chunkById(100, function ($customers) {
                foreach ($customers as $customer) {
                    // Dispatch job to GenerateSingleInvoice
                    // This creates a granular job for each invoice
                    GenerateSingleInvoiceJob::dispatch(
                        $customer->id, 
                        $this->month, 
                        $this->year
                    );
                }
            });

        Log::info("Batch invoice generation jobs dispatched.");
    }
}
