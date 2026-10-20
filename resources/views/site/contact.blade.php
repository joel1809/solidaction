@extends('layouts.site')

@section('title', 'Contact')
@section('meta_description', 'Contactez SolidAction : siège social, téléphone, e-mail et formulaire de contact direct.')

@section('content')

    <x-site.breadcrumb title="Contact" :items="['Contact']" image="theme/assets/images/breadcrumb/breadcrumb-4.jpg" />

    <section class="contact-style1-area" style="padding: 110px 0 90px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <div class="contact-style1_form">
                        <x-site.sec-title subtitle="Nous sommes à votre écoute" title="Écrivez-nous" :center="false">
                            Don, reçu de don, legs, mécénat d'entreprise ou demande presse : décrivez votre demande,
                            nous répondons sous 48 heures ouvrées.
                        </x-site.sec-title>

                        <x-site.alert />

                        <div class="contact-form">
                            <form action="{{ route('contact.store') }}" method="post" class="app-form">
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
                                            <label for="subject">Objet</label>
                                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}">
                                            @error('subject') <span class="field-error">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label for="message">Votre message <span class="required">*</span></label>
                                    <textarea name="message" id="message" required>{{ old('message') }}</textarea>
                                    @error('message') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="button-box">
                                    <button class="btn-one" type="submit">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Envoyer le message</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="sidebar-content-box">
                        <div class="contact-info-sidebar">
                            <ul>
                                <li>
                                    <div class="top">
                                        <div class="icon">
                                            <span class="flaticon-maps-and-flags"></span>
                                        </div>
                                        <div class="title">
                                            <h3>Siège social</h3>
                                        </div>
                                    </div>
                                    <p>{!! nl2br(e(setting('contact_address', 'Cocody Riviera 2, 08 BP 1234 Abidjan 08'))) !!}</p>
                                </li>

                                <li>
                                    <div class="top">
                                        <div class="icon">
                                            <span class="flaticon-phone-call-1"></span>
                                        </div>
                                        <div class="title">
                                            <h3>Téléphone</h3>
                                        </div>
                                    </div>
                                    <p>Accueil <a href="tel:{{ preg_replace('/\s+/', '', setting('contact_phone', '')) }}">{{ setting('contact_phone') }}</a></p>
                                    @if (setting('contact_phone_secondary'))
                                        <p>Dons <a href="tel:{{ preg_replace('/\s+/', '', setting('contact_phone_secondary')) }}">{{ setting('contact_phone_secondary') }}</a></p>
                                    @endif
                                </li>

                                <li>
                                    <div class="top">
                                        <div class="icon">
                                            <span class="flaticon-opened"></span>
                                        </div>
                                        <div class="title">
                                            <h3>E-mail</h3>
                                        </div>
                                    </div>
                                    <p><a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a></p>
                                    @if (setting('contact_email_secondary'))
                                        <p><a href="mailto:{{ setting('contact_email_secondary') }}">{{ setting('contact_email_secondary') }}</a></p>
                                    @endif
                                </li>

                                <li>
                                    <div class="top">
                                        <div class="icon">
                                            <span class="flaticon-clock"></span>
                                        </div>
                                        <div class="title">
                                            <h3>Horaires</h3>
                                        </div>
                                    </div>
                                    <p>{{ setting('contact_hours', 'Du lundi au vendredi, 9h00 — 18h00') }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($map = setting('contact_map'))
        <section class="contact-map-area">
            <iframe src="{{ $map }}" style="width: 100%; height: 450px; border: 0; display: block;"
                loading="lazy" title="Carte du siège social" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>
    @endif

@endsection
