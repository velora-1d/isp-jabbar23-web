<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = [
        'ticket_number',
        'customer_id',
        'type',
        'status',
        'priority',
        'scheduled_date',
        'completed_date',
        'technician_id',
        'odp_id',
        'description',
        'technician_notes',
        'photos'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_date' => 'datetime',
        'photos' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wo) {
            if (empty($wo->ticket_number)) {
                $wo->ticket_number = 'WO-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -4));
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function odp()
    {
        return $this->belongsTo(Odp::class);
    }

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'gray',
            'scheduled' => 'blue',
            'on_way' => 'indigo',
            'in_progress' => 'orange',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
