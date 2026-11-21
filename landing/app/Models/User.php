<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    // Status yang dianggap "sedang tugas" - teknisi sedang handle customer
    public const ACTIVE_CUSTOMER_STATUSES = [
        'survey',
        'approved', 
        'scheduled',
        'installing',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
        'is_active',
        'last_latitude',
        'last_longitude',
        'last_location_update',
        // Employee fields
        'nik',
        'address',
        'date_of_birth',
        'gender',
        'position',
        'department',
        'join_date',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    /**
     * Get photo URL or default avatar.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return '';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_latitude' => 'float',
            'last_longitude' => 'float',
            'last_location_update' => 'datetime',
        ];
    }

    /**
     * Get all customers assigned to this technician.
     */
    public function assignedCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'assigned_to');
    }

    /**
     * Get customers currently being worked on (not completed).
     * These are customers in: survey, approved, scheduled, installing
     */
    public function currentTasks(): HasMany
    {
        return $this->hasMany(Customer::class, 'assigned_to')
            ->whereIn('status', self::ACTIVE_CUSTOMER_STATUSES);
    }

    /**
     * Get count of completed installations (active customers).
     */
    public function completedCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'assigned_to')
            ->where('status', 'active');
    }

    /**
     * Scope for technicians only.
     */
    /**
     * Scope for technicians only.
     */
    public function scopeTechnicians($query)
    {
        return $query->role('technician');
    }

    /**
     * Get technician status - CALCULATED AUTOMATICALLY from customer data.
     * - on_task: Ada customer aktif dengan status survey/approved/scheduled/installing
     * - available: Tidak ada customer yang sedang ditangani
     * - off_duty: is_active = false
     */
    public function getComputedStatusAttribute(): string
    {
        // Check if inactive/off duty
        if ($this->is_active === false) {
            return 'off_duty';
        }

        // Check if has any active tasks
        $activeTasks = $this->currentTasks()->count();
        
        if ($activeTasks > 0) {
            return 'on_task';
        }

        return 'available';
    }

    /**
     * Get technician status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->computed_status) {
            'available' => 'Tersedia',
            'on_task' => 'Sedang Tugas',
            'off_duty' => 'Tidak Aktif',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get technician status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->computed_status) {
            'available' => 'emerald',
            'on_task' => 'amber',
            'off_duty' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get current customer being handled (first active task).
     */
    public function getCurrentCustomerAttribute(): ?Customer
    {
        return $this->currentTasks()->with('package')->first();
    }

    /**
     * Check if user is a field technician.
     */
    public function isTechnician(): bool
    {
        return $this->hasRole('technician');
    }
}
