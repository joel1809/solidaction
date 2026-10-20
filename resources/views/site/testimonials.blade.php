@extends('layouts.site')

@section('title', 'Témoignages')
@section('meta_description', 'Donateurs, bénévoles, partenaires et bénéficiaires racontent leur expérience avec SolidAction.')

@section('content')

    <x-site.breadcrumb title="Témoignages" :items="['Association' => route('about'), 'Témoignages']"
        image="theme/assets/images/breadcrumb/breadcrumb-2.jpg" />

    <section class="testimonial-page-one" style="padding: 110px 0 80px;">
        <div class="container">
            <x-site.sec-title subtitle="Ils en parlent mieux que nous" title="Paroles de donateurs et de partenaires" />

            <div class="row">
                @forelse ($testimonials as $testimonial)
                    <div class="col-xl-6 col-lg-6">
                        <div class="testimonial-grid-item">
                            <span class="quote-icon flaticon-right-quotes-symbol"></span>
                            @if ($testimonial->title)
                                <h3>{{ $testimonial->title }}</h3>
                            @endif
                            <p>{{ $testimonial->content }}</p>
                            <div class="author">
                                <h4>{{ $testimonial->name }}</h4>
                                <span>{{ $testimonial->location }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-xl-12">
                        <div class="empty-state">
                            <h3>Aucun témoignage publié pour le moment</h3>
                            <p>Vous souhaitez partager votre expérience ? Écrivez-nous.</p>
                            <a class="btn-one" href="{{ route('contact.create') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Nous contacter</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <x-site.partners :partners="$partners" />

@endsection
