<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Services\HRD\PayrollService;
use App\Http\Requests\Admin\HRD\StorePayrollRequest;
use App\Http\Requests\Admin\HRD\UpdatePayrollRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function __construct(
        protected PayrollService $payrollService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $period = $request->string('period', Carbon::now()->format('Y-m'))->value();
        
        return response()->json([
            'payrolls' => $this->payrollService->list(
                $period,
                $request->string('search')->value(),
                $request->string('status')->value(),
                $request->integer('limit', 15)
            ),
            'stats' => $this->payrollService->getStats($period),
            'period' => $period
        ]);
    }

    public function store(StorePayrollRequest $request): JsonResponse
    {
        $payroll = $this->payrollService->create($request->validated());

        return response()->json([
            'message' => 'Payroll berhasil dibuat',
            'payroll' => $payroll
        ], 201);
    }

    public function show(Payroll $payroll): JsonResponse
    {
        return response()->json($payroll->load('user'));
    }

    public function update(UpdatePayrollRequest $request, Payroll $payroll): JsonResponse
    {
        $this->payrollService->update($payroll, $request->validated());

        return response()->json([
            'message' => 'Payroll berhasil diperbarui',
            'payroll' => $payroll
        ]);
    }

    public function approve(Payroll $payroll): JsonResponse
    {
        $payroll->update(['status' => 'approved']);
        return response()->json(['message' => 'Payroll disetujui']);
    }

    public function markPaid(Payroll $payroll): JsonResponse
    {
        $payroll->update(['status' => 'paid', 'paid_at' => now()]);
        return response()->json(['message' => 'Payroll dibayar']);
    }

    public function destroy(Payroll $payroll): JsonResponse
    {
        $payroll->delete();
        return response()->json(['message' => 'Payroll berhasil dihapus']);
    }
}
