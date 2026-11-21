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
        return Cache::remember('analytics:network', 60, function() {
            $totalCustomers = $this->analyticsRepository->getTotalCustomers();
            $prevCustomers = $this->analyticsRepository->getActiveCustomersForPreviousMonth();
            $activePppoe = (int) Cache::get("network:stats:total_pppoe", 0);

            $trend = 0;
            if ($prevCustomers > 0) {
                $trend = round((($totalCustomers - $prevCustomers) / $prevCustomers) * 100, 1);
            }

            $totalRouters = $this->analyticsRepository->getTotalRoutersCount();
            $onlineRouters = $this->analyticsRepository->getOnlineRoutersCount();
            $health = $totalRouters > 0 ? round(($onlineRouters / $totalRouters) * 100) : 100;

            return [
                'total_customers' => $totalCustomers,
                'total_online'    => $activePppoe,
                'total_offline'   => max(0, $totalCustomers - $activePppoe),
                'active_pppoe'    => $activePppoe,
                'hotspot_active'  => (int) \DB::table('hotspot_vouchers')->where('status', 'active')->count(), 
                'routers_online'  => $onlineRouters,
                'total_routers'   => $totalRouters,
                'routers'         => $this->analyticsRepository->getRouterStatuses(),
                'odp_count'       => $this->analyticsRepository->getOdpCount(),
                'olt_count'       => $this->analyticsRepository->getOltCount(),
                'infra_summary'   => $this->analyticsRepository->getInfrastructureSummary(),
                'network_health'  => $health,
                'trend'           => ($trend >= 0 ? '+' : '') . $trend . '%',
            ];
        });
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
            $prevMonthRevenue = $this->analyticsRepository->getRevenueForPreviousMonth();
            
            $totalInvoiced = $this->analyticsRepository->getTotalInvoicedByYear($year);
            $totalPaid = $this->analyticsRepository->getTotalPaidByYear($year);
            
            $collectionRate = 0;
            if ($totalInvoiced > 0) {
                $collectionRate = round(($totalPaid / $totalInvoiced) * 100, 2);
            }

            // Calculate billing trend (current vs previous month collection rate)
            $prevMonth = now()->subMonth();
            $prevRate = $this->analyticsRepository->getCollectionRateForMonth($prevMonth->year, $prevMonth->month);
            $billTrend = 0;
            if ($prevRate > 0) {
                $billTrend = round((($collectionRate - $prevRate) / $prevRate) * 100, 1);
            }

            // Calculate revenue trend
            $revTrend = 0;
            if ($prevMonthRevenue > 0) {
                $revTrend = round((($monthRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 1);
            }

            return [
                'monthly_revenue' => $monthlyRevenue,
                'month_revenue' => $monthRevenue,
                'total_ytd' => $totalPaid,
                'collection_rate' => $collectionRate,
                'unpaid_receivables' => $totalInvoiced - $totalPaid,
                'regional_performance' => $this->analyticsRepository->getBillingPerformanceByRegion(),
                'revenue_trend' => ($revTrend >= 0 ? '+' : '') . $revTrend . '%',
                'billing_trend' => ($billTrend >= 0 ? '+' : '') . $billTrend . '%',
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
            $totalStaff = \App\Models\User::count();

            return [
                'staff_online' => $attendanceCount,
                'total_staff' => $totalStaff,
                'by_role' => $this->analyticsRepository->getStaffCountByRole(),
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
                'monthly_trend' => $this->analyticsRepository->getTicketMonthlyTrend(6),
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

