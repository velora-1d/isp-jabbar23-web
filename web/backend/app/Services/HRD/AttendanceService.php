<?php

namespace App\Services\HRD;

use App\Models\Attendance;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AttendanceService
{
    /**
     * Clock in for the current user.
     */
    public function clockIn(int $userId, array $data, ?UploadedFile $photo = null): Attendance
    {
        $date = Carbon::today()->toDateString();
        
        $attendance = Attendance::firstOrNew([
            'user_id' => $userId,
            'date' => $date,
        ]);

        if ($attendance->clock_in) {
            throw new \Exception('Sudah melakukan absen masuk hari ini.');
        }

        $attendance->clock_in = now()->toTimeString();
        $attendance->clock_in_location = $data['location'] ?? null;
        
        if ($photo) {
            $attendance->photo_in = $photo->store('attendances/in', 'public');
        }

        // Determine status (e.g. late if after 08:30)
        $limit = Carbon::today()->hour(8)->minute(30);
        if (now()->gt($limit)) {
            $attendance->status = 'late';
        } else {
            $attendance->status = 'present';
        }

        $attendance->save();
        return $attendance;
    }

    /**
     * Clock out for the current user.
     */
    public function clockOut(int $userId, array $data, ?UploadedFile $photo = null): Attendance
    {
        $date = Carbon::today()->toDateString();
        
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $date)
            ->first();

        if (!$attendance || !$attendance->clock_in) {
            throw new \Exception('Harus absen masuk terlebih dahulu.');
        }

        if ($attendance->clock_out) {
            throw new \Exception('Sudah melakukan absen keluar hari ini.');
        }

        $attendance->clock_out = now()->toTimeString();
        $attendance->clock_out_location = $data['location'] ?? null;
        
        if ($photo) {
            $attendance->photo_out = $photo->store('attendances/out', 'public');
        }

        $attendance->save();
        return $attendance;
    }
}
