<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Finance\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $invoices = Invoice::with('customer.package')
            ->when($request->customer_id, fn($q) => $q->where('customer_id', $request->customer_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->month, fn($q) => $q->whereMonth('period_start', $request->month))
            ->when($request->year, fn($q) => $q->whereYear('period_start', $request->year))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json($invoices);
    }

    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024',
        ]);

        $result = $this->invoiceService->generateMonthlyInvoices(
            $request->integer('month'),
            $request->integer('year')
        );

        return response()->json([
            'message' => "Successfully generated {$result['generated']} invoices. Skipped {$result['skipped']} existing.",
            'data' => $result
        ]);
    }

    public function pay(Request $request, Invoice $invoice): JsonResponse
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $this->invoiceService->markAsPaid($invoice, $request->payment_method);

        return response()->json(['message' => 'Invoice marked as paid']);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json($invoice->load('customer.package'));
    }
}
