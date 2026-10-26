@extends('layouts.site')

@section('title', 'Événements')
@section('meta_description', 'Courses solidaires, dîners caritatifs, webinaires et formations : retrouvez tous les rendez-vous Solid’Action.')

@section('content')

    <x-site.breadcrumb title="Nos événements" :items="['Événements']" image="theme/assets/images/breadcrumb/breadcrumb-5.jpg" />

    <section class="event-page-one" style="padding: 110px 0 80px;">
        <div class="container">
            <x-site.alert />

            <div class="filter-bar">
                <ul class="filter-links">
                    @foreach (['upcoming' => 'À venir', 'past' => 'Passés', 'all' => 'Tous'] as $key => $label)
                        <li>
                            <a href="{{ route('events.index', ['periode' => $key]) }}" class="{{ $filter === $key ? 'is-active' : '' }}">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
                <p style="margin: 0; color: #777;">{{ $events->total() }} événement(s)</p>
            </div>

            <div class="row">
                @forelse ($events as $event)
                    <div class="col-xl-4 col-lg-6 col-md-6" style="margin-bottom: 40px;">
                        <div class="single-blog-style1">
                            <div class="img-holder">
                                <div class="inner">
                                    <img src="{{ media($event->image, 'theme/assets/images/events/event-details-1.jpg') }}" alt="{{ $event->title }}">
                                    <div class="overlay-icon">
                                        <a href="{{ route('events.show', $event) }}"><span class="flaticon-plus"></span></a>
                                    </div>
                                </div>
                                <div class="date-box">
                                    <h2>{{ $event->starts_at->format('d') }}</h2>
                                    <p>{{ Str::upper($event->starts_at->translatedFormat('M')) }}</p>
                                </div>
                            </div>
                            <div class="text-holder">
                                <h3 class="blog-title">
                                    <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                                </h3>
                                <div class="text">
                                    <p>{{ Str::limit(strip_tags($event->excerpt), 100) }}</p>
                                </div>
                                <ul class="meta-info">
                                    <li><i class="fa fa-map-marker" aria-hidden="true"></i> {{ Str::limit($event->location, 24) }}</li>
                                    <li><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $event->starts_at->format('H\hi') }}</li>
                                </ul>
                                <p style="margin-top: 10px; font-weight: 600;">
                                    @if ($event->is_past)
                                        <span style="color:#999;">Événement terminé</span>
                                    @elseif ($event->ticket_price > 0)
                                        Participation : {{ money($event->ticket_price) }}
                                    @else
                                        Entrée libre
                                    @endif
                                    @if ($event->seats_left !== null && ! $event->is_past)
                                        &mdash; {{ $event->seats_left }} place(s)
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-xl-12">
                        <div class="empty-state">
                            <h3>Aucun événement pour cette période</h3>
                            <p>Consultez nos événements passés ou abonnez-vous à la newsletter pour être prévenu.</p>
                            <a class="btn-one" href="{{ route('events.index', ['periode' => 'all']) }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Tous les événements</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($events->hasPages())
                <div class="row">
                    <div class="col-xl-12">{{ $events->links() }}</div>
                </div>
            @endif
        </div>
    </section>

@endsection
