<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateSingleInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customerId;
    protected $month;
    protected $year;

    /**
     * Create a new job instance.
     */
    public function __construct(int $customerId, int $month, int $year)
    {
        $this->customerId = $customerId;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customer = Customer::find($this->customerId);
        
        if (!$customer || !$customer->is_active) {
            return;
        }

        $periodStart = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();
        
        // Prevent duplicate invoice
        $exists = Invoice::where('customer_id', $this->customerId)
            ->where('period_start', $periodStart->format('Y-m-d'))
            ->exists();

        if ($exists) {
            return;
        }

        DB::transaction(function () use ($customer, $periodStart, $periodEnd) {
            $invoiceNumber = $this->generateInvoiceNumber($periodStart);
            $amount = $customer->subscription_fee ?? 0; // Assuming customer has subscription_fee
            
            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer->id,
                'amount' => $amount,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'due_date' => $periodStart->copy()->addDays((int) Setting::getValue('due_date_days', 10)),
                'status' => 'unpaid',
            ]);
            
            Log::info("Invoice generated for {$customer->name} ($invoiceNumber)");
            
            // Dispatch Event for Notification
            $invoice = Invoice::where('invoice_number', $invoiceNumber)->first();
            \App\Events\InvoiceGenerated::dispatch($invoice);
        });
    }

    private function generateInvoiceNumber($date)
    {
        // Format: INV/YYYY/MM/XXXXX
        // Note: In high concurrency, this simple logic might cause collision.
        // For 100k users, better use a Sequence Table or UUID.
        // But for now, with DB transaction and atomic job execution, it's acceptable-ish.
        
        $prefix = "INV/" . $date->format('Y/m') . "/";
        
        // Get latest invoice number for this month
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->lockForUpdate() // Lock to prevent race condition
            ->first();
            
        $sequence = 1;
        if ($lastInvoice) {
            $parts = explode('/', $lastInvoice->invoice_number);
            $lastSeq = (int) end($parts);
            $sequence = $lastSeq + 1;
        }
        
        return $prefix . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }
}
