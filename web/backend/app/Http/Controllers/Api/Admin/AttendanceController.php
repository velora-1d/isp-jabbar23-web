<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\HRD\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $attendances = Attendance::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->date, fn($q) => $q->whereDate('date', $request->date))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest('date')
            ->paginate($request->integer('per_page', 15));

        return response()->json($attendances);
    }

    public function today(): JsonResponse
    {
        $attendance = Attendance::where('user_id', auth()->user()->id)
            ->where('date', now()->toDateString())
            ->first();

        return response()->json($attendance);
    }

    public function clockIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            $attendance = $this->attendanceService->clockIn(
                auth()->user()->id,
                $validated,
                $request->file('photo')
            );
            return response()->json($attendance);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function clockOut(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            $attendance = $this->attendanceService->clockOut(
                auth()->user()->id,
                $validated,
                $request->file('photo')
            );
            return response()->json($attendance);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
