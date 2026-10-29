@extends('layouts.site')

@section('title', 'Devenir bénévole')
@section('meta_description', 'Rejoignez les 240 bénévoles de Solid’Action : logistique, communication, éducation, santé ou administration.')

@section('content')

    <x-site.breadcrumb title="Devenir bénévole" :items="['Association' => route('about'), 'Devenir bénévole']"
        image="theme/assets/images/breadcrumb/journee-verte.jpg" />

    <section class="volunteer-area" style="padding: 110px 0 90px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <x-site.sec-title subtitle="Donnez de votre temps" title="Rejoignez nos équipes" :center="false">
                        Quelques heures par mois suffisent. Après un entretien et une journée de formation,
                        vous choisissez la mission qui correspond à vos disponibilités et à vos compétences.
                    </x-site.sec-title>

                    <x-site.alert />

                    <form action="{{ route('volunteers.store') }}" method="post" class="app-form">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="name">Nom et prénom <span class="required">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                                    @error('name') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="email">Adresse e-mail <span class="required">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                                    @error('email') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="phone">Téléphone</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}">
                                    @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="birthdate">Date de naissance</label>
                                    <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}">
                                    @error('birthdate') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="address">Ville / code postal</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}">
                            @error('address') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="interest">Domaine d'engagement <span class="required">*</span></label>
                                    <select name="interest" id="interest" required>
                                        <option value="">Choisissez un domaine…</option>
                                        @foreach (\App\Models\Volunteer::INTERESTS as $value => $label)
                                            <option value="{{ $value }}" @selected(old('interest') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('interest') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-row">
                                    <label for="availability">Disponibilités</label>
                                    <input type="text" name="availability" id="availability" value="{{ old('availability') }}"
                                        placeholder="Ex. : mercredis après-midi">
                                    @error('availability') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="message">Parlez-nous de vous</label>
                            <textarea name="message" id="message" placeholder="Expériences, compétences, motivations…">{{ old('message') }}</textarea>
                            @error('message') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="button-box">
                            <button class="btn-one" type="submit">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Envoyer ma candidature</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-xl-4">
                    <div class="sidebar-content-box sidebar-content-box--plain">
                        <div class="volunteer-sidebar">
                            <div class="volunteer-sidebar__section">
                                <h3>Comment ça se passe ?</h3>
                                <ol class="volunteer-sidebar__steps">
                                    <li>Vous envoyez ce formulaire</li>
                                    <li>Entretien sous une semaine</li>
                                    <li>Journée de formation</li>
                                    <li>Première mission</li>
                                </ol>
                            </div>

                            <div class="volunteer-sidebar__section">
                                <h3>Nos besoins actuels</h3>
                                <ul class="volunteer-sidebar__list">
                                    @foreach (\App\Models\Volunteer::INTERESTS as $label)
                                        <li>{{ $label }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="volunteer-sidebar__section">
                                <h3>Une question ?</h3>
                                <p>
                                    Contactez notre coordinatrice au
                                    <a href="tel:{{ preg_replace('/\s+/', '', setting('contact_phone', '')) }}">{{ setting('contact_phone') }}</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
