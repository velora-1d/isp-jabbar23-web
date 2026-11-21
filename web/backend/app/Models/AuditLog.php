<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'create' => 'emerald',
            'update' => 'blue',
            'delete' => 'red',
            'login' => 'green',
            'logout' => 'gray',
            'view' => 'purple',
            'export' => 'amber',
            'import' => 'cyan',
            default => 'slate',
        };
    }

    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'create' => 'plus-circle',
            'update' => 'pencil',
            'delete' => 'trash',
            'login' => 'login',
            'logout' => 'logout',
            'view' => 'eye',
            'export' => 'download',
            'import' => 'upload',
            default => 'document',
        };
    }

    public function getModelNameAttribute(): string
    {
        if (!$this->model_type) {
            return '-';
        }
        
        $parts = explode('\\', $this->model_type);
        return end($parts);
    }

    /**
     * Log an action
     */
    public static function log(string $action, string $description, $model = null, array $oldValues = [], array $newValues = []): self
    {
        $user = auth()->user();

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'old_values' => !empty($oldValues) ? $oldValues : null,
            'new_values' => !empty($newValues) ? $newValues : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }
}
