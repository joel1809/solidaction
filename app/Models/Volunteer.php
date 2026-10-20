<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'birthdate', 'interest',
        'availability', 'message', 'status',
    ];

    protected $casts = ['birthdate' => 'date'];

    public const STATUSES = [
        'pending' => 'En attente',
        'approved' => 'Accepté',
        'rejected' => 'Refusé',
    ];

    public const INTERESTS = [
        'terrain' => 'Missions de terrain',
        'logistique' => 'Logistique et distribution',
        'education' => 'Éducation et soutien scolaire',
        'sante' => 'Santé et premiers secours',
        'communication' => 'Communication et collecte de fonds',
        'administration' => 'Administration et bureau',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getInterestLabelAttribute(): string
    {
        return self::INTERESTS[$this->interest] ?? (string) $this->interest;
    }
}
