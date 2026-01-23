<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory;
    // Status constants for ISP workflow
    public const STATUS_REGISTERED = 'registered';
    public const STATUS_SURVEY = 'survey';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_INSTALLING = 'installing';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_TERMINATED = 'terminated';

    public const STATUSES = [
        self::STATUS_REGISTERED => 'Registrasi',
        self::STATUS_SURVEY => 'Survey',
        self::STATUS_APPROVED => 'Disetujui',
        self::STATUS_SCHEDULED => 'Terjadwal',
        self::STATUS_INSTALLING => 'Instalasi',
        self::STATUS_ACTIVE => 'Aktif',
        self::STATUS_SUSPENDED => 'Ditangguhkan',
        self::STATUS_TERMINATED => 'Berhenti',
    ];

    protected $fillable = [
        'customer_id',
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
        'package_id',
        'partner_id',
        'assigned_to',
        'team_size',
        'status',
        'installation_date',
        'billing_date',
        'notes',
        'payment_token',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'billing_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'team_size' => 'integer',
    ];

    /**
     * Boot method to auto-generate customer_id and log status changes.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_id)) {
                $customer->customer_id = 'CUST-' . strtoupper(uniqid());
            }
            // Auto-generate payment token for QR code
            if (empty($customer->payment_token)) {
                $customer->payment_token = bin2hex(random_bytes(16));
            }
        });

        // Log initial status on create
        static::created(function ($customer) {
            $customer->logStatusChange(null, $customer->status, 'Customer created');
        });

        // Log status changes on update
        static::updating(function ($customer) {
            if ($customer->isDirty('status')) {
                $customer->logStatusChange(
                    $customer->getOriginal('status'),
                    $customer->status
                );
            }
        });
    }

    /**
     * Log a status change.
     */
    public function logStatusChange(?string $previousStatus, string $newStatus, ?string $notes = null): void
    {
        $this->statusLogs()->create([
            'status' => $newStatus,
            'previous_status' => $previousStatus,
            'changed_by' => Auth::id(),
            'notes' => $notes,
            'changed_at' => now(),
        ]);
    }

    /**
     * Get the package that the customer subscribes to.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the partner who referred this customer.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Relationship: Customer has many Invoices.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }



    /**
     * Get the technician assigned to this customer.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get status change logs.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(CustomerStatusLog::class)->orderBy('changed_at', 'desc');
    }

    /**
     * Scope for active customers only.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'emerald',
            'registered', 'survey' => 'blue',
            'approved', 'scheduled' => 'cyan',
            'installing' => 'amber',
            'suspended' => 'red',
            'terminated' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get formatted address.
     */
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

    /**
     * Get QR Code SVG for this customer.
     */
    public function getQrCodeAttribute(): string
    {
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
            ->backgroundColor(255, 255, 255)
            ->color(30, 41, 59)
            ->generate($this->payment_token);
    }

    /**
     * Get payment on-time percentage (0-100).
     */
    public function getPaymentScoreAttribute(): ?float
    {
        $paidInvoices = $this->invoices()->paid()->get();

        if ($paidInvoices->isEmpty()) {
            return null;
        }

        $onTimeCount = $paidInvoices->filter(fn($inv) => $inv->is_on_time === true)->count();
        return round(($onTimeCount / $paidInvoices->count()) * 100, 1);
    }

    /**
     * Get payment behavior label based on score.
     */
    public function getPaymentLabelAttribute(): string
    {
        $score = $this->payment_score;

        if ($score === null) {
            return 'Belum Ada Data';
        }

        if ($score >= 90) {
            return 'Sangat Rajin';
        } elseif ($score >= 70) {
            return 'Rajin';
        } elseif ($score >= 50) {
            return 'Cukup';
        } else {
            return 'Sering Telat';
        }
    }

    /**
     * Get payment label color for UI.
     */
    public function getPaymentLabelColorAttribute(): string
    {
        $score = $this->payment_score;

        if ($score === null)
            return 'gray';
        if ($score >= 90)
            return 'emerald';
        if ($score >= 70)
            return 'blue';
        if ($score >= 50)
            return 'amber';
        return 'red';
    }

    /**
     * Get payment history statistics.
     */
    public function getPaymentStatsAttribute(): array
    {
        $invoices = $this->invoices()->paid()->get();

        return [
            'total_paid' => $invoices->count(),
            'on_time' => $invoices->filter(fn($i) => $i->is_on_time === true)->count(),
            'late' => $invoices->filter(fn($i) => $i->is_on_time === false)->count(),
            'avg_days_late' => $invoices->avg('days_late') ?? 0,
        ];
    }
}
