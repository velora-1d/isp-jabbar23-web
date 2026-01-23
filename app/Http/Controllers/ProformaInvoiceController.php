<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ProformaInvoice;
use App\Traits\HasFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProformaInvoiceController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('permission:view invoices')->only(['index', 'show']);
        $this->middleware('permission:create invoices')->only(['create', 'store']);
        $this->middleware('permission:edit invoices')->only(['convert', 'cancel']);
    }

    public function index(Request $request)
    {
        $query = ProformaInvoice::with('customer');

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'issue_date',
            'searchColumns' => ['proforma_number', 'customer.name']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        $proformas = $query->latest()->paginate(15)->withQueryString();

        // Stats respecting filters
        $statsQuery = ProformaInvoice::query();
        if ($request->filled('year')) {
            $statsQuery->whereYear('issue_date', $request->year);
        }
        if ($request->filled('month')) {
            $statsQuery->whereMonth('issue_date', $request->month);
        }

        $stats = [
            'pending_count' => (clone $statsQuery)->where('status', 'pending')->count(),
            'pending_value' => (clone $statsQuery)->where('status', 'pending')->sum('amount'),
            'converted_count' => (clone $statsQuery)->where('status', 'converted')->count(),
            'expired_count' => (clone $statsQuery)->where('status', 'expired')->count(),
        ];

        // Filter options
        $statuses = [
            'pending' => 'Pending',
            'converted' => 'Converted',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
        ];

        return view('billing.proforma.index', compact('proformas', 'stats', 'statuses'));
    }

    public function create()
    {
        $customers = Customer::where('status', '=', 'active', 'and')->with('package')->get(['*']);
        return view('billing.proforma.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'valid_days' => 'required|integer|min:1|max:90',
            'notes' => 'nullable|string|max:500',
        ]);

        $proformaNumber = 'PRO-' . now()->format('Ym') . '-' . strtoupper(Str::random(5));

        ProformaInvoice::create([
            'proforma_number' => $proformaNumber,
            'customer_id' => $validated['customer_id'],
            'amount' => $validated['amount'],
            'issue_date' => now(),
            'valid_until' => now()->addDays($validated['valid_days']),
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return redirect()->route('billing.proforma')
            ->with('success', 'Proforma Invoice berhasil dibuat!');
    }

    public function show(ProformaInvoice $proforma)
    {
        $proforma->load(['customer.package', 'convertedInvoice']);
        return view('billing.proforma.show', compact('proforma'));
    }

    public function convert(ProformaInvoice $proforma)
    {
        if ($proforma->status !== 'pending') {
            return back()->with('error', 'Proforma tidak dapat dikonversi!');
        }

        // Create real invoice
        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . now()->format('Ym') . '-' . strtoupper(Str::random(5)),
            'customer_id' => $proforma->customer_id,
            'amount' => $proforma->amount,
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
            'due_date' => now()->addDays(7),
            'status' => 'unpaid',
        ]);

        // Update proforma
        $proforma->update([
            'status' => 'converted',
            'converted_invoice_id' => $invoice->id,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Proforma berhasil dikonversi ke Invoice!');
    }

    public function cancel(ProformaInvoice $proforma)
    {
        if ($proforma->status !== 'pending') {
            return back()->with('error', 'Proforma tidak dapat dibatalkan!');
        }

        $proforma->update(['status' => 'cancelled']);

        return back()->with('success', 'Proforma Invoice dibatalkan.');
    }
}
