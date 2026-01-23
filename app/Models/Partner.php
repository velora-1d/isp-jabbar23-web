<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'address',
        'commission_rate',
        'status',
        'notes',
        'balance', // Keep for compatibility
        'erp_supplier_id', // Keep for compatibility
    ];

    public function getStatusColorAttribute(): string
    {
        return $this->status === 'active' ? 'emerald' : 'gray';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'active' ? 'Aktif' : 'Non-Aktif';
    }

    /**
     * Get customers referred by this partner.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
