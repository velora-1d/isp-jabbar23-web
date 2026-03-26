<?php

namespace App\Services\HRD;

use App\Models\Payroll;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PayrollService
{
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

    public function create(array $data): Payroll
    {
        $data = $this->calculatePayroll($data);
        $data['status'] = 'draft';

        return Payroll::create($data);
    }

    public function update(Payroll $payroll, array $data): Payroll
    {
        $data = $this->calculatePayroll($data);

        if ($data['status'] === 'paid' && $payroll->status !== 'paid') {
            $data['paid_at'] = now();
        }

        $payroll->update($data);
        return $payroll;
    }

    protected function calculatePayroll(array $data): array
    {
        if (isset($data['user_id']) && isset($data['period'])) {
            $startDate = Carbon::createFromFormat('Y-m', $data['period'])->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $data['period'])->endOfMonth();

            $attendanceStats = Attendance::where('user_id', $data['user_id'])
                ->whereBetween('date', [$startDate, $endDate])
                ->selectRaw("
                    COUNT(*) as working_days,
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_days
                ")
                ->first();

            $data['working_days'] = $attendanceStats->working_days ?? 0;
            $data['present_days'] = $attendanceStats->present_days ?? 0;
            $data['absent_days'] = $attendanceStats->absent_days ?? 0;
            $data['late_days'] = $attendanceStats->late_days ?? 0;
        }

        $gross = ($data['basic_salary'] ?? 0) + ($data['allowances'] ?? 0) +
            ($data['overtime'] ?? 0) + ($data['bonus'] ?? 0);
        $totalDeductions = ($data['deductions'] ?? 0) + ($data['tax'] ?? 0);
        
        $data['net_salary'] = $gross - $totalDeductions;

        return $data;
    }
}
