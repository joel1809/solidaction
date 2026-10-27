@extends('layouts.site')

@section('title', 'Faire un don')
@section('meta_description', 'Soutenez les programmes de Solid’Action par un don, par Mobile Money, carte ou virement.')

@section('content')

    <x-site.breadcrumb title="Faire un don" :items="['Faire un don']" image="theme/assets/images/breadcrumb/breadcrumb-4.jpg" />

    <section class="donate-page-area" style="padding: 110px 0 90px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <x-site.sec-title subtitle="Votre générosité en action" title="Choisissez votre contribution" :center="false">
                        Un don affecté au projet de votre choix. Vous recevez un reçu de don et le bilan
                        du projet que vous avez soutenu.
                    </x-site.sec-title>

                    <x-site.alert />

                    <form action="{{ route('donations.store') }}" method="post" class="app-form" id="donation-form">
                        @csrf

                        {{-- Montant --}}
                        <div class="form-row">
                            <label for="amount">Montant du don <span class="required">*</span></label>
                            <div class="amount-presets">
                                @foreach (explode(',', setting('donation_amounts', '2000,5000,10000,25000,50000')) as $preset)
                                    <button type="button" data-amount="{{ trim($preset) }}">{{ money(trim($preset)) }}</button>
                                @endforeach
                            </div>
                            <input type="number" name="amount" id="amount" min="100" step="1"
                                value="{{ old('amount', $presetAmount ?? request('montant')) }}" placeholder="Autre montant" required>
                            @error('amount') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-row">
                            <label for="cause_id">Affecter mon don à</label>
                            <select name="cause_id" id="cause_id">
                                <option value="">Toutes nos actions (fonds libre)</option>
                                @foreach ($causes as $cause)
                                    <option value="{{ $cause->id }}" @selected((int) old('cause_id', $selectedCause ?? request('cause')) === $cause->id)>
                                        {{ $cause->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cause_id') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Coordonnées --}}
                        <h3 style="margin: 36px 0 22px; font-size: 24px;">Vos coordonnées</h3>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="donor_name">Nom et prénom <span class="required">*</span></label>
                                    <input type="text" name="donor_name" id="donor_name" value="{{ old('donor_name') }}" required>
                                    @error('donor_name') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="donor_email">Adresse e-mail <span class="required">*</span></label>
                                    <input type="email" name="donor_email" id="donor_email" value="{{ old('donor_email') }}" required>
                                    @error('donor_email') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-row">
                                    {{-- Libellé et caractère obligatoire ajustés en JS selon le moyen de paiement --}}
                                    <label for="donor_phone">
                                        <span data-phone-label>Téléphone</span>
                                        <span class="required" data-phone-required hidden>*</span>
                                    </label>
                                    <input type="tel" name="donor_phone" id="donor_phone" value="{{ old('donor_phone') }}">
                                    @error('donor_phone') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="donor_address">Adresse postale</label>
                                    <input type="text" name="donor_address" id="donor_address" value="{{ old('donor_address') }}">
                                    @error('donor_address') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Paiement --}}
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="payment_method">Moyen de paiement <span class="required">*</span></label>
                                    <select name="payment_method" id="payment_method" required>
                                        @foreach (\App\Models\Donation::PUBLIC_METHODS as $value)
                                            <option value="{{ $value }}" @selected(old('payment_method', 'orange_money') === $value)>{{ \App\Models\Donation::METHODS[$value] }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_method') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="fund_type">Intitulé libre (optionnel)</label>
                                    <input type="text" name="fund_type" id="fund_type" value="{{ old('fund_type') }}"
                                        placeholder="Ex. : en mémoire de…">
                                    @error('fund_type') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="message">Message à l'équipe</label>
                            <textarea name="message" id="message" placeholder="Votre message (facultatif)">{{ old('message') }}</textarea>
                            @error('message') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-row checkbox-row">
                            <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" @checked(old('is_anonymous'))>
                            <label for="is_anonymous">Je souhaite que mon don reste anonyme sur le site</label>
                        </div>

                        <div class="button-box" style="margin-top: 30px;">
                            <button class="btn-one" type="submit">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Confirmer ma promesse de don</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Colonne d'information --}}
                <div class="col-xl-4">
                    <div class="sidebar-content-box sidebar-content-box--plain">
                        <div class="single-sidebar-box">
                            <div class="sidebar-categories">
                                <div class="title">
                                    <h3>Mobile Money</h3>
                                </div>
                                <table class="info-table" style="margin: 10px 0 0;">
                                    <tbody>
                                        @foreach (\App\Models\Donation::MOBILE_MONEY as $method => $settingKey)
                                            @if (filled(setting($settingKey)))
                                                <tr>
                                                    <th>{{ \App\Models\Donation::METHODS[$method] }}</th>
                                                    <td>{{ setting($settingKey) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="single-sidebar-box">
                            <div class="sidebar-categories">
                                <div class="title">
                                    <h3>Reçu de don</h3>
                                </div>
                                <p style="padding: 6px 0 0;">{{ setting('donation_tax_notice', 'Chaque don donne lieu à un reçu nominatif envoyé par e-mail.') }}</p>
                            </div>
                        </div>

                        <div class="single-sidebar-box">
                            <div class="sidebar-categories">
                                <div class="title">
                                    <h3>Virement bancaire</h3>
                                </div>
                                <table class="info-table" style="margin: 10px 0 0;">
                                    <tbody>
                                        @foreach (['donation_bank_holder' => 'Titulaire', 'donation_bank_name' => 'Banque', 'donation_iban' => 'N° de compte', 'donation_bic' => 'BIC'] as $settingKey => $label)
                                            @if (filled(setting($settingKey)))
                                                <tr>
                                                    <th>{{ $label }}</th>
                                                    <td>{{ setting($settingKey) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="single-sidebar-box">
                            <div class="sidebar-categories">
                                <div class="title">
                                    <h3>Où va votre don ?</h3>
                                </div>
                                <ul class="sidebar-categories-box">
                                    <li><a href="{{ route('pages.show', 'transparence-financiere') }}"><i class="fa fa-check-circle"></i>85 % aux programmes</a></li>
                                    <li><a href="{{ route('pages.show', 'transparence-financiere') }}"><i class="fa fa-check-circle"></i>10 % au suivi et à l'audit</a></li>
                                    <li><a href="{{ route('pages.show', 'transparence-financiere') }}"><i class="fa fa-check-circle"></i>5 % aux frais administratifs</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="single-sidebar-box">
                            <div class="sidebar-categories">
                                <div class="title">
                                    <h3>Une question ?</h3>
                                </div>
                                <p style="padding: 6px 0 0;">
                                    Appelez-nous au <a href="tel:{{ preg_replace('/\s+/', '', setting('contact_phone', '')) }}">{{ setting('contact_phone') }}</a>
                                    ou écrivez à <a href="mailto:{{ setting('contact_email_secondary', setting('contact_email')) }}">{{ setting('contact_email_secondary', setting('contact_email')) }}</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        // Les boutons de montant préremplissent le champ libre.
        (function () {
            const input = document.getElementById('amount');
            const buttons = document.querySelectorAll('.amount-presets button');

            const sync = () => buttons.forEach(button => {
                button.classList.toggle('is-active', button.dataset.amount === input.value);
            });

            buttons.forEach(button => button.addEventListener('click', () => {
                input.value = button.dataset.amount;
                sync();
            }));

            input.addEventListener('input', sync);
            sync();
        })();

        // Avec Mobile Money, le numéro du payeur devient obligatoire pour rapprocher le paiement.
        (function () {
            const mobileMoney = @json(array_keys(\App\Models\Donation::MOBILE_MONEY));
            const method = document.getElementById('payment_method');
            const phone = document.getElementById('donor_phone');
            const label = document.querySelector('[data-phone-label]');
            const star = document.querySelector('[data-phone-required]');

            const sync = () => {
                const isMobileMoney = mobileMoney.includes(method.value);
                label.textContent = isMobileMoney ? 'Numéro Mobile Money qui effectuera le paiement' : 'Téléphone';
                star.hidden = !isMobileMoney;
                phone.required = isMobileMoney;
            };

            method.addEventListener('change', sync);
            sync();
        })();
    </script>
@endpush
