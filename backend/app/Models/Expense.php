<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'amount',
        'description',
        'receipt_path',
        'date',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * categories of expenses.
     */
    public const CATEGORIES = [
        'fuel' => 'Bensin / BBM',
        'equipment' => 'Alat & Material',
        'salary' => 'Gaji Karyawan',
        'office' => 'Sewa Kantor',
        'utility' => 'Listrik & Air',
        'tax' => 'Pajak / PPN',
        'other' => 'Lain-lain',
    ];

    /**
     * Relationship with User.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
