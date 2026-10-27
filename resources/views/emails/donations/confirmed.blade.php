<x-mail::message>
# Merci {{ Str::before($donation->donor_name, ' ') }} !

Nous avons bien reçu votre don de **{{ money($donation->amount, true) }}**@if ($donation->cause) en faveur de « {{ $donation->cause->title }} »@endif. Votre générosité nous aide concrètement à poursuivre nos actions.

<x-mail::table>
| | |
|:--|:--|
| Référence | **{{ $donation->reference }}** |
| Montant | {{ money($donation->amount, true) }} |
| Moyen de paiement | {{ $donation->method_label }} |
| Date de validation | {{ ($donation->paid_at ?? now())->format('d/m/Y') }} |
</x-mail::table>

@if (setting('donation_tax_notice'))
{{ setting('donation_tax_notice') }}
@endif

<x-mail::button :url="route('home')">
Découvrir nos actions
</x-mail::button>

Avec toute notre reconnaissance,<br>
L'équipe {{ setting('site_name', config('app.name')) }}
</x-mail::message>
