<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Router;
use App\Models\Attendance;
use App\Models\Ticket;
use App\Models\WorkOrder;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\InventoryTransaction;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsRepository
{
    /**
     * Get total customer count.
     */
    public function getTotalCustomers(): int
    {
        return Customer::count();
    }

    /**
     * Get online routers count.
     */
    public function getOnlineRoutersCount(): int
    {
        return Router::where('status', 'online')->count();
    }

    /**
     * Get total routers count.
     */
    public function getTotalRoutersCount(): int
    {
        return Router::count();
    }

    /**
     * Get router statuses.
     */
    public function getRouterStatuses()
    {
        return Router::select('name', 'status as is_up')->get()->map(function($r) {
            return [
                'name' => $r->name,
                'is_up' => $r->is_up === 'online'
            ];
        });
    }

    /**
     * Get monthly revenue breakdown for the given year.
     */
    public function getMonthlyRevenueBreakdown(int $year)
    {
        return Invoice::whereYear('period_start', $year)
            ->where('status', 'paid')
            ->select(
                DB::raw("TO_CHAR(period_start, 'Mon') as month_name"),
                DB::raw('EXTRACT(MONTH FROM period_start) as month'),
                DB::raw('SUM(total_after_tax) as total')
            )
            ->groupBy('month_name', 'month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get total revenue for a specific month.
     */
    public function getRevenueForMonth(int $year, int $month): float
    {
        return Invoice::whereYear('period_start', $year)
            ->whereMonth('period_start', $month)
            ->where('status', 'paid')
            ->sum('total_after_tax');
    }

    /**
     * Get total invoiced amount for the given year.
     */
    public function getTotalInvoicedByYear(int $year): float
    {
        return Invoice::whereYear('period_start', $year)->sum('total_after_tax');
    }

    /**
     * Get total paid invoiced amount for the given year.
     */
    public function getTotalPaidByYear(int $year): float
    {
        return Invoice::whereYear('period_start', $year)->where('status', 'paid')->sum('total_after_tax');
    }

    /**
     * Get total staff attendance count for today.
     */
    public function getAttendanceCountForToday(string $date): int
    {
        return Attendance::query()->whereDate('created_at', $date)->count();
    }

    /**
     * Get total staff excluding super_admin and admin.
     */
    public function getTotalOperationalStaffCount(): int
    {
        return DB::table('users')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('model_has_roles')
                    ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', 'App\\Models\\User')
                    ->whereNotIn('roles.name', ['admin', 'super_admin']);
            })
            ->count();
    }

    /**
     * Get total unpaid invoices.
     */
    public function getUnpaidInvoicesCount(): int
    {
        return Invoice::where('status', 'unpaid')->count();
    }

    /**
     * Get total overdue invoices.
     */
    public function getOverdueInvoicesCount(): int
    {
        return Invoice::where('status', 'unpaid')->where('due_date', '<', now())->count();
    }

    /**
     * Get open tickets count.
     */
    public function getOpenTicketsCount(): int
    {
        return Ticket::where('status', 'open')->count();
    }

    /**
     * Get in-progress tickets count.
     */
    public function getInProgressTicketsCount(): int
    {
        return Ticket::where('status', 'in_progress')->count();
    }

    /**
     * Get pending work orders count.
     */
    public function getPendingWorkOrdersCount(): int
    {
        return WorkOrder::whereNotIn('status', ['completed', 'cancelled'])->count();
    }

    /**
     * Get completed work orders count for the current month.
     */
    public function getCompletedWorkOrdersThisMonthCount(int $month): int
    {
        return WorkOrder::where('status', 'completed')->whereMonth('created_at', $month)->count();
    }

    /**
     * Get low stock inventory items count.
     */
    public function getLowStockInventoryCount(): int
    {
        return InventoryItem::get()->filter(fn($i) => $i->total_stock <= $i->min_stock_alert && $i->total_stock > 0)->count();
    }

    public function getTotalInventoryItemsCount(): int
    {
        return InventoryItem::count();
    }

    public function getTotalInventoryValue(): float
    {
        return InventoryItem::get()->sum(fn($i) => $i->total_stock * $i->purchase_price);
    }

    public function getCriticalInventoryCount(): int
    {
        return InventoryItem::get()->filter(fn($i) => $i->total_stock <= 0)->count();
    }

    public function getPendingPoCount(): int
    {
        return PurchaseOrder::where('status', 'draft')->orWhere('status', 'pending')->orWhere('status', 'processing')->count();
    }

    public function getTotalCategoriesCount(): int
    {
        return InventoryCategory::count();
    }

    public function getMonthlyInventoryTransactions(string $type, int $month, int $year): float
    {
        return InventoryTransaction::where('type', $type)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('quantity');
    }

    public function getMonthlyInventoryTransactionsHistory(int $months = 6): array
    {
        $transactions = InventoryTransaction::where('created_at', '>=', now()->subMonths($months - 1)->startOfMonth())
            ->select(
                DB::raw("TO_CHAR(created_at, 'Mon') as month_name"),
                DB::raw('EXTRACT(MONTH FROM created_at) as month'),
                DB::raw('EXTRACT(YEAR FROM created_at) as year'),
                'type',
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->groupBy('month_name', 'month', 'year', 'type')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $monthsList = collect();
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthsList->push([
                'name' => $date->format('M'),
                'sort_key' => $date->format('Y-m'),
                'masuk' => 0,
                'keluar' => 0
            ]);
        }
        
        $monthsDict = $monthsList->keyBy('sort_key')->toArray();

        foreach ($transactions as $t) {
            $sortKey = sprintf('%04d-%02d', $t->year, $t->month);
            if (isset($monthsDict[$sortKey])) {
                if ($t->type === 'in') {
                    $monthsDict[$sortKey]['masuk'] = (float) $t->total_quantity;
                } elseif ($t->type === 'out') {
                    $monthsDict[$sortKey]['keluar'] = (float) $t->total_quantity;
                }
            }
        }

        return array_values($monthsDict);
    }

    public function getTotalVendorsCount(): int
    {
        return Vendor::count();
    }

    public function getLowStockItemsList(int $limit = 7): array
    {
        return InventoryItem::get()
            ->filter(fn($i) => $i->total_stock <= $i->min_stock_alert)
            ->map(function($i) {
                return [
                    'name' => $i->name,
                    'stock' => $i->total_stock,
                    'min_stock' => $i->min_stock_alert
                ];
            })
            ->sortBy('stock')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function getItemsByCategory(): array
    {
        return DB::table('inventory_items')
            ->join('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
            ->select('inventory_categories.name as category', DB::raw('count(inventory_items.id) as count'))
            ->groupBy('inventory_categories.id', 'inventory_categories.name')
            ->get()
            ->toArray();
    }

    /**
     * Get customer growth data for the last N months.
     */
    public function getCustomerGrowth(int $months = 6): array
    {
        return Customer::where('created_at', '>=', now()->subMonths($months))
            ->select(
                DB::raw("TO_CHAR(created_at, 'Mon') as month_name"),
                DB::raw('EXTRACT(MONTH FROM created_at) as month'),
                DB::raw('EXTRACT(YEAR FROM created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month_name', 'month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * Get total payment amount for a specific method and month.
     */
    public function getPaymentSumByMethodAndMonth(array|string $methods, int $month, int $year): float
    {
        $query = Payment::where('status', 'confirmed')
            ->whereMonth('paid_at', $month)
            ->whereYear('paid_at', $year);

        if (is_array($methods)) {
            $query->whereIn('payment_method', $methods);
        } else {
            $query->where('payment_method', $methods);
        }

        return $query->sum('amount');
    }
}
