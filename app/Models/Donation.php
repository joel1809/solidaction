<?php

namespace App\Models;

use App\Mail\DonationConfirmed;
use App\Mail\DonationReceived;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

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
        'orange_money' => 'Orange Money',
        'mtn_momo' => 'MTN MoMo',
        'moov_money' => 'Moov Money',
        'wave' => 'Wave',
        'mobile_money' => 'Mobile Money',
        'paypal' => 'PayPal',
        'cash' => 'Espèces',
    ];

    /**
     * Opérateurs Mobile Money de Côte d'Ivoire et le réglage qui porte leur numéro de réception.
     */
    public const MOBILE_MONEY = [
        'orange_money' => 'donation_orange_money',
        'mtn_momo' => 'donation_mtn_momo',
        'moov_money' => 'donation_moov_money',
        'wave' => 'donation_wave',
    ];

    /**
     * Moyens proposés sur le formulaire public : ceux qu'un donateur peut régler
     * sans paiement en ligne. Carte, PayPal et « Mobile Money » générique restent
     * saisissables côté admin.
     */
    public const PUBLIC_METHODS = ['orange_money', 'mtn_momo', 'moov_money', 'wave', 'transfer', 'cash'];

    protected static function booted(): void
    {
        static::creating(function (Donation $donation) {
            if (blank($donation->reference)) {
                $donation->reference = 'DON-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
            }
        });

        // Envoie le mail de confirmation au donateur dès que le don passe à « Validé ».
        static::created(function (Donation $donation) {
            if ($donation->status === 'completed') {
                $donation->sendConfirmation();
            }
        });

        static::updated(function (Donation $donation) {
            if ($donation->wasChanged('status') && $donation->status === 'completed') {
                $donation->sendConfirmation();
            }
        });

        static::saved(fn (Donation $donation) => $donation->cause?->recalculateRaised());
        static::deleted(fn (Donation $donation) => $donation->cause?->recalculateRaised());
    }

    public function sendConfirmation(): void
    {
        $this->sendMail(new DonationConfirmed($this));
    }

    public function sendReceipt(): void
    {
        $this->sendMail(new DonationReceived($this));
    }

    private function sendMail(Mailable $mail): void
    {
        try {
            Mail::to($this->donor_email, $this->donor_name)->send($mail);
        } catch (Throwable $e) {
            // Un échec SMTP ne doit pas bloquer l'enregistrement ou la validation du don.
            report($e);
        }
    }

    /**
     * Comment régler la promesse de don, affiché sur la page Merci.
     */
    public function getPaymentInstructionsAttribute(): string
    {
        if (isset(self::MOBILE_MONEY[$this->payment_method])) {
            return 'Envoyez '.money($this->amount).' par '.$this->method_label.' au '
                .setting(self::MOBILE_MONEY[$this->payment_method])
                .' en indiquant la référence '.$this->reference.' en motif.';
        }

        return match ($this->payment_method) {
            'transfer' => 'Effectuez un virement de '.money($this->amount).' au profit de '.setting('donation_bank_holder')
                .' ('.setting('donation_bank_name').'), compte n° '.setting('donation_iban')
                .(filled(setting('donation_bic')) ? ' (BIC '.setting('donation_bic').')' : '')
                .', en indiquant la référence '.$this->reference.' en libellé.',
            'cash' => 'Remettez '.money($this->amount).' en espèces à notre bureau ('.setting('contact_address')
                .', '.lcfirst((string) setting('contact_hours')).') en rappelant la référence '.$this->reference.'.',
            default => 'Notre équipe vous contactera pour finaliser votre don (référence '.$this->reference.').',
        };
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
