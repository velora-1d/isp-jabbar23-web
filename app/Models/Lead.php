<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_QUALIFIED = 'qualified';
    public const STATUS_PROPOSAL = 'proposal';
    public const STATUS_NEGOTIATION = 'negotiation';
    public const STATUS_WON = 'won';
    public const STATUS_LOST = 'lost';

    public const STATUSES = [
        self::STATUS_NEW => 'Baru',
        self::STATUS_CONTACTED => 'Dihubungi',
        self::STATUS_QUALIFIED => 'Qualified',
        self::STATUS_PROPOSAL => 'Proposal',
        self::STATUS_NEGOTIATION => 'Negosiasi',
        self::STATUS_WON => 'Berhasil',
        self::STATUS_LOST => 'Gagal',
    ];

    public const SOURCES = [
        'website' => 'Website',
        'whatsapp' => 'WhatsApp',
        'referral' => 'Referral',
        'walk-in' => 'Walk-in',
        'social_media' => 'Social Media',
        'other' => 'Lainnya',
    ];

    protected $fillable = [
        'lead_number',
        'name',
        'phone',
        'email',
        'address',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'latitude',
        'longitude',
        'source',
        'interested_package_id',
        'assigned_to',
        'status',
        'notes',
        'converted_at',
        'customer_id',
    ];

    protected $casts = [
        'converted_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lead) {
            if (empty($lead->lead_number)) {
                $lead->lead_number = 'LEAD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
            }
        });
    }

    public function interestedPackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'interested_package_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getSourceLabelAttribute(): string
    {
        return self::SOURCES[$this->source] ?? ucfirst($this->source);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'blue',
            'contacted' => 'cyan',
            'qualified' => 'emerald',
            'proposal' => 'amber',
            'negotiation' => 'orange',
            'won' => 'green',
            'lost' => 'red',
            default => 'gray',
        };
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->rt_rw ? "RT/RW {$this->rt_rw}" : null,
            $this->kelurahan,
            $this->kecamatan,
            $this->kabupaten,
            $this->provinsi,
            $this->kode_pos,
        ]);
        return implode(', ', $parts);
    }

    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_WON, self::STATUS_LOST]);
    }
}
