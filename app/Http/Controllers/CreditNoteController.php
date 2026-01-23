<?php

namespace App\Http\Controllers;

use App\Models\CreditNote;
use App\Models\Customer;
use App\Models\Invoice;
use App\Traits\HasFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreditNoteController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('permission:view invoices')->only(['index', 'show']);
        $this->middleware('permission:create invoices')->only(['create', 'store']);
        $this->middleware('permission:edit invoices')->only(['apply', 'cancel']);
    }

    public function index(Request $request)
    {
        $query = CreditNote::with('customer');

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'issue_date',
            'searchColumns' => ['credit_number', 'customer.name']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply reason filter
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        $creditNotes = $query->latest()->paginate(15)->withQueryString();

        // Stats respecting filters
        $statsQuery = CreditNote::query();
        if ($request->filled('year')) {
            $statsQuery->whereYear('issue_date', $request->year);
        }
        if ($request->filled('month')) {
            $statsQuery->whereMonth('issue_date', $request->month);
        }

        $stats = [
            'pending_count' => (clone $statsQuery)->where('status', 'pending')->count(),
            'pending_value' => (clone $statsQuery)->where('status', 'pending')->sum('amount'),
            'applied_count' => (clone $statsQuery)->where('status', 'applied')->count(),
            'applied_value' => (clone $statsQuery)->where('status', 'applied')->sum('amount'),
        ];

        // Filter options
        $statuses = [
            'pending' => 'Pending',
            'applied' => 'Applied',
            'cancelled' => 'Cancelled',
        ];
        $reasons = [
            'overpayment' => 'Overpayment',
            'refund' => 'Refund',
            'discount' => 'Discount',
            'adjustment' => 'Adjustment',
            'other' => 'Other',
        ];

        return view('billing.credit-notes.index', compact('creditNotes', 'stats', 'statuses', 'reasons'));
    }

    public function create()
    {
        $customers = Customer::where('status', '=', 'active', 'and')->get(['*']);
        return view('billing.credit-notes.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|in:overpayment,refund,discount,adjustment,other',
            'notes' => 'nullable|string|max:500',
        ]);

        $creditNumber = 'CN-' . now()->format('Ym') . '-' . strtoupper(Str::random(5));

        CreditNote::create([
            'credit_number' => $creditNumber,
            'customer_id' => $validated['customer_id'],
            'amount' => $validated['amount'],
            'issue_date' => now(),
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return redirect()->route('billing.credit-notes')
            ->with('success', 'Credit Note berhasil dibuat!');
    }

    public function show(CreditNote $creditNote)
    {
        $creditNote->load(['customer', 'appliedInvoice']);

        // Get unpaid invoices for this customer
        $unpaidInvoices = Invoice::where('customer_id', '=', $creditNote->customer_id, 'and')
            ->where('status', '=', 'unpaid', 'and')
            ->get(['*']);

        return view('billing.credit-notes.show', compact('creditNote', 'unpaidInvoices'));
    }

    public function apply(Request $request, CreditNote $creditNote)
    {
        if ($creditNote->status !== 'pending') {
            return back()->with('error', 'Credit Note tidak dapat diterapkan!');
        }

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id'], ['*']);

        // Apply credit to invoice
        if ($creditNote->amount >= $invoice->amount) {
            $invoice->update(['status' => 'paid']);
        }

        $creditNote->update([
            'status' => 'applied',
            'applied_to_invoice_id' => $invoice->id,
        ]);

        return back()->with('success', 'Credit Note berhasil diterapkan ke invoice!');
    }

    public function cancel(CreditNote $creditNote)
    {
        if ($creditNote->status !== 'pending') {
            return back()->with('error', 'Credit Note tidak dapat dibatalkan!');
        }

        $creditNote->update(['status' => 'cancelled']);

        return back()->with('success', 'Credit Note dibatalkan.');
    }
}
