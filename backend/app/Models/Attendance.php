<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'clock_in_location',
        'clock_out_location',
        'photo_in',
        'photo_out',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'present' => 'emerald',
            'late' => 'amber',
            'absent' => 'red',
            'sick' => 'orange',
            'leave' => 'blue',
            'holiday' => 'violet',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'present' => 'Hadir',
            'late' => 'Terlambat',
            'absent' => 'Absen',
            'sick' => 'Sakit',
            'leave' => 'Cuti',
            'holiday' => 'Libur',
            default => $this->status,
        };
    }

    public function getWorkingHoursAttribute(): ?string
    {
        if ($this->clock_in && $this->clock_out) {
            $diff = \Carbon\Carbon::parse($this->clock_in)->diff(\Carbon\Carbon::parse($this->clock_out));
            return $diff->format('%H:%I');
        }
        return null;
    }
}
