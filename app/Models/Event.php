<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'image', 'excerpt', 'description', 'starts_at', 'ends_at',
        'location', 'address', 'organizer', 'capacity', 'ticket_price', 'status', 'is_featured',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_featured' => 'boolean',
        'ticket_price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (Event $event) {
            if (blank($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('starts_at', '<', now());
    }

    public function getIsPastAttribute(): bool
    {
        return $this->starts_at->isPast();
    }

    public function getSeatsTakenAttribute(): int
    {
        return (int) $this->registrations()->where('status', '!=', 'cancelled')->sum('seats');
    }

    public function getSeatsLeftAttribute(): ?int
    {
        return $this->capacity ? max(0, $this->capacity - $this->seats_taken) : null;
    }
}
