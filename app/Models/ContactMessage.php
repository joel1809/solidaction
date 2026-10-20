<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'status', 'reply', 'read_at',
    ];

    protected $casts = ['read_at' => 'datetime'];

    public const STATUSES = [
        'new' => 'Nouveau',
        'read' => 'Lu',
        'replied' => 'Répondu',
        'archived' => 'Archivé',
    ];

    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
