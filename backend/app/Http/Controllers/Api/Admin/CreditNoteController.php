<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CreditNoteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CreditNote::with('customer:id,name,identifier');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('credit_number', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($cq) use ($request) {
                      $cq->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        $creditNotes = $query->latest()->paginate($request->get('limit', 15));

        $stats = [
            'pending_count' => CreditNote::where('status', 'pending')->count(),
            'pending_value' => CreditNote::where('status', 'pending')->sum('amount'),
            'applied_count' => CreditNote::where('status', 'applied')->count(),
            'applied_value' => CreditNote::where('status', 'applied')->sum('amount'),
        ];

        return response()->json([
            'credit_notes' => $creditNotes,
            'stats' => $stats
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|in:overpayment,refund,discount,adjustment,other',
            'notes' => 'nullable|string|max:500',
        ]);

        $creditNumber = 'CN-' . now()->format('Ym') . '-' . strtoupper(Str::random(5));

        $creditNote = CreditNote::create([
            'credit_number' => $creditNumber,
            'customer_id' => $validated['customer_id'],
            'amount' => $validated['amount'],
            'issue_date' => now(),
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Credit Note berhasil dibuat',
            'credit_note' => $creditNote
        ], 201);
    }

    public function show(CreditNote $creditNote): JsonResponse
    {
        $creditNote->load(['customer', 'appliedInvoice']);

        $unpaidInvoices = Invoice::where('customer_id', $creditNote->customer_id)
            ->where('status', 'unpaid')
            ->get();

        return response()->json([
            'credit_note' => $creditNote,
            'unpaid_invoices' => $unpaidInvoices
        ]);
    }

    public function apply(Request $request, CreditNote $creditNote): JsonResponse
    {
        if ($creditNote->status !== 'pending') {
            return response()->json(['message' => 'Credit Note tidak dapat diterapkan!'], 422);
        }

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);

        // Apply credit to invoice
        if ($creditNote->amount >= $invoice->amount) {
            $invoice->update(['status' => 'paid']);
        }

        $creditNote->update([
            'status' => 'applied',
            'applied_to_invoice_id' => $invoice->id,
        ]);

        return response()->json([
            'message' => 'Credit Note berhasil diterapkan ke invoice',
            'invoice' => $invoice
        ]);
    }

    public function cancel(CreditNote $creditNote): JsonResponse
    {
        if ($creditNote->status !== 'pending') {
            return response()->json(['message' => 'Credit Note tidak dapat dibatalkan!'], 422);
        }

        $creditNote->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Credit Note dibatalkan.']);
    }
}
