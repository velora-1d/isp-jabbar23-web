<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\SyncMapping;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\WorkOrder;
use App\Models\User;
use App\Models\Odp;
use App\Models\Olt;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        
        // Redirect NOC/Technician to their own dashboard
        if ($user && $user->hasRole('noc')) {
            return app(\App\Http\Controllers\TechnicianController::class)->dashboard($request);
        }

        // Base data for all roles
        $baseData = $this->getBaseData($request);
        
        // Role-specific data
        $roleData = [];
        
        if ($user->hasAnyRole(['super-admin', 'admin'])) {
            $roleData = $this->getAdminDashboardData($request);
        } elseif ($user->hasRole('sales')) {
            $roleData = $this->getSalesDashboardData($request);
        } elseif ($user->hasRole('finance')) {
            $roleData = $this->getFinanceDashboardData($request);
        } elseif ($user->hasRole('warehouse')) {
            $roleData = $this->getWarehouseDashboardData($request);
        } elseif ($user->hasRole('hrd')) {
            $roleData = $this->getHrdDashboardData($request);
        }
        
        return view('dashboard', array_merge($baseData, $roleData, ['userRole' => $user->roles->first()->name ?? 'user']));
    }
    
    private function getBaseData(Request $request): array
    {
        $queryPartners = Partner::query();
        $queryCustomers = Customer::query();

        if ($request->filled('month')) {
            $queryPartners->whereMonth('created_at', $request->month);
            $queryCustomers->whereMonth('created_at', $request->month);
        }
        
        if ($request->filled('year')) {
            $queryPartners->whereYear('created_at', $request->year);
            $queryCustomers->whereYear('created_at', $request->year);
        }

        $totalPartners = $queryPartners->count();
        $totalCustomers = $queryCustomers->count();
        $activeCustomers = (clone $queryCustomers)->where('status', 'active')->count();
        $suspendedCustomers = (clone $queryCustomers)->where('status', 'suspended')->count();
        
        $newPartnersThisMonth = Partner::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
            
        $newCustomersThisMonth = Customer::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        $latestPartners = Partner::latest()->take(5)->get();

        $partnerYears = Partner::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year')->toArray();
        $customerYears = Customer::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year')->toArray();
        $years = array_unique(array_merge($partnerYears, $customerYears));
        rsort($years);
        if (empty($years)) {
            $years = [date('Y')];
        }

        return compact(
            'totalPartners', 'totalCustomers', 'activeCustomers', 'suspendedCustomers',
            'latestPartners', 'years', 'newPartnersThisMonth', 'newCustomersThisMonth'
        );
    }
    
    private function getAdminDashboardData(Request $request): array
    {
        // Revenue stats
        $totalRevenue = Payment::where('status', 'confirmed')->sum('amount');
        $revenueThisMonth = Payment::where('status', 'confirmed')
            ->whereMonth('paid_at', date('m'))
            ->whereYear('paid_at', date('Y'))
            ->sum('amount');
            
        // Invoice stats
        $unpaidInvoices = Invoice::where('status', 'unpaid')->count();
        $overdueInvoices = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->count();
            
        // Ticket stats
        $openTickets = Ticket::where('status', 'open')->count();
        $inProgressTickets = Ticket::where('status', 'in_progress')->count();
        
        // Work order stats
        $pendingWorkOrders = WorkOrder::whereNotIn('status', ['completed', 'cancelled'])->count();
        $completedWorkOrders = WorkOrder::where('status', 'completed')
            ->whereMonth('created_at', date('m'))
            ->count();
            
        // Revenue chart data (last 6 months)
        $revenueChart = Payment::where('status', 'confirmed')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(paid_at) as month, YEAR(paid_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        // Customer growth chart (last 6 months)
        $customerGrowth = Customer::where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        return compact(
            'totalRevenue', 'revenueThisMonth', 'unpaidInvoices', 'overdueInvoices',
            'openTickets', 'inProgressTickets', 'pendingWorkOrders', 'completedWorkOrders',
            'revenueChart', 'customerGrowth'
        );
    }
    
    private function getSalesDashboardData(Request $request): array
    {
        // Customer acquisition stats
        $newCustomersToday = Customer::whereDate('created_at', today())->count();
        $newCustomersThisWeek = Customer::where('created_at', '>=', now()->startOfWeek())->count();
        $newCustomersThisMonth = Customer::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
            
        // Lead conversion (customers by status)
        $leadStats = [
            'registered' => Customer::where('status', 'registered')->count(),
            'survey' => Customer::where('status', 'survey')->count(),
            'approved' => Customer::where('status', 'approved')->count(),
            'active' => Customer::where('status', 'active')->count(),
        ];
        
        // Ticket stats for CS
        $openTickets = Ticket::where('status', 'open')->count();
        $myTickets = Ticket::where('technician_id', auth()->user()?->id)->whereIn('status', ['open', 'in_progress'])->count();
        
        // Recent customers
        $recentCustomers = Customer::with('package')
            ->latest()
            ->take(5)
            ->get();
            
        return compact('newCustomersToday', 'newCustomersThisWeek', 'newCustomersThisMonth', 'leadStats', 'openTickets', 'myTickets', 'recentCustomers');
    }
    
    private function getFinanceDashboardData(Request $request): array
    {
        // Revenue stats
        $totalRevenue = Payment::where('status', 'confirmed')->sum('amount');
        $revenueThisMonth = Payment::where('status', 'confirmed')
            ->whereMonth('paid_at', date('m'))
            ->whereYear('paid_at', date('Y'))
            ->sum('amount');
        $revenueToday = Payment::where('status', 'confirmed')
            ->whereDate('paid_at', today())
            ->sum('amount');
            
        // Invoice stats
        $totalUnpaid = Invoice::where('status', 'unpaid')->sum('total_amount');
        $unpaidInvoices = Invoice::where('status', 'unpaid')->count();
        $overdueInvoices = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->count();
        $paidThisMonth = Invoice::where('status', 'paid')
            ->whereMonth('updated_at', date('m'))
            ->count();
            
        // Payment method breakdown
        $paymentMethods = Payment::where('status', 'confirmed')
            ->whereMonth('paid_at', date('m'))
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();
            
        // Revenue chart (last 6 months)
        $revenueChart = Payment::where('status', 'confirmed')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(paid_at) as month, YEAR(paid_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        // Recent payments
        $recentPayments = Payment::with('customer')
            ->where('status', 'confirmed')
            ->latest('paid_at')
            ->take(5)
            ->get();
            
        return compact(
            'totalRevenue', 'revenueThisMonth', 'revenueToday', 'totalUnpaid',
            'unpaidInvoices', 'overdueInvoices', 'paidThisMonth', 'paymentMethods',
            'revenueChart', 'recentPayments'
        );
    }
    
    private function getWarehouseDashboardData(Request $request): array
    {
        // Since we don't have inventory models yet, return placeholder data
        return [
            'totalItems' => 0,
            'lowStockItems' => 0,
            'pendingPO' => 0,
            'totalAssets' => 0,
        ];
    }
    
    private function getHrdDashboardData(Request $request): array
    {
        // Employee stats
        $totalEmployees = User::count();
        $activeEmployees = User::where('is_active', true)->count();
        
        // Role breakdown
        $roleBreakdown = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->selectRaw('roles.name, COUNT(*) as count')
            ->groupBy('roles.name')
            ->get();
            
        return compact('totalEmployees', 'activeEmployees', 'roleBreakdown');
    }
}
