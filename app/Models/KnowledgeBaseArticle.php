<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class KnowledgeBaseArticle extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'category',
        'tags',
        'author_id',
        'views',
        'helpful_count',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'getting-started' => 'blue',
            'billing' => 'emerald',
            'technical' => 'purple',
            'troubleshooting' => 'amber',
            'faq' => 'cyan',
            default => 'gray',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'getting-started' => 'Memulai',
            'billing' => 'Billing',
            'technical' => 'Teknis',
            'troubleshooting' => 'Troubleshooting',
            'faq' => 'FAQ',
            default => ucfirst($this->category),
        };
    }
}
