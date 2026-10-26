<x-mail::message>
# Merci {{ Str::before($donation->donor_name, ' ') }} !

Votre promesse de don de **{{ money($donation->amount, true) }}**@if ($donation->cause) en faveur de « {{ $donation->cause->title }} »@endif a bien été enregistrée.

<x-mail::table>
| | |
|:--|:--|
| Référence | **{{ $donation->reference }}** |
| Montant | {{ money($donation->amount, true) }} |
| Fréquence | {{ $donation->frequency_label }} |
| Moyen de paiement | {{ $donation->method_label }} |
| Date | {{ $donation->created_at->format('d/m/Y') }} |
</x-mail::table>

@if ($donation->status === 'pending')
**Pour finaliser votre don :** {{ $donation->payment_instructions }}

Dès réception de votre paiement, notre équipe valide votre don sous 48 heures ouvrées et vous envoie un mail de confirmation à {{ $donation->donor_email }}.
@endif

{{ setting('donation_tax_notice') }}

Avec toute notre reconnaissance,<br>
L'équipe {{ setting('site_name', config('app.name')) }}
</x-mail::message>
