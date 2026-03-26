<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoices for all active customers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting invoice generation...');

        $customers = Customer::where('status', 'active')->with('package')->get();
        $date = Carbon::now();
        $periodStart = $date->copy()->startOfMonth();
        $periodEnd = $date->copy()->endOfMonth();
        
        // Due date config (misal tgl 10 bulan ini)
        // Jika generate tgl 1, due date tgl 10. Jika generate tgl 20, due date tgl 10 bulan depan? 
        // Asumsi standard: Tagihan bulan berjalan, due date tgl 10 bulan berjalan.
        $dueDate = $date->copy()->day(10); 

        $count = 0;

        foreach ($customers as $customer) {
            // Skip if no package assigned
            if (!$customer->package) {
                $this->warn("Skipping customer {$customer->name} (No Package)");
                continue;
            }

            // Check if invoice exists for this period
            $exists = Invoice::where('customer_id', $customer->id)
                ->whereDate('period_start', $periodStart)
                ->exists();

            if ($exists) {
                $this->info("Invoice exists for {$customer->name} - Skiping");
                continue;
            }

            Invoice::create([
                'invoice_number' => 'INV-' . $date->format('Ym') . '-' . strtoupper(Str::random(5)),
                'customer_id' => $customer->id,
                'amount' => $customer->package->price,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'due_date' => $dueDate,
                'status' => 'unpaid',
            ]);

            $count++;
            $this->info("Generated invoice for {$customer->name}");
        }

        $this->info("Completed! Generated {$count} invoices.");
    }
}
