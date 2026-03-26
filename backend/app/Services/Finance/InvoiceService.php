<?php

namespace App\Services\Finance;

use App\Models\Customer;
use App\Models\Invoice;
use App\Jobs\SendWhatsappJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\RadiusService;

class InvoiceService
{
    public function __construct(
        protected RadiusService $radiusService
    ) {}
    /**
     * Generate invoices for all active customers for a specific month.
     */
    public function generateMonthlyInvoices(int $month, int $year): array
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $dueDate = $startDate->copy()->day(10); // Default jatuh tempo tanggal 10

        $customers = Customer::where('status', 'active')->with('package')->get();
        $generatedCount = 0;
        $skippedCount = 0;

        foreach ($customers as $customer) {
            // Check if invoice already exists for this period
            $exists = Invoice::where('customer_id', $customer->id)
                ->where('period_start', $startDate->toDateString())
                ->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber($month, $year),
                'customer_id' => $customer->id,
                'amount' => $customer->package->price ?? 0,
                'period_start' => $startDate->toDateString(),
                'period_end' => $endDate->toDateString(),
                'due_date' => $dueDate->toDateString(),
                'status' => 'unpaid',
            ]);

            // Kirim Notifikasi WA
            if ($customer->phone) {
                $message = "Halo {$customer->name},\n\nTagihan internet Jabbar23 Anda untuk periode " . $startDate->format('F Y') . " telah terbit.\n\n" .
                           "Nomor Invoice: {$invoice->invoice_number}\n" .
                           "Nominal: Rp " . number_format($invoice->amount, 0, ',', '.') . "\n" .
                           "Jatuh Tempo: " . $dueDate->format('d-m-Y') . "\n\n" .
                           "Mohon segera lakukan pembayaran. Terima kasih.";
                
                SendWhatsappJob::dispatch($customer->phone, $message);
            }

            $generatedCount++;
        }

        return [
            'generated' => $generatedCount,
            'skipped' => $skippedCount,
        ];
    }

    /**
     * Generate unique invoice number.
     */
    private function generateInvoiceNumber(int $month, int $year): string
    {
        $prefix = "INV/{$year}/" . str_pad($month, 2, '0', STR_PAD_LEFT) . "/";
        $latest = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($latest) {
            $lastNum = (int) substr($latest->invoice_number, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }

        return $prefix . $newNum;
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(Invoice $invoice, string $method): void
    {
        $invoice->update([
            'status' => 'paid',
            'payment_date' => now(),
            'payment_method' => $method,
        ]);
        
        // Kirim Notifikasi Pembayaran Sukses
        $customer = $invoice->customer;
        if ($customer) {
            // Otomatisasi Aktivasi (Wave 7 Task 25)
            if ($customer->status === Customer::STATUS_SUSPENDED && $customer->pppoe_username) {
                $this->radiusService->restoreUser($customer->pppoe_username);
                $customer->update(['status' => Customer::STATUS_ACTIVE]);
                // Log status change is handled by Customer model boot method
            }

            if ($customer->phone) {
                $message = "Terima kasih {$customer->name}!\n\n" .
                           "Pembayaran invoice {$invoice->invoice_number} sebesar Rp " . number_format($invoice->amount, 0, ',', '.') . " telah kami terima melalui {$method}.\n\n" .
                           "Layanan Anda akan tetap aktif. Salam, Jabbar23.";
                
                SendWhatsappJob::dispatch($customer->phone, $message);
            }
        }
    }
}
