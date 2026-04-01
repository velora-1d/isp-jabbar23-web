<?php

namespace App\Services\Analytics;

use App\Repositories\AnalyticsRepository;
use App\Services\Network\PppoeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    public function __construct(
        protected PppoeService $pppoeService,
        protected AnalyticsRepository $analyticsRepository
    ) {}

    /**
     * Get network operational statistics from background-synced cache.
     */
    public function getNetworkStats(): array
    {
        $totalCustomers = $this->analyticsRepository->getTotalCustomers();
        $activePppoe = (int) Cache::get("network:stats:total_pppoe", 0);

        return [
            'total_customers' => $totalCustomers,
            'total_online'    => $activePppoe,
            'total_offline'   => max(0, $totalCustomers - $activePppoe),
            'active_pppoe'    => $activePppoe,
            'hotspot_active'  => 0, 
            'routers_online'  => $this->analyticsRepository->getOnlineRoutersCount(),
            'total_routers'   => $this->analyticsRepository->getTotalRoutersCount(),
            'routers'         => $this->analyticsRepository->getRouterStatuses(),
        ];
    }

    /**
     * Get financial summary (Year-to-Date) with caching.
     */
    public function getFinancialSummary(): array
    {
        return Cache::remember('analytics:finance', 1800, function() {
            $year = now()->year;
            $currentMonth = now()->month;
            
            $monthlyRevenue = $this->analyticsRepository->getMonthlyRevenueBreakdown($year);
            $monthRevenue = $this->analyticsRepository->getRevenueForMonth($year, $currentMonth);
            
            $totalInvoiced = $this->analyticsRepository->getTotalInvoicedByYear($year);
            $totalPaid = $this->analyticsRepository->getTotalPaidByYear($year);
            
            $collectionRate = 0;
            if ($totalInvoiced > 0) {
                $collectionRate = round(($totalPaid / $totalInvoiced) * 100, 2);
            }

            return [
                'monthly_revenue' => $monthlyRevenue,
                'month_revenue' => $monthRevenue,
                'total_ytd' => $totalPaid,
                'collection_rate' => $collectionRate,
                'unpaid_receivables' => $totalInvoiced - $totalPaid
            ];
        });
    }

    /**
     * Get staff performance metrics with caching.
     */
    public function getStaffPerformance(): array
    {
        return Cache::remember('analytics:staff', 600, function() {
            $today = now()->toDateString();
            $attendanceCount = $this->analyticsRepository->getAttendanceCountForToday($today);
            $totalStaff = $this->analyticsRepository->getTotalOperationalStaffCount();

            return [
                'staff_online' => $attendanceCount,
                'total_staff' => $totalStaff,
                'attendance_rate' => $totalStaff > 0 ? round(($attendanceCount / $totalStaff) * 100, 2) : 0
            ];
        });
    }

    /**
     * Get detailed invoice statistics with caching.
     */
    public function getInvoiceStats(): array
    {
        return Cache::remember('analytics:invoices', 900, function() {
            return [
                'unpaid_count' => $this->analyticsRepository->getUnpaidInvoicesCount(),
                'overdue_count' => $this->analyticsRepository->getOverdueInvoicesCount(),
            ];
        });
    }

    /**
     * Get ticket statistics with caching.
     */
    public function getTicketStats(): array
    {
        return Cache::remember('analytics:tickets', 300, function() {
            return [
                'open_count' => $this->analyticsRepository->getOpenTicketsCount(),
                'in_progress_count' => $this->analyticsRepository->getInProgressTicketsCount(),
            ];
        });
    }

    /**
     * Get work order statistics with caching.
     */
    public function getWorkOrderStats(): array
    {
        return Cache::remember('analytics:work_orders', 300, function() {
            $currentMonth = now()->month;
            return [
                'pending_count' => $this->analyticsRepository->getPendingWorkOrdersCount(),
                'completed_this_month' => $this->analyticsRepository->getCompletedWorkOrdersThisMonthCount($currentMonth),
            ];
        });
    }

    /**
     * Get inventory count and stats with caching.
     */
    public function getInventoryStats(): array
    {
        return Cache::remember('analytics:inventory', 3600, function() {
            $month = now()->month;
            $year = now()->year;
            return [
                'low_stock_count'  => $this->analyticsRepository->getLowStockInventoryCount(),
                'total_items'      => $this->analyticsRepository->getTotalInventoryItemsCount(),
                'total_value'      => $this->analyticsRepository->getTotalInventoryValue(),
                'critical_count'   => $this->analyticsRepository->getCriticalInventoryCount(),
                'pending_po_count' => $this->analyticsRepository->getPendingPoCount(),
                'total_categories' => $this->analyticsRepository->getTotalCategoriesCount(),
                'items_in_month'   => $this->analyticsRepository->getMonthlyInventoryTransactions('in', $month, $year),
                'items_out_month'  => $this->analyticsRepository->getMonthlyInventoryTransactions('out', $month, $year),
                'total_vendors'    => $this->analyticsRepository->getTotalVendorsCount(),
                'low_stock_items'  => $this->analyticsRepository->getLowStockItemsList(7),
                'by_category'      => $this->analyticsRepository->getItemsByCategory(),
                'mutasi_stok_bulanan'=> $this->analyticsRepository->getMonthlyInventoryTransactionsHistory(6),
            ];
        });
    }

    /**
     * Get customer growth data for the last 6 months with caching.
     */
    public function getCustomerGrowth(): array
    {
        return Cache::remember('analytics:growth', 3600, function() {
            return $this->analyticsRepository->getCustomerGrowth(6);
        });
    }

    /**
     * Get payment category distribution for the current month with caching.
     */
    public function getPaymentCategoryDistribution(): array
    {
        return Cache::remember('analytics:payments', 1800, function() {
            $currentMonth = now()->month;
            $currentYear = now()->year;

            return [
                'cash' => $this->analyticsRepository->getPaymentSumByMethodAndMonth('cash', $currentMonth, $currentYear),
                'manual_transfer' => $this->analyticsRepository->getPaymentSumByMethodAndMonth('bank_transfer', $currentMonth, $currentYear),
                'payment_gateway' => $this->analyticsRepository->getPaymentSumByMethodAndMonth(['qris', 'va', 'ewallet', 'cc'], $currentMonth, $currentYear),
            ];
        });
    }
}

