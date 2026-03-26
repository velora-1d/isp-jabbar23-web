<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Leave::with(['user:id,name', 'approver:id,name']);

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $leaves = $query->latest()->paginate($request->get('limit', 15));

        $stats = [
            'total' => Leave::count(),
            'pending' => Leave::where('status', 'pending')->count(),
            'approved' => Leave::where('status', 'approved')->count(),
            'rejected' => Leave::where('status', 'rejected')->count(),
        ];

        return response()->json([
            'leaves' => $leaves,
            'stats' => $stats
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:annual,sick,personal,maternity,paternity,unpaid,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $data = $validated;
        $data['days'] = $startDate->diffInDays($endDate) + 1;
        $data['status'] = 'pending';

        $leave = Leave::create($data);

        return response()->json([
            'message' => 'Pengajuan cuti berhasil dibuat',
            'leave' => $leave
        ], 201);
    }

    public function show(Leave $leave): JsonResponse
    {
        $leave->load(['user', 'approver']);
        return response()->json($leave);
    }

    public function approve(Leave $leave): JsonResponse
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return response()->json(['message' => 'Cuti disetujui']);
    }

    public function reject(Request $request, Leave $leave): JsonResponse
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

        return response()->json(['message' => 'Cuti ditolak']);
    }

    public function destroy(Leave $leave): JsonResponse
    {
        if (!in_array($leave->status, ['pending', 'cancelled'])) {
            return response()->json(['message' => 'Hanya pengajuan pending/cancelled yang dapat dihapus.'], 422);
        }

        $leave->delete();
        return response()->json(['message' => 'Pengajuan cuti berhasil dihapus']);
    }
}
