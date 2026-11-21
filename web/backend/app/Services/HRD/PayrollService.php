<?php

namespace App\Services\HRD;

use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * List payrolls with filters
     */
    public function list(string $period, ?string $search = null, ?string $status = null, int $limit = 15): LengthAwarePaginator
    {
        $query = Payroll::with('user:id,name,email')->where('period', $period);

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($limit);
    }

    /**
     * Get statistics for a period
     */
    public function getStats(string $period): array
    {
        $baseQuery = Payroll::where('period', $period);

        return [
            'total' => (clone $baseQuery)->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
            'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'paid' => (clone $baseQuery)->where('status', 'paid')->count(),
            'total_amount' => (clone $baseQuery)->sum('net_salary'),
        ];
    }

    /**
     * Calculate and create payroll
     */
    public function create(array $data): Payroll
    {
        $calculatedData = $this->calculatePayroll($data['user_id'], $data['period']);
        $calculatedData['notes'] = $data['notes'] ?? null;
        $calculatedData['status'] = 'draft';

        return Payroll::create($calculatedData);
    }

    /**
     * Update existing payroll
     */
    public function update(Payroll $payroll, array $data): Payroll
    {
        // Re-calculate based on existing user and period (or provided)
        $userId = $data['user_id'] ?? $payroll->user_id;
        $period = $data['period'] ?? $payroll->period;
        
        $calculatedData = $this->calculatePayroll($userId, $period);
        $calculatedData['notes'] = $data['notes'] ?? $payroll->notes;

        $payroll->update($calculatedData);
        return $payroll;
    }

    /**
     * Approve payroll
     */
    public function approve(Payroll $payroll): Payroll
    {
        $payroll->update(['status' => 'approved']);
        return $payroll;
    }

    /**
     * Mark payroll as paid
     */
    public function markAsPaid(Payroll $payroll): Payroll
    {
        $payroll->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);
        return $payroll;
    }

    /**
     * Core logic to calculate payroll based on attendance and user salary
     */
    public function calculatePayroll(int $userId, string $period): array
    {
        $user = User::findOrFail($userId);
        $startDate = Carbon::parse($period . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // Get attendance summary using DB query for efficiency
        $attendanceStats = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw("
                COUNT(*) as working_days,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_days
            ")
            ->first();

        $workingDays = 25; // Default working days per month
        $presentDays = $attendanceStats->present_days ?? 0;
        $lateDays = $attendanceStats->late_days ?? 0;
        $absentDays = $attendanceStats->absent_days ?? 0;
        
        $totalPresent = $presentDays + $lateDays;
        
        // Basic calculations
        $basicSalary = $user->salary ?? 0;
        $dailySalary = $basicSalary / $workingDays;
        
        // Deductions
        $lateDeduction = $lateDays * 10000; // Example: 10k per late
        $absentDeduction = ($workingDays - $totalPresent) * $dailySalary;
        
        $totalDeductions = $lateDeduction + $absentDeduction;
        $netSalary = $basicSalary - $totalDeductions;
        
        return [
            'user_id' => $userId,
            'period' => $period,
            'basic_salary' => $basicSalary,
            'allowances' => 0,
            'overtime' => 0,
            'bonus' => 0,
            'deductions' => $totalDeductions,
            'tax' => 0,
            'net_salary' => max(0, $netSalary),
            'working_days' => $workingDays,
            'present_days' => $totalPresent,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
        ];
    }
}
