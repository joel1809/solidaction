<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'role', 'photo', 'bio', 'email', 'phone',
        'facebook', 'twitter', 'instagram', 'linkedin', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::saving(function (TeamMember $member) {
            if (blank($member->slug)) {
                $member->slug = Str::slug($member->name).'-'.Str::lower(Str::random(4));
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function socialLinks(): array
    {
        return array_filter([
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'linkedin' => $this->linkedin,
        ]);
    }
}
