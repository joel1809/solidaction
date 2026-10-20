@extends('layouts.site')

@section('title', 'Merci pour votre don')

@section('content')

    <x-site.breadcrumb title="Merci !" :items="['Faire un don' => route('donations.create'), 'Confirmation']"
        image="theme/assets/images/breadcrumb/breadcrumb-4.jpg" />

    <section class="donate-thanks-area" style="padding: 110px 0 100px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2">
                    <x-site.alert />

                    <div class="empty-state" style="padding: 70px 40px;">
                        <div style="font-size: 54px; color: var(--thm-primary); margin-bottom: 20px;">
                            <span class="fa fa-check-circle"></span>
                        </div>
                        <h3 style="font-size: 32px;">Merci {{ Str::before($donation->donor_name, ' ') }} !</h3>
                        <p>
                            Votre {{ Str::lower($donation->frequency_label) }} de <strong>{{ money($donation->amount, true) }}</strong>
                            @if ($donation->cause)
                                en faveur de « {{ $donation->cause->title }} »
                            @endif
                            a bien été enregistré.
                        </p>

                        <table class="info-table" style="text-align: left; margin-top: 34px;">
                            <tbody>
                                <tr>
                                    <th>Référence</th>
                                    <td><strong>{{ $donation->reference }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Montant</th>
                                    <td>{{ money($donation->amount, true) }}</td>
                                </tr>
                                <tr>
                                    <th>Fréquence</th>
                                    <td>{{ $donation->frequency_label }}</td>
                                </tr>
                                <tr>
                                    <th>Moyen de paiement</th>
                                    <td>{{ $donation->method_label }}</td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>{{ $donation->status_label }}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if ($donation->payment_method === 'transfer')
                            <p style="text-align: left;">
                                Pour finaliser votre don par virement, utilisez l'IBAN
                                <strong>{{ setting('donation_iban') }}</strong> (BIC {{ setting('donation_bic') }}) en indiquant
                                la référence <strong>{{ $donation->reference }}</strong> en libellé.
                            </p>
                        @elseif ($donation->payment_method === 'mobile_money')
                            <p style="text-align: left;">
                                Pour finaliser votre don, envoyez le montant au <strong>{{ setting('donation_mobile_money') }}</strong>
                                en indiquant la référence <strong>{{ $donation->reference }}</strong> en motif.
                                Notre équipe le valide sous 48 heures ouvrées.
                            </p>
                        @else
                            <p style="text-align: left;">
                                Notre équipe valide votre don sous 48 heures ouvrées et vous adresse une confirmation
                                par e-mail à {{ $donation->donor_email }}.
                            </p>
                        @endif

                        <p style="text-align: left;">{{ setting('donation_tax_notice') }}</p>

                        <div style="margin-top: 30px;">
                            <a class="btn-one" href="{{ route('causes.index') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Découvrir nos autres causes</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
