<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre promesse de don '.$this->donation->reference.' a bien été enregistrée',
            replyTo: array_filter([setting('contact_email_secondary') ?: setting('contact_email')]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.donations.received',
            with: ['donation' => $this->donation->loadMissing('cause')],
        );
    }
}
