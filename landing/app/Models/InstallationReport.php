<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallationReport extends Model
{
    protected $fillable = [
        'work_order_id',
        'technician_id',
        'customer_id',
        'installation_date',
        'start_time',
        'end_time',
        'status',
        'work_performed',
        'equipment_used',
        'issues_found',
        'resolution',
        'customer_signature',
        'customer_rating',
        'customer_feedback',
        'photos',
        'notes',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'equipment_used' => 'array',
        'photos' => 'array',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'emerald',
            'partial' => 'amber',
            'failed' => 'red',
            'rescheduled' => 'blue',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'Selesai',
            'partial' => 'Sebagian',
            'failed' => 'Gagal',
            'rescheduled' => 'Dijadwalkan Ulang',
            default => $this->status,
        };
    }

    public function getDurationAttribute(): ?string
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }
        
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        $diff = $start->diff($end);
        
        return $diff->format('%H:%I');
    }
}
