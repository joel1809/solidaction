<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtitle', 'title', 'text', 'image', 'focus_x', 'focus_y', 'button_text', 'button_url',
        'button2_text', 'button2_url', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'focus_x' => 'integer', 'focus_y' => 'integer'];

    /** Cadrage de l'image de fond : centré sur le point focal choisi, sinon celui du thème. */
    public function focusPosition(): ?string
    {
        if ($this->focus_x === null || $this->focus_y === null) {
            return null;
        }

        return "{$this->focus_x}% {$this->focus_y}%";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
