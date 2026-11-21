<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    public function __construct(protected AnalyticsService $analyticsService) {}

    /**
     * Get full dashboard stats.
     */
    public function index(): JsonResponse
    {
        \Illuminate\Support\Facades\Log::info('Analytics API Hit by User: ' . (\Illuminate\Support\Facades\Auth::id() ?? 'Guest'));
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'network'         => $this->analyticsService->getNetworkStats(),
                'finance'         => $this->analyticsService->getFinancialSummary(),
                'staff'           => $this->analyticsService->getStaffPerformance(),
                'invoices'        => $this->analyticsService->getInvoiceStats(),
                'tickets'         => $this->analyticsService->getTicketStats(),
                'work_orders'     => $this->analyticsService->getWorkOrderStats(),
                'inventory'       => $this->analyticsService->getInventoryStats(),
                'customer_growth' => $this->analyticsService->getCustomerGrowth(),
                'payment_dist'    => $this->analyticsService->getPaymentCategoryDistribution(),
            ]
        ]);
    }
}
