<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ProformaInvoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProformaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ProformaInvoice::with('customer:id,name,identifier');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('proforma_number', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($cq) use ($request) {
                      $cq->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $proformas = $query->latest()->paginate($request->get('limit', 15));

        $stats = [
            'pending_count' => ProformaInvoice::where('status', 'pending')->count(),
            'pending_value' => ProformaInvoice::where('status', 'pending')->sum('amount'),
            'converted_count' => ProformaInvoice::where('status', 'converted')->count(),
            'expired_count' => ProformaInvoice::where('status', 'expired')->count(),
        ];

        return response()->json([
            'proformas' => $proformas,
            'stats' => $stats
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'valid_days' => 'required|integer|min:1|max:90',
            'notes' => 'nullable|string|max:500',
        ]);

        $proformaNumber = 'PRO-' . now()->format('Ym') . '-' . strtoupper(Str::random(5));

        $proforma = ProformaInvoice::create([
            'proforma_number' => $proformaNumber,
            'customer_id' => $validated['customer_id'],
            'amount' => $validated['amount'],
            'issue_date' => now(),
            'valid_until' => now()->addDays($validated['valid_days']),
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Proforma Invoice berhasil dibuat',
            'proforma' => $proforma
        ], 201);
    }

    public function show(ProformaInvoice $proforma): JsonResponse
    {
        $proforma->load(['customer.package', 'convertedInvoice']);
        return response()->json($proforma);
    }

    public function convert(ProformaInvoice $proforma): JsonResponse
    {
        if ($proforma->status !== 'pending') {
            return response()->json(['message' => 'Proforma tidak dapat dikonversi!'], 422);
        }

        try {
            DB::beginTransaction();

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

            DB::commit();

            return response()->json([
                'message' => 'Proforma berhasil dikonversi ke Invoice',
                'invoice' => $invoice
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal konversi: ' . $e->getMessage()], 500);
        }
    }

    public function cancel(ProformaInvoice $proforma): JsonResponse
    {
        if ($proforma->status !== 'pending') {
            return response()->json(['message' => 'Proforma tidak dapat dibatalkan!'], 422);
        }

        $proforma->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Proforma Invoice dibatalkan.']);
    }
}
