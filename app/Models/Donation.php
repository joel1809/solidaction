<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference', 'cause_id', 'donor_name', 'donor_email', 'donor_phone',
        'donor_address', 'amount', 'frequency', 'fund_type', 'payment_method',
        'status', 'is_anonymous', 'message', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public const FREQUENCIES = [
        'one_time' => 'Don unique',
        'monthly' => 'Mensuel',
        'yearly' => 'Annuel',
    ];

    public const STATUSES = [
        'pending' => 'En attente',
        'completed' => 'Validé',
        'failed' => 'Échoué',
        'refunded' => 'Remboursé',
    ];

    public const METHODS = [
        'transfer' => 'Virement bancaire',
        'card' => 'Carte bancaire',
        'mobile_money' => 'Mobile Money',
        'paypal' => 'PayPal',
        'cash' => 'Espèces',
    ];

    protected static function booted(): void
    {
        static::creating(function (Donation $donation) {
            if (blank($donation->reference)) {
                $donation->reference = 'DON-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
            }
        });

        static::saved(fn (Donation $donation) => $donation->cause?->recalculateRaised());
        static::deleted(fn (Donation $donation) => $donation->cause?->recalculateRaised());
    }

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getFrequencyLabelAttribute(): string
    {
        return self::FREQUENCIES[$this->frequency] ?? $this->frequency;
    }

    public function getMethodLabelAttribute(): string
    {
        return self::METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Donateur anonyme' : $this->donor_name;
    }
}
