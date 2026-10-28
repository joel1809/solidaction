<?php

namespace App\Models;

use App\Support\ResponsiveImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtitle', 'title', 'text', 'image', 'focus_x', 'focus_y', 'button_text', 'button_url',
        'button2_text', 'button2_url', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'focus_x' => 'integer',
        'focus_y' => 'integer',
        'image_variants' => 'array',
    ];

    /** Recrée les déclinaisons WebP de l'image téléversée (aucune pour les visuels du thème). */
    public function refreshImageVariants(): void
    {
        ResponsiveImage::delete($this->image_variants);

        $this->image_variants = $this->image && str_starts_with($this->image, 'uploads/')
            ? ResponsiveImage::generate($this->image)
            : null;

        $this->save();
    }

    public function imageSrcset(): ?string
    {
        return $this->image_variants ? ResponsiveImage::srcset($this->image_variants) : null;
    }

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
