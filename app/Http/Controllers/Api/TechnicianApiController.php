<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TechnicianApiController extends Controller
{
    /**
     * Technician Dashboard Stats.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Ensure user is technician
        if (!$user->hasRole('technician')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $totalJobs = Ticket::where('technician_id', $user->id)->count();
        $pendingJobs = Ticket::where('technician_id', $user->id)->whereIn('status', ['open', 'in_progress'])->count();
        $completedJobs = Ticket::where('technician_id', $user->id)->where('status', 'resolved')->count();

        // Get 5 recent jobs
        $recentJobs = Ticket::where('technician_id', $user->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->with(['customer:id,name,address'])
            ->latest()
            ->take(5)
            ->get();

        // Get Active Job (In Progress)
        $activeJob = Ticket::where('technician_id', $user->id)
            ->where('status', 'in_progress')
            ->with(['customer:id,name,address'])
            ->first();

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'position' => $user->position,
                'department' => $user->department,
                'role' => $user->roles->first()?->name ?? 'technician',
            ],
            'stats' => [
                'total' => $totalJobs,
                'pending' => $pendingJobs,
                'completed' => $completedJobs
            ],
            'active_job' => $activeJob,
            'recent_jobs' => $recentJobs
        ]);
    }

    /**
     * List all assigned jobs.
     */
    public function jobs(Request $request)
    {
        $user = $request->user();

        $jobs = Ticket::where('technician_id', $user->id)
            ->with(['customer:id,name,address,phone'])
            ->latest()
            ->paginate(10);

        return response()->json($jobs);
    }

    /**
     * Update job status (e.g. Start working, Finish).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'notes' => 'nullable|string',
            'optical_power' => 'nullable|string',
            'evidence_photo' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $user = $request->user();
        $ticket = Ticket::where('id', $id)->where('technician_id', $user->id)->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found or not assigned to you'], 404);
        }

        $ticket->status = $request->status;

        if ($request->has('optical_power')) {
            $ticket->optical_power = $request->optical_power;
        }

        if ($request->hasFile('evidence_photo')) {
            $path = $request->file('evidence_photo')->store('public/evidence');
            // Store relative path for serving
            $ticket->evidence_photo = str_replace('public/', 'storage/', $path);
        }

        if ($request->status == 'resolved') {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        return response()->json([
            'message' => 'Status pekerjaan berhasil diperbarui',
            'ticket' => $ticket
        ]);
    }
    /**
     * Get technician inventory (Real DB).
     */
    public function inventory(Request $request)
    {
        $items = \App\Models\InventoryItem::with('stocks')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'stock' => (int) $item->stocks->sum('quantity'), // Sum all locations for now
                'unit' => $item->unit,
                // 'image' => ...
            ];
        });

        return response()->json($items);
    }

    /**
     * Get today's attendance status.
     */
    public function attendance(Request $request)
    {
        $user = $request->user();
        $date = now()->format('Y-m-d');

        $attendance = \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', $date)
            ->first();

        return response()->json([
            'status' => $attendance ? ($attendance->clock_out ? 'done' : 'present') : 'absent',
            'clock_in_time' => $attendance?->clock_in?->format('H:i'),
            'clock_out_time' => $attendance?->clock_out?->format('H:i'),
            'date' => $date
        ]);
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:5120', // Max 5MB
        ]);

        $user = $request->user();
        $date = now()->format('Y-m-d');

        // Check if already clocked in
        $existing = \App\Models\Attendance::where('user_id', $user->id)->where('date', $date)->first();
        if ($existing) {
            return response()->json(['message' => 'Anda sudah absen masuk hari ini'], 400);
        }

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/attendance');
            $path = str_replace('public/', 'storage/', $path);
        }

        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'date' => $date,
            'clock_in' => now(),
            'status' => 'present',
            'photo_in' => $path
        ]);

        return response()->json([
            'message' => 'Berhasil Absen Masuk',
            'time' => now()->format('H:i')
        ]);
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:5120',
        ]);

        $user = $request->user();
        $date = now()->format('Y-m-d');

        $attendance = \App\Models\Attendance::where('user_id', $user->id)->where('date', $date)->first();

        if (!$attendance) {
            return response()->json(['message' => 'Anda belum absen masuk'], 400);
        }

        if ($attendance->clock_out) {
            return response()->json(['message' => 'Anda sudah absen pulang hari ini'], 400);
        }

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/attendance');
            $path = str_replace('public/', 'storage/', $path);
        }

        $attendance->update([
            'clock_out' => now(),
            'photo_out' => $path
        ]);

        return response()->json([
            'message' => 'Berhasil Absen Pulang',
            'time' => now()->format('H:i')
        ]);
    }
}
