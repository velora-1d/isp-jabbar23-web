<?php

namespace App\Services\Analytics;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Router;
use App\Models\Attendance;
use App\Services\Network\PppoeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function __construct(protected PppoeService $pppoeService) {}

    /**
     * Get real-time network statistics.
     */
    public function getNetworkStats(): array
    {
        $routers = Router::all();
        $totalOnline = 0;
        $routerStates = [];

        foreach ($routers as $router) {
            $activeSessions = $this->pppoeService->getActiveSessions($router);
            $onlineCount = count($activeSessions);
            $totalOnline += $onlineCount;
            
            $routerStates[] = [
                'name' => $router->name,
                'online' => $onlineCount,
                'is_up' => true // Simplified, in real case we check connection
            ];
        }

        $totalCustomers = Customer::where('status', 'active')->count();

        return [
            'total_customers' => $totalCustomers,
            'total_online' => $totalOnline,
            'total_offline' => max(0, $totalCustomers - $totalOnline),
            'routers' => $routerStates
        ];
    }

    /**
     * Get financial summary (Year-to-Date).
     */
    public function getFinancialSummary(): array
    {
        $year = now()->year;
        
        $monthlyRevenue = Invoice::whereYear('period_start', $year)
            ->where('status', 'paid')
            ->select(
                DB::raw("TO_CHAR(period_start, 'Mon') as month_name"),
                DB::raw('EXTRACT(MONTH FROM period_start) as month'),
                DB::raw('SUM(total_after_tax) as total')
            )
            ->groupBy('month_name', 'month')
            ->orderBy('month')
            ->get();

        $collectionRate = 0;
        $totalInvoiced = Invoice::whereYear('period_start', $year)->sum('total_after_tax');
        $totalPaid = Invoice::whereYear('period_start', $year)->where('status', 'paid')->sum('total_after_tax');
        
        if ($totalInvoiced > 0) {
            $collectionRate = round(($totalPaid / $totalInvoiced) * 100, 2);
        }

        return [
            'monthly_revenue' => $monthlyRevenue,
            'total_ytd' => $totalPaid,
            'collection_rate' => $collectionRate,
            'unpaid_receivables' => $totalInvoiced - $totalPaid
        ];
    }

    /**
     * Get staff performance metrics.
     */
    public function getStaffPerformance(): array
    {
        // Simple performance based on attendance for now
        $today = now()->toDateString();
        
        $attendanceCount = Attendance::where(DB::raw('DATE(check_in)'), (string)$today)->count();
        $totalStaff = DB::table('users')->where('role', '!=', 'admin')->count();

        return [
            'staff_online' => $attendanceCount,
            'total_staff' => $totalStaff,
            'attendance_rate' => $totalStaff > 0 ? round(($attendanceCount / $totalStaff) * 100, 2) : 0
        ];
    }
}
