<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Finance\PaymentService;
use App\Http\Requests\Finance\MidtransNotificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Get or create Snap Token for an invoice.
     */
    public function getSnapToken(Invoice $invoice): JsonResponse
    {
        $token = $this->paymentService->updateInvoiceSnapToken($invoice);
        
        if (!$token) {
            return response()->json(['message' => 'Failed to generate payment token'], 500);
        }

        return response()->json(['snap_token' => $token]);
    }

    /**
     * Handle Midtrans Notification (Webhook).
     */
    public function handleNotification(MidtransNotificationRequest $request): JsonResponse
    {
        $processed = $this->paymentService->handleNotification($request->validated());

        if (!$processed) {
            return response()->json(['message' => 'Notification processing failed or invalid signature'], 400);
        }

        return response()->json(['message' => 'Notification processed']);
    }
}
