<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecurringBillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view invoices')->only(['index', 'show']);
        $this->middleware('permission:edit invoices')->only(['updateBillingDate']);
    }

    public function index(Request $request)
    {
        $query = Customer::with(['package', 'invoices'])
            ->where('status', '=', 'active', 'and');

        // Filter by billing date
        if ($request->filled('billing_day')) {
            $query->whereDay('billing_date', '=', $request->billing_day, 'and');
        }

        // Filter by package
        if ($request->filled('package_id')) {
            $query->where('package_id', '=', $request->package_id, 'and');
        }

        $customers = $query->latest()->paginate(15, ['*']);
        $packages = Package::all(['*']);

        // Calculate MRR (Monthly Recurring Revenue)
        $activeCustomers = Customer::where('status', '=', 'active', 'and')
            ->with('package')
            ->get(['*']);

        $mrr = $activeCustomers->sum(fn($c) => $c->package?->price ?? 0);
        $totalActive = $activeCustomers->count();

        // Due this week (billing date within next 7 days)
        $today = Carbon::today();
        $weekEnd = Carbon::today()->addDays(7);
        
        $dueThisWeek = Customer::where('status', '=', 'active', 'and')
            ->whereNotNull('billing_date')
            ->get(['*'])
            ->filter(function ($c) use ($today, $weekEnd) {
                $billingDay = $c->billing_date->day;
                $thisMonthBilling = Carbon::today()->day($billingDay);
                return $thisMonthBilling->between($today, $weekEnd);
            })->count();

        // Unpaid invoices this month
        $unpaidThisMonth = Invoice::where('status', '=', 'unpaid', 'and')
            ->whereMonth('due_date', '=', now()->month, 'and')
            ->whereYear('due_date', '=', now()->year, 'and')
            ->count(['*']);

        $stats = [
            'mrr' => $mrr,
            'total_active' => $totalActive,
            'due_this_week' => $dueThisWeek,
            'unpaid_this_month' => $unpaidThisMonth,
        ];

        return view('billing.recurring.index', compact('customers', 'packages', 'stats'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['package', 'invoices' => function ($q) {
            $q->latest()->take(12);
        }]);

        return view('billing.recurring.show', compact('customer'));
    }

    public function updateBillingDate(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'billing_date' => 'required|date',
        ]);

        $customer->update([
            'billing_date' => Carbon::parse($validated['billing_date']),
        ]);

        return back()->with('success', 'Tanggal billing berhasil diubah!');
    }
}
