<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HRD\StorePayrollRequest;
use App\Http\Requests\Admin\HRD\UpdatePayrollRequest;
use App\Models\Payroll;
use App\Services\HRD\PayrollService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct(
        protected PayrollService $payrollService
    ) {}

    /**
     * List payrolls with statistics
     */
    public function index(Request $request): JsonResponse
    {
        $period = $request->query('period', date('Y-m'));
        
        return response()->json([
            'payrolls' => $this->payrollService->list(
                $period,
                $request->query('search'),
                $request->query('status'),
                $request->query('limit', 15)
            ),
            'stats' => $this->payrollService->getStats($period)
        ]);
    }

    /**
     * Store a newly created payroll
     */
    public function store(StorePayrollRequest $request): JsonResponse
    {
        $payroll = $this->payrollService->create($request->validated());
        
        return response()->json([
            'message' => 'Payroll berhasil dibuat (draft)',
            'payroll' => $payroll
        ], 201);
    }

    /**
     * Display the specified payroll
     */
    public function show(Payroll $payroll): JsonResponse
    {
        return response()->json($payroll->load('user:id,name,email'));
    }

    /**
     * Update the specified payroll
     */
    public function update(UpdatePayrollRequest $request, Payroll $payroll): JsonResponse
    {
        $updatedPayroll = $this->payrollService->update($payroll, $request->validated());
        
        return response()->json([
            'message' => 'Payroll berhasil diperbarui',
            'payroll' => $updatedPayroll
        ]);
    }

    /**
     * Approve the specified payroll
     */
    public function approve(Payroll $payroll): JsonResponse
    {
        $approvedPayroll = $this->payrollService->approve($payroll);
        
        return response()->json([
            'message' => 'Payroll berhasil disetujui',
            'payroll' => $approvedPayroll
        ]);
    }

    /**
     * Mark the specified payroll as paid
     */
    public function markPaid(Payroll $payroll): JsonResponse
    {
        $paidPayroll = $this->payrollService->markAsPaid($payroll);
        
        return response()->json([
            'message' => 'Payroll berhasil ditandai sebagai lunas',
            'payroll' => $paidPayroll
        ]);
    }

    /**
     * Remove the specified payroll
     */
    public function destroy(Payroll $payroll): JsonResponse
    {
        $payroll->delete();
        
        return response()->json(['message' => 'Payroll berhasil dihapus']);
    }
}
