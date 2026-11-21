<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use App\Traits\HasFilters;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin|hrd');
    }

    public function index(Request $request)
    {
        $query = Leave::with(['user', 'approver']);

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'start_date',
            'searchColumns' => ['user.name', 'reason']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Apply employee filter
        $this->applyRelationFilter($query, $request, 'user_id');

        $leaves = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Leave::count(),
            'pending' => Leave::where('status', 'pending')->count(),
            'approved' => Leave::where('status', 'approved')->count(),
            'rejected' => Leave::where('status', 'rejected')->count(),
        ];

        // Filter options
        $employees = User::where('is_active', true)->orderBy('name')->get();
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
        ];
        $types = [
            'annual' => 'Annual',
            'sick' => 'Sick',
            'personal' => 'Personal',
            'maternity' => 'Maternity',
            'paternity' => 'Paternity',
            'unpaid' => 'Unpaid',
            'other' => 'Other',
        ];

        return view('hrd.leave.index', compact('leaves', 'stats', 'employees', 'statuses', 'types'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        return view('hrd.leave.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:annual,sick,personal,maternity,paternity,unpaid,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        // Calculate days
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $validated['days'] = $startDate->diffInDays($endDate) + 1;
        $validated['status'] = 'pending';

        Leave::create($validated);

        return redirect()->route('leave.index')
            ->with('success', 'Pengajuan cuti berhasil dibuat!');
    }

    public function edit(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan pending yang dapat diedit.');
        }

        $users = User::where('is_active', true)->orderBy('name')->get();
        return view('hrd.leave.edit', compact('leave', 'users'));
    }

    public function update(Request $request, Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan pending yang dapat diedit.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:annual,sick,personal,maternity,paternity,unpaid,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $validated['days'] = $startDate->diffInDays($endDate) + 1;

        $leave->update($validated);

        return redirect()->route('leave.index')
            ->with('success', 'Pengajuan cuti berhasil diperbarui!');
    }

    public function destroy(Leave $leave)
    {
        if (!in_array($leave->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Hanya pengajuan pending/cancelled yang dapat dihapus.');
        }

        Leave::destroy($leave->id);
        return redirect()->route('leave.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus!');
    }

    public function approve(Leave $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Cuti disetujui!');
    }

    public function reject(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Cuti ditolak.');
    }
}
