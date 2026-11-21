<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\SyncMapping;
use App\Models\Ticket;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index(Request $request): View|RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Redirect NOC/Technician to their own dashboard
        if ($user->hasRole('noc')) {
            return app(TechnicianController::class)->dashboard($request);
        }

        // Base data for all roles
        $baseData = $this->getBaseData($request);

        // Role-specific data
        $roleData = $this->getRoleSpecificData($user, $request);

        $userRole = $user->roles->first()?->name ?? 'user';

        return view('dashboard', array_merge($baseData, $roleData, ['userRole' => $userRole]));
    }

    /**
     * Get role-specific dashboard data.
     *
     * @return array<string, mixed>
     */
    private function getRoleSpecificData(User $user, Request $request): array
    {
        if ($user->hasAnyRole(['super-admin', 'admin'])) {
            return $this->getAdminDashboardData($request);
        }

        if ($user->hasRole('sales-cs')) {
            return $this->getSalesDashboardData($user);
        }

        if ($user->hasRole('finance')) {
            return $this->getFinanceDashboardData();
        }

        if ($user->hasRole('warehouse')) {
            return $this->getWarehouseDashboardData();
        }

        if ($user->hasRole('hrd')) {
            return $this->getHrdDashboardData();
        }

        return [];
    }

    /**
     * Get base data shared across all roles.
     *
     * @return array<string, mixed>
     */
    private function getBaseData(Request $request): array
    {
        $month = $request->input('month');
        $year = $request->input('year');

        // Build partner query
        $partnerQuery = Partner::query();
        if ($month) {
            $partnerQuery->whereMonth('created_at', '=', (int) $month);
        }
        if ($year) {
            $partnerQuery->whereYear('created_at', '=', (int) $year);
        }

        // Build customer query
        $customerQuery = Customer::query();
        if ($month) {
            $customerQuery->whereMonth('created_at', '=', (int) $month);
        }
        if ($year) {
            $customerQuery->whereYear('created_at', '=', (int) $year);
        }

        $totalPartners = $partnerQuery->count('*');
        $totalCustomers = $customerQuery->count('*');

        $activeQuery = clone $customerQuery;
        $suspendedQuery = clone $customerQuery;

        $activeCustomers = $activeQuery->where('status', '=', 'active')->count('*');
        $suspendedCustomers = $suspendedQuery->where('status', '=', 'suspended')->count('*');

        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');

        $newPartnersThisMonth = Partner::whereMonth('created_at', '=', $currentMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->count('*');

        $newCustomersThisMonth = Customer::whereMonth('created_at', '=', $currentMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->count('*');

        $latestPartners = Partner::query()
            ->latest('created_at')
            ->take(5)
            ->get();

        $years = $this->getAvailableYears();

        // Get packages for filter dropdown
        $packages = Package::orderBy('name')->get(['id', 'name']);

        return [
            'totalPartners' => $totalPartners,
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'suspendedCustomers' => $suspendedCustomers,
            'latestPartners' => $latestPartners,
            'years' => $years,
            'packages' => $packages,
            'newPartnersThisMonth' => $newPartnersThisMonth,
            'newCustomersThisMonth' => $newCustomersThisMonth,
        ];
    }

    /**
     * Get available years for filter dropdown.
     *
     * @return array<int>
     */
    private function getAvailableYears(): array
    {
        $partnerYears = Partner::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->filter()
            ->toArray();

        $customerYears = Customer::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->filter()
            ->toArray();

        $years = array_unique(array_merge($partnerYears, $customerYears));
        rsort($years);

        return empty($years) ? [(int) date('Y')] : $years;
    }

    /**
     * Get admin/super-admin dashboard data.
     *
     * @return array<string, mixed>
     */
    private function getAdminDashboardData(Request $request): array
    {
        // Use filter parameters or default to current month/year
        $filterMonth = $request->input('month');
        $filterYear = $request->input('year', date('Y'));
        $currentMonth = $filterMonth ? (int) $filterMonth : (int) date('m');
        $currentYear = (int) $filterYear;

        // Revenue stats
        $totalRevenue = Payment::where('status', '=', 'confirmed')->sum('amount');

        $revenueThisMonth = Payment::where('status', '=', 'confirmed')
            ->whereMonth('paid_at', '=', $currentMonth)
            ->whereYear('paid_at', '=', $currentYear)
            ->sum('amount');

        // Invoice stats
        $unpaidInvoices = Invoice::where('status', '=', 'unpaid')->count('*');

        $overdueInvoices = Invoice::where('status', '=', 'unpaid')
            ->where('due_date', '<', now())
            ->count('*');

        // Ticket stats
        $openTickets = Ticket::where('status', '=', 'open')->count('*');
        $inProgressTickets = Ticket::where('status', '=', 'in_progress')->count('*');

        // Work order stats
        $pendingWorkOrders = WorkOrder::whereNotIn('status', ['completed', 'cancelled'])->count('*');

        $completedWorkOrders = WorkOrder::where('status', '=', 'completed')
            ->whereMonth('created_at', '=', $currentMonth)
            ->count('*');

        // Revenue chart data (last 6 months)
        $revenueChart = Payment::where('status', '=', 'confirmed')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(paid_at) as month, YEAR(paid_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Customer growth chart (last 6 months)
        $customerGrowth = Customer::where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Payment by category (this month)
        $paymentByCategory = [
            'cash' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->where('payment_method', '=', 'cash')
                ->sum('amount'),
            'manual_transfer' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->where('payment_method', '=', 'bank_transfer')
                ->sum('amount'),
            'payment_gateway' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->whereIn('payment_method', ['qris', 'va', 'ewallet', 'cc'])
                ->sum('amount'),
        ];

        // Payment count by category (this month)
        $paymentCountByCategory = [
            'cash' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->where('payment_method', '=', 'cash')
                ->count(),
            'manual_transfer' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->where('payment_method', '=', 'bank_transfer')
                ->count(),
            'payment_gateway' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->whereIn('payment_method', ['qris', 'va', 'ewallet', 'cc'])
                ->count(),
        ];

        return [
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'unpaidInvoices' => $unpaidInvoices,
            'overdueInvoices' => $overdueInvoices,
            'openTickets' => $openTickets,
            'inProgressTickets' => $inProgressTickets,
            'pendingWorkOrders' => $pendingWorkOrders,
            'completedWorkOrders' => $completedWorkOrders,
            'revenueChart' => $revenueChart,
            'customerGrowth' => $customerGrowth,
            'paymentByCategory' => $paymentByCategory,
            'paymentCountByCategory' => $paymentCountByCategory,
        ];
    }

    /**
     * Get sales dashboard data.
     *
     * @return array<string, mixed>
     */
    private function getSalesDashboardData(User $user): array
    {
        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');

        // Customer acquisition stats
        $newCustomersToday = Customer::whereDate('created_at', '=', today())->count('*');

        $newCustomersThisWeek = Customer::where('created_at', '>=', now()->startOfWeek())->count('*');

        $newCustomersThisMonth = Customer::whereMonth('created_at', '=', $currentMonth)
            ->whereYear('created_at', '=', $currentYear)
            ->count('*');

        // Lead conversion stats
        $leadStats = [
            'registered' => Customer::where('status', '=', 'registered')->count('*'),
            'survey' => Customer::where('status', '=', 'survey')->count('*'),
            'approved' => Customer::where('status', '=', 'approved')->count('*'),
            'active' => Customer::where('status', '=', 'active')->count('*'),
        ];

        // Ticket stats for CS
        $openTickets = Ticket::where('status', '=', 'open')->count('*');

        $myTickets = Ticket::where('technician_id', '=', $user->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->count('*');

        // Recent customers
        $recentCustomers = Customer::with('package')
            ->latest('created_at')
            ->take(5)
            ->get();

        return [
            'newCustomersToday' => $newCustomersToday,
            'newCustomersThisWeek' => $newCustomersThisWeek,
            'newCustomersThisMonth' => $newCustomersThisMonth,
            'leadStats' => $leadStats,
            'openTickets' => $openTickets,
            'myTickets' => $myTickets,
            'recentCustomers' => $recentCustomers,
        ];
    }

    /**
     * Get finance dashboard data.
     *
     * @return array<string, mixed>
     */
    private function getFinanceDashboardData(): array
    {
        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');

        // Revenue stats
        $totalRevenue = Payment::where('status', '=', 'confirmed')->sum('amount');

        $revenueThisMonth = Payment::where('status', '=', 'confirmed')
            ->whereMonth('paid_at', '=', $currentMonth)
            ->whereYear('paid_at', '=', $currentYear)
            ->sum('amount');

        $revenueToday = Payment::where('status', '=', 'confirmed')
            ->whereDate('paid_at', '=', today())
            ->sum('amount');

        // Invoice stats
        $totalUnpaid = Invoice::where('status', '=', 'unpaid')->sum('amount');
        $unpaidInvoices = Invoice::where('status', '=', 'unpaid')->count('*');

        $overdueInvoices = Invoice::where('status', '=', 'unpaid')
            ->where('due_date', '<', now())
            ->count('*');

        $paidThisMonth = Invoice::where('status', '=', 'paid')
            ->whereMonth('updated_at', '=', $currentMonth)
            ->count('*');

        // Payment method breakdown
        $paymentMethods = Payment::where('status', '=', 'confirmed')
            ->whereMonth('paid_at', '=', $currentMonth)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Revenue chart (last 6 months)
        $revenueChart = Payment::where('status', '=', 'confirmed')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(paid_at) as month, YEAR(paid_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Recent payments
        $recentPayments = Payment::with('customer')
            ->where('status', '=', 'confirmed')
            ->latest('paid_at')
            ->take(5)
            ->get();

        // Payment by category (this month)
        $paymentByCategory = [
            'cash' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->where('payment_method', '=', 'cash')
                ->sum('amount'),
            'manual_transfer' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->where('payment_method', '=', 'bank_transfer')
                ->sum('amount'),
            'payment_gateway' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->whereIn('payment_method', ['qris', 'va', 'ewallet', 'cc'])
                ->sum('amount'),
        ];

        // Payment count by category (this month)
        $paymentCountByCategory = [
            'cash' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->where('payment_method', '=', 'cash')
                ->count(),
            'manual_transfer' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->where('payment_method', '=', 'bank_transfer')
                ->count(),
            'payment_gateway' => Payment::where('status', '=', 'confirmed')
                ->whereMonth('paid_at', '=', $currentMonth)
                ->whereYear('paid_at', '=', $currentYear)
                ->whereIn('payment_method', ['qris', 'va', 'ewallet', 'cc'])
                ->count(),
        ];

        return [
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueToday' => $revenueToday,
            'totalUnpaid' => $totalUnpaid,
            'unpaidInvoices' => $unpaidInvoices,
            'overdueInvoices' => $overdueInvoices,
            'paidThisMonth' => $paidThisMonth,
            'paymentMethods' => $paymentMethods,
            'revenueChart' => $revenueChart,
            'recentPayments' => $recentPayments,
            'paymentByCategory' => $paymentByCategory,
            'paymentCountByCategory' => $paymentCountByCategory,
        ];
    }

    /**
     * Get warehouse dashboard data.
     *
     * @return array<string, mixed>
     */
    private function getWarehouseDashboardData(): array
    {
        // Placeholder data - inventory module not yet implemented
        return [
            'totalItems' => 0,
            'lowStockItems' => 0,
            'pendingPO' => 0,
            'totalAssets' => 0,
        ];
    }

    /**
     * Get HRD dashboard data.
     *
     * @return array<string, mixed>
     */
    private function getHrdDashboardData(): array
    {
        $totalEmployees = User::count('*');
        $activeEmployees = User::where('is_active', '=', true)->count('*');

        // Role breakdown
        $roleBreakdown = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->selectRaw('roles.name, COUNT(*) as count')
            ->groupBy('roles.name')
            ->get();

        return [
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'roleBreakdown' => $roleBreakdown,
        ];
    }
}
