<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Traits\HasFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('permission:view payments')->only(['index']);
        $this->middleware('permission:create payments')->only(['store']);
        $this->middleware('permission:verify payments')->only(['verify']);
    }

    public function index(Request $request)
    {
        $query = Payment::with(['invoice', 'customer', 'processedBy']);

        // Apply global filters (year, month, search)
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'paid_at',
            'searchColumns' => ['payment_number', 'reference_number', 'customer.name']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        // Stats respecting filters
        $statsQuery = Payment::where('status', 'confirmed');
        if ($request->filled('year')) {
            $statsQuery->whereYear('paid_at', $request->year);
        }
        if ($request->filled('month')) {
            $statsQuery->whereMonth('paid_at', $request->month);
        }

        $stats = [
            'total' => (clone $statsQuery)->sum('amount'),
            'today' => Payment::where('status', 'confirmed')->whereDate('paid_at', today())->sum('amount'),
            'this_month' => Payment::where('status', 'confirmed')->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount'),
            'pending' => Payment::where('status', 'pending')->count(),
        ];

        // Payment methods for filter dropdown
        $paymentMethods = Payment::PAYMENT_METHODS ?? [
            'cash' => 'Cash',
            'bank_transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
            'e-wallet' => 'E-Wallet',
        ];

        return view('payments.index', compact('payments', 'stats', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);

        DB::transaction(function () use ($validated, $invoice) {
            // Check current paid amount excluding this one
            $previouslyPaid = $invoice->payments()->whereIn('status', ['confirmed', 'verified'])->sum('amount');

            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'customer_id' => $invoice->customer_id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'paid_at' => now(),
                'processed_by' => Auth::id(),
                'status' => 'confirmed', // Assuming manual entry by admin is auto-confirmed
                'notes' => $validated['notes'] ?? null,
            ]);

            // Calculate total including new payment
            $totalPaid = $previouslyPaid + $validated['amount'];

            if ($totalPaid >= $invoice->amount) {
                $invoice->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                    'payment_method' => $validated['payment_method'],
                ]);

                if ($invoice->customer) {
                    // Dispatch Event for Notification (Send Receipt)
                    \App\Events\InvoicePaid::dispatch($invoice);

                    // Auto-Restore Internet Access (Radius)
                    if ($invoice->customer->pppoe_username) {
                        try {
                            app(\App\Services\RadiusService::class)->restoreUser($invoice->customer->pppoe_username);
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Auto-Restore Failed: " . $e->getMessage());
                        }
                    }
                }

                // Invalidate User Dashboard Cache
                $user = \App\Models\User::where('email', $invoice->customer->email)->first();
                if ($user) {
                    \Illuminate\Support\Facades\Cache::forget("api_dashboard_{$user->id}");
                }

            } else {
                // Only update to partial if currently unpaid or overdue
                if (in_array($invoice->status, ['unpaid', 'overdue'])) {
                    $invoice->update(['status' => 'partial']);
                    
                    // Send WhatsApp for Partial Payment
                    if ($invoice->customer && $invoice->customer->phone) {
                        $message = "Halo {$invoice->customer->name},\n\nPembayaran sebesar *Rp " . number_format($validated['amount'], 0, ',', '.') . "* untuk tagihan *{$invoice->invoice_number}* telah diterima. (Parsial)";
                        \App\Jobs\SendWhatsAppJob::dispatch($invoice->customer->phone, $message);
                    }
                }
            }
        });

        return back()->with('success', 'Pembayaran berhasil dicatat!');
    }

    // ... (skipped irrelevant methods) ...

    private function recordPayment($invoice, $notif, $status)
    {
        // Check duplication
        $exist = Payment::where('order_id', $notif->order_id)->exists();
        if ($exist)
            return;

        DB::transaction(function () use ($invoice, $notif, $status) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $notif->gross_amount,
                'status' => $status,
                'order_id' => $notif->order_id,
                'payment_type' => $notif->payment_type,
                'transaction_time' => $notif->transaction_time,
                'raw_response' => json_encode($notif)
            ]);

            // Update Invoice Status if Paid
            if ($status == 'verified') {
                $invoice->update(['status' => 'paid']);

                \App\Models\AuditLog::log(
                    'payment_received',
                    "Payment received via {$notif->payment_type} for Invoice {$invoice->invoice_number}",
                    $invoice,
                    [],
                    ['amount' => $notif->gross_amount, 'status' => 'paid']
                );

                if ($invoice->customer) {
                    // Dispatch Event for Notification (Send Receipt)
                    \App\Events\InvoicePaid::dispatch($invoice);

                    // Auto-Restore Internet Access (Radius)
                    if ($invoice->customer->pppoe_username) {
                        try {
                            app(\App\Services\RadiusService::class)->restoreUser($invoice->customer->pppoe_username);
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Auto-Restore Failed: " . $e->getMessage());
                        }
                    }
                }

                // Invalidate User Dashboard Cache
                $user = \App\Models\User::where('email', $invoice->customer->email)->first();
                if ($user) {
                    \Illuminate\Support\Facades\Cache::forget("api_dashboard_{$user->id}");
                }
            }
        });
    }
}
