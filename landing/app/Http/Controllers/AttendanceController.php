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
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $month = $request->get('month', Carbon::today()->format('Y-m'));

        $query = Attendance::with('user');

        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('date', $date);
        } else {
            $query->whereDate('date', $date);
        }

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply employee filter
        $this->applyRelationFilter($query, $request, 'user_id');

        $attendances = $query->orderBy('clock_in')->paginate(20)->withQueryString();

        $stats = [
            'total_employees' => User::where('is_active', true)->count(),
            'present' => Attendance::whereDate('date', $date)->where('status', 'present')->count(),
            'late' => Attendance::whereDate('date', $date)->where('status', 'late')->count(),
            'absent' => Attendance::whereDate('date', $date)->where('status', 'absent')->count(),
        ];

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

        return view('hrd.attendance.index', compact('attendances', 'stats', 'date', 'month', 'employees', 'statuses'));
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
