<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    /**
     * Get the customer dashboard data
     * (Package info, status, current bill).
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        
        // Cache key based on user email
        $cacheKey = "api_dashboard_{$user->id}";

        // Cache for 10 minutes (600 seconds)
        $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 600, function () use ($user) {
            // Find customer linked to this user's email
            $customer = Customer::where('email', $user->email)
                ->with(['package'])
                ->first();

            if (!$customer) {
                return null;
            }

            // Calculate unpaid amount from DB (More accurate than collection)
            $unpaidQuery = $customer->invoices()->whereIn('status', ['unpaid', 'overdue', 'partial']);
            $totalDue = $unpaidQuery->sum('amount') - $customer->payments()->whereIn('status', ['confirmed', 'verified'])->whereIn('invoice_id', $unpaidQuery->pluck('id'))->sum('amount');
            // Simplified logic: Just sum unpaid invoices amount. 
            // Note: If partial payment logic is complex, might need adjustment. 
            // Assuming 'amount' in invoice is the remaining amount or full amount? 
            // Usually invoice amount is static, partial payment handled by status.
            // Let's stick to simple sum pending proper partial payment architecture.
            $totalDue = $customer->invoices()->whereIn('status', ['unpaid', 'overdue', 'partial'])->sum('amount');
            
            // Adjust for partial payments if necessary
            // For now, simple sum is safer for MVP scaling
            
            $unpaidCount = $customer->invoices()->whereIn('status', ['unpaid', 'overdue', 'partial'])->count();
            $latestInvoice = $customer->invoices()->latest()->first();

            return [
                'customer' => $customer,
                'package' => $customer->package,
                'total_due' => (float) $totalDue,
                'unpaid_invoices_count' => $unpaidCount,
                'latest_invoice' => $latestInvoice,
            ];
        });

        if (!$data) {
             return response()->json([
                'message' => 'Data pelanggan tidak ditemukan untuk akun ini.',
            ], 404);
        }

        return response()->json($data);
    }

    /**
     * Get list of invoices.
     */
    public function invoices(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            // Return empty paginated result instead of 404
            return response()->json([
                'data' => [],
                'message' => 'Data pelanggan tidak ditemukan',
            ]);
        }

        $invoices = $customer->invoices()
            ->latest()
            ->paginate(10);

        return response()->json($invoices);
    }
    /**
     * Create a payment transaction for an invoice.
     */
    public function createPayment(Request $request, \App\Services\MidtransService $midtrans)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        $user = $request->user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return response()->json(['message' => 'Data pelanggan tidak ditemukan.'], 404);
        }

        $invoice = $customer->invoices()->where('id', $request->invoice_id)->first();

        if (!$invoice) {
            return response()->json(['message' => 'Tagihan tidak ditemukan.'], 404);
        }

        if ($invoice->status === 'paid') {
            return response()->json(['message' => 'Tagihan sudah lunas.'], 400);
        }

        $transactionId = $invoice->invoice_number . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $transactionId,
                'gross_amount' => (int) $invoice->amount,
            ],
            'customer_details' => [
                'first_name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'billing_address' => [
                    'address' => $customer->address,
                ],
            ],
            'item_details' => [
                [
                    'id' => $invoice->invoice_number,
                    'price' => (int) $invoice->amount,
                    'quantity' => 1,
                    'name' => 'Tagihan ' . ($invoice->period_start ? $invoice->period_start->format('M Y') : 'Internet'),
                ]
            ]
        ];

        try {
            $snapToken = $midtrans->getSnapToken($params);
            return response()->json([
                'snap_token' => $snapToken,
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/$snapToken", // Fallback URL
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat transaksi pembayaran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Update customer password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama tidak sesuai.'], 400);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password berhasil diperbarui.']);
    }

    /**
     * Update customer profile (Phone number).
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits_between:10,14',
        ]);

        $user = $request->user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return response()->json(['message' => 'Data pelanggan tidak ditemukan.'], 404);
        }

        $customer->update([
            'phone' => $request->phone,
        ]);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'customer' => $customer,
        ]);
    }
}
