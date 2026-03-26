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
        return response()->json([
            'status' => 'success',
            'data' => [
                'network' => $this->analyticsService->getNetworkStats(),
                'finance' => $this->analyticsService->getFinancialSummary(),
                'staff'   => $this->analyticsService->getStaffPerformance(),
            ]
        ]);
    }
}
