<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Odp;
use App\Models\Olt;
use App\Models\Partner;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin|finance');
    }

    /**
     * Display the financial report (legacy).
     */
    public function index(Request $request): View
    {
        $hasFilter = $request->has('month') || $request->has('year');
        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');

        $query = Invoice::with(['customer.package']);

        if ($request->filled('month')) {
            $query->whereMonth('due_date', '=', $request->month);
        } elseif (!$hasFilter) {
            $request->merge(['month' => $currentMonth]);
            $query->whereMonth('due_date', '=', $currentMonth);
        }

        if ($request->filled('year')) {
            $query->whereYear('due_date', '=', $request->year);
        } elseif (!$hasFilter) {
            $request->merge(['year' => $currentYear]);
            $query->whereYear('due_date', '=', $currentYear);
        }

        $totalRevenue = (clone $query)->where('status', '=', 'paid')->sum('amount');
        $totalUnpaid = (clone $query)->where('status', '=', 'unpaid')->sum('amount');
        $countPaid = (clone $query)->where('status', '=', 'paid')->count(['*']);
        $countUnpaid = (clone $query)->where('status', '=', 'unpaid')->count(['*']);
        $countTotal = $countPaid + $countUnpaid;

        $invoices = $query->latest('due_date')->get(['*']);

        $years = Invoice::selectRaw('YEAR(due_date) as year', [])
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([$currentYear]);
        }

        return view('reports.index', compact(
            'invoices', 'totalRevenue', 'totalUnpaid',
            'countPaid', 'countUnpaid', 'countTotal', 'years'
        ));
    }

    /**
     * Revenue Reports
     */
    public function revenue(Request $request): View
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $totalRevenue = Payment::query()
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->where('status', '=', 'confirmed')
            ->sum('amount');

        $revenueByMonth = Payment::query()
            ->where('status', '=', 'confirmed')
            ->where('paid_at', '>=', Carbon::now()->subMonths(12))
            ->select([
                DB::raw('YEAR(paid_at) as year'),
                DB::raw('MONTH(paid_at) as month'),
                DB::raw('SUM(amount) as total'),
            ])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get(['*']);

        $revenueByMethod = Payment::query()
            ->where('status', '=', 'confirmed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->select([
                'payment_method',
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count'),
            ])
            ->groupBy('payment_method')
            ->get(['*']);

        $pendingInvoices = Invoice::query()
            ->whereIn('status', ['unpaid', 'partial'])
            ->sum('amount');

        $paidCount = Invoice::query()
            ->where('status', '=', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(['*']);

        $unpaidCount = Invoice::query()
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(['*']);

        return view('reports.revenue', compact(
            'totalRevenue', 'revenueByMonth', 'revenueByMethod',
            'pendingInvoices', 'paidCount', 'unpaidCount',
            'startDate', 'endDate'
        ));
    }

    /**
     * Customer Reports
     */
    public function customers(Request $request): View
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $totalCustomers = Customer::query()->count(['*']);
        $activeCustomers = Customer::query()->where('status', '=', 'active')->count(['*']);
        $newCustomers = Customer::query()->whereBetween('created_at', [$startDate, $endDate])->count(['*']);
        $churnedCustomers = Customer::query()
            ->where('status', '=', 'inactive')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count(['*']);

        $customersByPackage = Customer::query()
            ->where('status', '=', 'active')
            ->whereNotNull('package_id')
            ->select(['package_id', DB::raw('COUNT(*) as total')])
            ->groupBy('package_id')
            ->with(['package:id,name'])
            ->get(['*']);

        $customerGrowth = Customer::query()
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select([
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
            ])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get(['*']);

        $topCustomers = Customer::query()
            ->select(['customers.id', 'customers.name', 'customers.email', DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid')])
            ->leftJoin('payments', function ($join) {
                $join->on('customers.id', '=', 'payments.customer_id')
                     ->where('payments.status', '=', 'confirmed');
            })
            ->groupBy('customers.id', 'customers.name', 'customers.email')
            ->orderByDesc('total_paid')
            ->limit(10)
            ->get();

        return view('reports.customers', compact(
            'totalCustomers', 'activeCustomers', 'newCustomers', 'churnedCustomers',
            'customersByPackage', 'customerGrowth', 'topCustomers',
            'startDate', 'endDate'
        ));
    }

    /**
     * Network Reports
     */
    public function network(Request $request): View
    {
        $odpStats = [
            'total' => Odp::query()->count('*'),
            'available' => Odp::query()->where('status', '=', 'active')->count('*'),
            'full' => Odp::query()->where('status', '=', 'full')->count('*'),
            'maintenance' => Odp::query()->where('status', '=', 'maintenance')->count('*'),
        ];

        $oltStats = [
            'total' => Olt::query()->count(['*']),
            'online' => Olt::query()->where('status', '=', 'online')->count(['*']),
            'offline' => Olt::query()->where('status', '=', 'offline')->count(['*']),
        ];

        $routerStats = [];

        $bandwidthUsage = [
            'total_allocated' => Customer::query()
                ->where('customers.status', '=', 'active')
                ->join('packages', 'customers.package_id', '=', 'packages.id')
                ->sum('packages.speed_down'),
        ];

        return view('reports.network', compact('odpStats', 'oltStats', 'routerStats', 'bandwidthUsage'));
    }

    /**
     * Commission Reports
     */
    public function commissions(Request $request): View
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $partners = Partner::query()->withCount(['customers as total_customers'])->get(['*']);

        $commissionData = [];
        $totalCommissions = 0;
        $paidCommissions = 0;
        $pendingCommissions = 0;

        foreach ($partners as $partner) {
            $customerPayments = Payment::query()
                ->whereHas('customer', function ($q) use ($partner) {
                    $q->where('partner_id', '=', $partner->id);
                })
                ->where('status', '=', 'confirmed')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->sum('amount');

            $commissionRate = $partner->commission_rate ?? 0;
            $commission = $customerPayments * ($commissionRate / 100);

            $commissionData[] = [
                'partner' => $partner,
                'amount' => $commission,
                'customer_payments' => $customerPayments,
            ];

            $totalCommissions += $commission;
        }

        $topPerformers = Partner::query()
            ->withCount(['customers'])
            ->orderByDesc('customers_count')
            ->limit(10)
            ->get(['*']);

        return view('reports.commissions', compact(
            'commissionData', 'totalCommissions', 'paidCommissions', 'pendingCommissions',
            'topPerformers', 'startDate', 'endDate'
        ));
    }

    /**
     * Profit & Loss Reports
     */
    public function profitLoss(Request $request): View
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Income (Paid Invoices Subtotal, excluding Tax)
        $totalIncome = Payment::query()
            ->where('status', '=', 'confirmed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        // Total Tax (PPN 11%) Collected from Paid Invoices
        $totalTax = Invoice::query()
            ->where('status', '=', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('tax_amount');

        // Total Expenses
        $totalExpenses = Expense::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $netProfit = $totalIncome - $totalExpenses;

        $expensesByCategory = Expense::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->select(['category', DB::raw('SUM(amount) as total')])
            ->groupBy('category')
            ->get();

        $categories = Expense::CATEGORIES;

        return view('reports.profit_loss', compact(
            'totalIncome', 'totalTax', 'totalExpenses', 'netProfit',
            'expensesByCategory', 'categories', 'startDate', 'endDate'
        ));
    }
}
