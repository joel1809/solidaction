@props(['event'])

<div class="single-event-style1">
    <div class="date-box">
        <div class="left">
            <h2>{{ $event->starts_at->format('d') }}</h2>
        </div>
        <div class="right">
            <h3>{{ Str::upper($event->starts_at->translatedFormat('M')) }}</h3>
        </div>
    </div>
    <div class="meta-info">
        <p>Organisé par : <a href="{{ route('about') }}">{{ $event->organizer ?: setting('site_name', 'SolidAction') }}</a></p>
    </div>
    <div class="title">
        <h2><a href="{{ route('events.show', $event) }}">{{ $event->title }}</a></h2>
    </div>
    <div class="border-box"></div>
    <div class="event-time">
        <div class="icon">
            <span class="flaticon-clock"></span>
        </div>
        <div class="text">
            <p>{{ $event->starts_at->format('H\hi') }} &mdash; {{ $event->location }}</p>
        </div>
    </div>
</div>
