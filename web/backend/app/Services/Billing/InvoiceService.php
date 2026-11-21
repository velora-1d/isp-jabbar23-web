<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\Customer;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    /**
     * Get list of invoices with filters.
     */
    public function index(array $filters = []): LengthAwarePaginator
    {
        $query = Invoice::with(['customer:id,name,customer_id']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('customer_id', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['year'])) {
            $query->whereYear('period_start', $filters['year']);
        }

        if (!empty($filters['month'])) {
            $query->whereMonth('period_start', $filters['month']);
        }

        return $query->latest('period_start')->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get billing statistics.
     */
    public function getStats(): array
    {
        $currentMonth = now()->startOfMonth();
        
        return [
            'total_billing' => Invoice::sum('total_after_tax'),
            'unpaid_count' => Invoice::where('status', 'unpaid')->count(),
            'paid_this_month' => Invoice::where('status', 'paid')
                ->whereMonth('payment_date', now()->month)
                ->sum('total_after_tax'),
            'overdue_count' => Invoice::where('status', 'unpaid')
                ->where('due_date', '<', now())
                ->count(),
        ];
    }

    /**
     * Generate invoices for all active customers for a specific month.
     */
    public function generateMonthlyInvoices(string $monthYear): array
    {
        $date = Carbon::parse($monthYear . "-01");
        $periodStart = $date->copy()->startOfMonth();
        $periodEnd = $date->copy()->endOfMonth();
        $dueDate = $periodStart->copy()->addDays(10); // Default due date: 10th of the month

        $customers = Customer::where('status', Customer::STATUS_ACTIVE)
            ->whereDoesntHave('invoices', function ($q) use ($periodStart) {
                $q->where('period_start', $periodStart);
            })->get();

        $count = 0;
        /** @var Customer[] $customers */
        foreach ($customers as $customer) {
            DB::transaction(function () use ($customer, $periodStart, $periodEnd, $dueDate, &$count) {
                Invoice::create([
                    'invoice_number' => $this->generateInvoiceNumber($customer, $periodStart),
                    'customer_id'    => $customer->id,
                    'amount'         => $customer->package?->price ?? 0,
                    'period_start'   => $periodStart,
                    'period_end'     => $periodEnd,
                    'due_date'       => $dueDate,
                    'status'         => 'unpaid',
                ]);
                $count++;
            });
        }

        return [
            'generated' => $count,
            'period'    => $periodStart->format('F Y'),
        ];
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            $invoice->update([
                'status'         => 'paid',
                'payment_date'   => $data['payment_date'] ?? now(),
                'payment_method' => $data['payment_method'] ?? 'manual_transfer',
            ]);

            // Optional: Create payment record if needed
            // $invoice->payments()->create([...]);

            return $invoice;
        });
    }

    /**
     * Generate a unique invoice number.
     * Format: INV/YYYYMM/ID/RANDOM
     */
    private function generateInvoiceNumber(Customer $customer, Carbon $date): string
    {
        $prefix = "INV";
        $period = $date->format('Ym');
        $customerId = str_pad($customer->id, 4, '0', STR_PAD_LEFT);
        $random = strtoupper(bin2hex(random_bytes(2)));

        return "{$prefix}/{$period}/{$customerId}/{$random}";
    }
}
