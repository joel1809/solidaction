<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cause extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'slug', 'image', 'excerpt', 'description',
        'goal_amount', 'raised_amount', 'deadline', 'location', 'status',
        'is_featured', 'views', 'sort_order', 'video_url',
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_featured' => 'boolean',
        'goal_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (Cause $cause) {
            if (blank($cause->slug)) {
                $cause->slug = Str::slug($cause->title);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getProgressAttribute(): int
    {
        if ((float) $this->goal_amount <= 0) {
            return 0;
        }

        return (int) min(100, round(((float) $this->raised_amount / (float) $this->goal_amount) * 100));
    }

    public function getRemainingAttribute(): float
    {
        return max(0, (float) $this->goal_amount - (float) $this->raised_amount);
    }

    public function getDaysLeftAttribute(): ?int
    {
        if (! $this->deadline) {
            return null;
        }

        return max(0, now()->startOfDay()->diffInDays($this->deadline, false));
    }

    public function recalculateRaised(): void
    {
        $this->raised_amount = $this->donations()->where('status', 'completed')->sum('amount');
        $this->saveQuietly();
    }
}
