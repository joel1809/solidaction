@extends('layouts.site')

@section('title', $event->title)
@section('meta_description', Str::limit(strip_tags($event->excerpt), 155))

@section('content')

    <x-site.breadcrumb :title="$event->title" :items="['Événements' => route('events.index'), Str::limit($event->title, 30)]"
        image="theme/assets/images/breadcrumb/breadcrumb-5.jpg" />

    <section class="event-details-area" style="padding: 110px 0 80px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <x-site.alert />

                    <div class="event-details-image" style="margin-bottom: 40px;">
                        <img src="{{ media($event->image, 'theme/assets/images/events/event-details-1.jpg') }}"
                            alt="{{ $event->title }}" style="width: 100%; border-radius: 6px;">
                    </div>

                    <x-site.sec-title :subtitle="$event->starts_at->translatedFormat('l d F Y')" :title="e($event->title)" :center="false" />

                    <table class="info-table">
                        <tbody>
                            <tr>
                                <th>Date et heure</th>
                                <td>
                                    {{ $event->starts_at->translatedFormat('d F Y à H\hi') }}
                                    @if ($event->ends_at)
                                        &mdash; fin vers {{ $event->ends_at->format('H\hi') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Lieu</th>
                                <td>{{ $event->location }}@if ($event->address)<br><small>{{ $event->address }}</small>@endif</td>
                            </tr>
                            <tr>
                                <th>Organisateur</th>
                                <td>{{ $event->organizer ?: setting('site_name') }}</td>
                            </tr>
                            <tr>
                                <th>Participation</th>
                                <td>{{ $event->ticket_price > 0 ? money($event->ticket_price) : 'Entrée libre' }}</td>
                            </tr>
                            @if ($event->capacity)
                                <tr>
                                    <th>Places</th>
                                    <td>{{ $event->seats_taken }} inscrit(s) sur {{ $event->capacity }}
                                        &mdash; {{ $event->seats_left }} restante(s)</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="content-prose">
                        {!! $event->description !!}
                    </div>

                    {{-- Formulaire d'inscription --}}
                    @if ($event->is_past)
                        <div class="empty-state" style="margin-top: 40px;">
                            <h3>Cet événement est terminé</h3>
                            <p>Retrouvez nos prochains rendez-vous sur la page des événements.</p>
                            <a class="btn-one" href="{{ route('events.index') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Prochains événements</span>
                            </a>
                        </div>
                    @elseif ($event->seats_left === 0)
                        <div class="empty-state" style="margin-top: 40px;">
                            <h3>Complet</h3>
                            <p>Toutes les places sont réservées. Écrivez-nous pour être placé sur liste d'attente.</p>
                            <a class="btn-one" href="{{ route('contact.create') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Nous contacter</span>
                            </a>
                        </div>
                    @else
                        <div class="contact-form" style="margin-top: 50px;">
                            <h3 style="font-size: 28px; margin-bottom: 26px;">S'inscrire à cet événement</h3>

                            <form action="{{ route('events.register', $event) }}" method="post" class="app-form">
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
                                            <label for="seats">Nombre de places <span class="required">*</span></label>
                                            <input type="number" name="seats" id="seats" min="1" max="20"
                                                value="{{ old('seats', 1) }}" required>
                                            @error('seats') <span class="field-error">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label for="message">Message (allergies, accessibilité…)</label>
                                    <textarea name="message" id="message">{{ old('message') }}</textarea>
                                    @error('message') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="button-box">
                                    <button class="btn-one" type="submit">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Je m'inscris</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="col-xl-4">
                    <div class="sidebar-content-box">
                        @if ($upcoming->isNotEmpty())
                            <div class="single-sidebar-box">
                                <div class="sidebar-campaigns">
                                    <div class="title">
                                        <h3>Prochains événements</h3>
                                    </div>
                                    <ul class="recent-campaigns recent-news">
                                        @foreach ($upcoming as $item)
                                            <li>
                                                <div class="inner">
                                                    <div class="img-box">
                                                        <img src="{{ media($item->image, 'theme/assets/images/sidebar/campaigns-1.jpg') }}" alt="{{ $item->title }}">
                                                        <div class="overlay-content">
                                                            <a href="{{ route('events.show', $item) }}"><i class="fa fa-link"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="title-box">
                                                        <h4><a href="{{ route('events.show', $item) }}">{{ Str::limit($item->title, 36) }}</a></h4>
                                                        <p>{{ $item->starts_at->translatedFormat('d M Y') }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="single-sidebar-box">
                            <div class="sidebar-slogan-box" style="background-image: url({{ theme_asset('assets/images/sidebar/sidebar-slogan-img.jpg') }});">
                                <div class="icon">
                                    <img src="{{ theme_asset('assets/images/icon/thm-logo1.png') }}" alt="" />
                                </div>
                                <p>Vous ne pouvez pas venir ?</p>
                                <h2>Soutenez-nous<br>autrement</h2>
                                <div class="btn-box">
                                    <div class="hand"><img src="{{ theme_asset('assets/images/shape/hand.png') }}" alt=""></div>
                                    <a class="btn-one" href="{{ route('donations.create') }}">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>faire un don</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
