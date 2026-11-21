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

        // Find customer linked to this user's email
        $customer = Customer::where('email', $user->email)
            ->with([
                'package',
                'invoices' => function ($q) {
                    // Get unpaid or latest invoices
                    $q->latest()->take(5);
                }
            ])
            ->first();

        if (!$customer) {
            return response()->json([
                'message' => 'Data pelanggan tidak ditemukan untuk akun ini.',
            ], 404);
        }

        // Calculate unpaid amount
        $unpaidInvoices = $customer->invoices->where('status', 'unpaid');
        $totalDue = $unpaidInvoices->sum('amount');

        return response()->json([
            'customer' => $customer,
            'package' => $customer->package,
            'total_due' => $totalDue,
            'unpaid_invoices_count' => $unpaidInvoices->count(),
            'latest_invoice' => $customer->invoices->first(),
        ]);
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
