@extends('layouts.site')

@section('title', 'Merci pour votre don')
@section('robots', 'noindex, nofollow')

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
                            Votre promesse de don de <strong>{{ money($donation->amount, true) }}</strong>
                            @if ($donation->cause)
                                en faveur de « {{ $donation->cause->title }} »
                            @endif
                            a bien été enregistrée.
                        </p>

                        @if ($donation->status === 'pending')
                            <p style="text-align: left;">
                                <strong>Pour finaliser votre don :</strong> {{ $donation->payment_instructions }}
                            </p>
                            <p style="text-align: left;">
                                Dès réception de votre paiement, notre équipe valide votre don sous 48 heures ouvrées
                                et vous envoie un mail de confirmation à {{ $donation->donor_email }}.
                            </p>
                        @endif

                        <p style="text-align: left;">{{ setting('donation_tax_notice') }}</p>

                        <table class="info-table" style="text-align: left; margin-top: 34px;">
                            <tbody>
                                <tr>
                                    <th>Référence</th>
                                    <td><strong>{{ $donation->reference }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $donation->created_at->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Montant</th>
                                    <td>{{ money($donation->amount, true) }}</td>
                                </tr>
                                <tr>
                                    <th>Affectation</th>
                                    <td>{{ $donation->cause?->title ?? 'Toutes nos actions (fonds libre)' }}</td>
                                </tr>
                                @if (filled($donation->fund_type))
                                    <tr>
                                        <th>Intitulé</th>
                                        <td>{{ $donation->fund_type }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Moyen de paiement</th>
                                    <td>{{ $donation->method_label }}</td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>{{ $donation->status_label }}</td>
                                </tr>
                                <tr>
                                    <th>Donateur</th>
                                    <td>
                                        {{ $donation->donor_name }}
                                        @if ($donation->is_anonymous)
                                            (anonyme sur le site)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>E-mail</th>
                                    <td>{{ $donation->donor_email }}</td>
                                </tr>
                                @if (filled($donation->donor_phone))
                                    <tr>
                                        <th>Téléphone</th>
                                        <td>{{ $donation->donor_phone }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>


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
