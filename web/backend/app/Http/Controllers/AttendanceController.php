<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Traits\HasFilters;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin|hrd');
    }

    public function index(Request $request)
    {
        $mode = $request->get('mode', 'daily'); // daily or range
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $dateFrom = $request->get('date_from', Carbon::today()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::today()->format('Y-m-d'));
        $month = $request->get('month', Carbon::today()->format('Y-m'));

        $query = Attendance::with('user');

        // Apply date filter based on mode
        if ($mode === 'range' && $request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$dateFrom, $dateTo]);
            $statsDate = null; // Don't show daily stats in range mode
        } else {
            $query->whereDate('date', $date);
            $statsDate = $date;
        }

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply employee filter
        $this->applyRelationFilter($query, $request, 'user_id');

        $attendances = $query->orderBy('date', 'desc')->orderBy('clock_in')->paginate(20)->withQueryString();

        // Stats - only for daily mode
        if ($statsDate) {
            $stats = [
                'total_employees' => User::where('is_active', true)->count(),
                'present' => Attendance::whereDate('date', $statsDate)->where('status', 'present')->count(),
                'late' => Attendance::whereDate('date', $statsDate)->where('status', 'late')->count(),
                'absent' => Attendance::whereDate('date', $statsDate)->where('status', 'absent')->count(),
            ];
        } else {
            // Range mode stats
            $stats = [
                'total_employees' => User::where('is_active', true)->count(),
                'present' => Attendance::whereBetween('date', [$dateFrom, $dateTo])->where('status', 'present')->count(),
                'late' => Attendance::whereBetween('date', [$dateFrom, $dateTo])->where('status', 'late')->count(),
                'absent' => Attendance::whereBetween('date', [$dateFrom, $dateTo])->where('status', 'absent')->count(),
            ];
        }

        // Get employees for filter
        $employees = User::where('is_active', true)->orderBy('name')->get();

        // Filter options
        $statuses = [
            'present' => 'Present',
            'late' => 'Late',
            'absent' => 'Absent',
            'sick' => 'Sick',
            'leave' => 'Leave',
            'holiday' => 'Holiday',
        ];

        return view('hrd.attendance.index', compact('attendances', 'stats', 'date', 'dateFrom', 'dateTo', 'month', 'employees', 'statuses', 'mode'));
    }

    public function history(Request $request)
    {
        $month = $request->get('month', Carbon::today()->format('Y-m'));
        $userId = $request->get('user_id');

        $query = Attendance::with('user')
            ->whereYear('date', Carbon::parse($month)->year)
            ->whereMonth('date', Carbon::parse($month)->month);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        $attendances = $query->orderBy('date', 'desc')->orderBy('user_id')->paginate(50)->withQueryString();

        // Monthly stats
        $stats = [
            'total_days' => Carbon::parse($month)->daysInMonth,
            'total_records' => Attendance::whereYear('date', Carbon::parse($month)->year)
                ->whereMonth('date', Carbon::parse($month)->month)->count(),
            'present' => Attendance::whereYear('date', Carbon::parse($month)->year)
                ->whereMonth('date', Carbon::parse($month)->month)->where('status', 'present')->count(),
            'late' => Attendance::whereYear('date', Carbon::parse($month)->year)
                ->whereMonth('date', Carbon::parse($month)->month)->where('status', 'late')->count(),
            'absent' => Attendance::whereYear('date', Carbon::parse($month)->year)
                ->whereMonth('date', Carbon::parse($month)->month)->where('status', 'absent')->count(),
        ];

        $employees = User::where('is_active', true)->orderBy('name')->get();

        $statuses = [
            'present' => 'Present',
            'late' => 'Late',
            'absent' => 'Absent',
            'sick' => 'Sick',
            'leave' => 'Leave',
            'holiday' => 'Holiday',
        ];

        return view('hrd.attendance.history', compact('attendances', 'stats', 'month', 'employees', 'statuses', 'userId'));
    }

    public function create()
    {
        $users = User::where(['is_active' => true])->orderBy('name')->get();
        return view('hrd.attendance.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,late,absent,sick,leave,holiday',
            'notes' => 'nullable|string',
        ]);

        Attendance::updateOrCreate(
            ['user_id' => $validated['user_id'], 'date' => $validated['date']],
            $validated
        );

        return redirect()->route('attendance.index', ['date' => $validated['date']])
            ->with('success', 'Attendance berhasil disimpan!');
    }

    public function edit(Attendance $attendance)
    {
        $users = User::where(['is_active' => true])->orderBy('name')->get();
        return view('hrd.attendance.edit', compact('attendance', 'users'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,late,absent,sick,leave,holiday',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendance.index', ['date' => $validated['date']])
            ->with('success', 'Attendance berhasil diperbarui!');
    }

    public function destroy(Attendance $attendance)
    {
        $date = $attendance->date->format('Y-m-d');
        Attendance::destroy($attendance->id);

        return redirect()->route('attendance.index', ['date' => $date])
            ->with('success', 'Attendance berhasil dihapus!');
    }

    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => Carbon::now()->hour >= 9 ? 'late' : 'present']
        );

        if (!$attendance->clock_in) {
            $attendance->update([
                'clock_in' => Carbon::now()->format('H:i'),
                'clock_in_location' => $request->get('location'),
            ]);
        }

        return back()->with('success', 'Clock In berhasil!');
    }

    public function clockOut(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        $attendance = Attendance::where(['user_id' => $user->id])
            ->whereDate('date', $today)
            ->first();

        if ($attendance && !$attendance->clock_out) {
            $attendance->update([
                'clock_out' => Carbon::now()->format('H:i'),
                'clock_out_location' => $request->get('location'),
            ]);
        }

        return back()->with('success', 'Clock Out berhasil!');
    }
}
