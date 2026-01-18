<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin');
    }

    public function index(Request $request)
    {
        $period = $request->get('period', Carbon::now()->format('Y-m'));

        $payrolls = Payroll::with('user')
            ->where(['period' => $period])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Payroll::where(['period' => $period])->count(['*']),
            'draft' => Payroll::where(['period' => $period, 'status' => 'draft'])->count(['*']),
            'approved' => Payroll::where(['period' => $period, 'status' => 'approved'])->count(['*']),
            'paid' => Payroll::where(['period' => $period, 'status' => 'paid'])->count(['*']),
            'total_amount' => Payroll::where(['period' => $period])->sum('net_salary'),
        ];

        return view('hrd.payroll.index', compact('payrolls', 'stats', 'period'));
    }

    public function create()
    {
        $users = User::where(['is_active' => true])->orderBy('name')->get();
        return view('hrd.payroll.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'period' => 'required|date_format:Y-m',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Calculate attendance stats
        $startDate = Carbon::createFromFormat('Y-m', $validated['period'])->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $validated['period'])->endOfMonth();

        $attendanceStats = Attendance::where(['user_id' => $validated['user_id']])
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw("
                COUNT(*) as working_days,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_days
            ")
            ->first();

        $validated['working_days'] = $attendanceStats->working_days ?? 0;
        $validated['present_days'] = $attendanceStats->present_days ?? 0;
        $validated['absent_days'] = $attendanceStats->absent_days ?? 0;
        $validated['late_days'] = $attendanceStats->late_days ?? 0;

        // Calculate net salary
        $gross = ($validated['basic_salary'] ?? 0) + ($validated['allowances'] ?? 0) + 
                 ($validated['overtime'] ?? 0) + ($validated['bonus'] ?? 0);
        $totalDeductions = ($validated['deductions'] ?? 0) + ($validated['tax'] ?? 0);
        $validated['net_salary'] = $gross - $totalDeductions;
        $validated['status'] = 'draft';

        Payroll::create($validated);

        return redirect()->route('payroll.index', ['period' => $validated['period']])
            ->with('success', 'Payroll berhasil dibuat!');
    }

    public function edit(Payroll $payroll)
    {
        $users = User::where(['is_active' => true])->orderBy('name')->get();
        return view('hrd.payroll.edit', compact('payroll', 'users'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,approved,paid',
            'notes' => 'nullable|string',
        ]);

        // Recalculate net salary
        $gross = ($validated['basic_salary'] ?? 0) + ($validated['allowances'] ?? 0) + 
                 ($validated['overtime'] ?? 0) + ($validated['bonus'] ?? 0);
        $totalDeductions = ($validated['deductions'] ?? 0) + ($validated['tax'] ?? 0);
        $validated['net_salary'] = $gross - $totalDeductions;

        if ($validated['status'] === 'paid' && $payroll->status !== 'paid') {
            $validated['paid_at'] = now();
        }

        $payroll->update($validated);

        return redirect()->route('payroll.index', ['period' => $payroll->period])
            ->with('success', 'Payroll berhasil diperbarui!');
    }

    public function destroy(Payroll $payroll)
    {
        $period = $payroll->period;
        Payroll::destroy($payroll->id);

        return redirect()->route('payroll.index', ['period' => $period])
            ->with('success', 'Payroll berhasil dihapus!');
    }

    public function approve(Payroll $payroll)
    {
        $payroll->update(['status' => 'approved']);
        return back()->with('success', 'Payroll disetujui!');
    }

    public function markPaid(Payroll $payroll)
    {
        $payroll->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Payroll dibayar!');
    }
}
