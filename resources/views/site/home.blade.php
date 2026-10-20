@extends('layouts.site')

@section('title', 'Accueil')

@section('content')

    {{-- ======================= Slider ======================= --}}
    <section class="main-slider style1">
        <div class="shape2 paroller"></div>
        <div class="slider-box">
            <div class="banner-carousel owl-theme owl-carousel">
                @forelse ($sliders as $slide)
                    <div class="slide">
                        <div class="image-layer" style="background-image:url({{ media($slide->image, 'theme/assets/images/slides/slide-v1-1.jpg') }})"></div>
                        <div class="auto-container">
                            <div class="content">
                                <h3>{{ $slide->subtitle }}</h3>
                                <div class="big-title">
                                    <h2>{!! nl2br(e($slide->title)) !!}</h2>
                                </div>
                                <div class="border-box"></div>
                                <div class="text">
                                    <p>{{ $slide->text }}</p>
                                </div>
                                <div class="btns-box">
                                    @if ($slide->button_text)
                                        <a class="btn-one btn-one-style2" href="{{ $slide->button_url ?: route('causes.index') }}">
                                            <span class="txt"><i class="arrow1 fa fa-check-circle"></i>{{ $slide->button_text }}</span>
                                        </a>
                                    @endif
                                    @if ($slide->button2_text)
                                        <a class="btn-one marlft15" href="{{ $slide->button2_url ?: route('donations.create') }}">
                                            <span class="txt"><i class="arrow1 fa fa-check-circle"></i>{{ $slide->button2_text }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="slide">
                        <div class="image-layer" style="background-image:url({{ theme_asset('assets/images/slides/slide-v1-1.jpg') }})"></div>
                        <div class="auto-container">
                            <div class="content">
                                <h3>Changer une vie, changer le monde</h3>
                                <div class="big-title">
                                    <h2>Chaque geste<br> est une victoire</h2>
                                </div>
                                <div class="border-box"></div>
                                <div class="text">
                                    <p>Rejoignez {{ setting('site_name', 'SolidAction') }} et soutenez nos programmes de terrain</p>
                                </div>
                                <div class="btns-box">
                                    <a class="btn-one btn-one-style2" href="{{ route('about') }}">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Notre mission</span>
                                    </a>
                                    <a class="btn-one marlft15" href="{{ route('donations.create') }}">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Faire un don</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ======================= Trois piliers ======================= --}}
    <section class="features-style1-area">
        <div class="container">
            <x-site.alert />

            <x-site.sec-title subtitle="Agir ensemble, durablement" title="Une solidarité qui change tout" />

            <div class="row">
                <div class="col-xl-12">
                    <div class="features-style1_box">
                        <div class="thm-shape1 wow slideInRight" data-wow-delay="0ms" data-wow-duration="3500ms">
                            <img class="rotate-me" src="{{ theme_asset('assets/images/shape/thm-shape-1.png') }}" alt="">
                        </div>
                        <div class="row">
                            @php
                                $features = [
                                    ['icon' => 'feature-v1-1', 'style' => '', 'title' => 'Soutenir une cause', 'text' => 'Choisissez un projet et suivez l\'avancement de la collecte en toute transparence.', 'url' => route('causes.index'), 'cta' => 'Voir les causes'],
                                    ['icon' => 'feature-v1-2', 'style' => 'style2', 'title' => 'Faire un don', 'text' => 'Don unique ou mensuel : 100 % de votre geste sert nos programmes de terrain.', 'url' => route('donations.create'), 'cta' => 'Je donne'],
                                    ['icon' => 'feature-v1-3', 'style' => 'style3', 'title' => 'Devenir bénévole', 'text' => 'Donnez de votre temps et de vos compétences auprès de nos équipes locales.', 'url' => route('volunteers.create'), 'cta' => 'Je m\'engage'],
                                ];
                            @endphp

                            @foreach ($features as $index => $feature)
                                <div class="col-xl-4 col-lg-4 text-center" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="{{ 500 + $index * 100 }}">
                                    <div class="features-style1_single {{ $feature['style'] }}">
                                        <div class="icon-holder">
                                            <div class="inner">
                                                <img src="{{ theme_asset('assets/images/icon/features/'.$feature['icon'].'.png') }}" alt="">
                                            </div>
                                            <div class="shape1 zoominout">
                                                <img src="{{ theme_asset('assets/images/icon/features/shape-1.png') }}" alt="">
                                            </div>
                                            <div class="shape-bg">
                                                <img src="{{ theme_asset('assets/images/icon/features/'.$feature['icon'].'-bg.png') }}" alt="">
                                            </div>
                                        </div>
                                        <div class="text-holder">
                                            <h3>{{ $feature['title'] }}</h3>
                                            <p>{{ $feature['text'] }}</p>
                                            <div class="btns-box">
                                                <a class="btn-one" href="{{ $feature['url'] }}">
                                                    <span class="txt"><i class="arrow1 fa fa-check-circle"></i>{{ $feature['cta'] }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================= Cause à la une ======================= --}}
    @if ($featuredCause)
        <section class="causes-style1-area">
            <div class="causes-style1-area_bg" style="background-image: url({{ theme_asset('assets/images/pattern/thm-pattern-1.png') }});"></div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="causes-style1_image-box">
                            <div class="shape wow slideInLeft" data-wow-delay="0ms" data-wow-duration="3500ms">
                                <img class="zoom-fade" src="{{ theme_asset('assets/images/shape/thm-shape-7.png') }}" alt="">
                            </div>
                            <div class="causes-style1_image2">
                                <img src="{{ theme_asset('assets/images/resources/causes-style1_image-2.jpg') }}" alt="">
                            </div>
                            <div class="main">
                                <img src="{{ media($featuredCause->image, 'theme/assets/images/resources/causes-style1_image-1.jpg') }}" alt="{{ $featuredCause->title }}">
                            </div>
                            <div class="causes-style1_image3">
                                <img src="{{ theme_asset('assets/images/resources/causes-style1_image-3.jpg') }}" alt="">
                            </div>
                            <div class="causes-style1_image4">
                                <img src="{{ theme_asset('assets/images/resources/causes-style1_image-4.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="causes-style1_content-box">
                            <x-site.sec-title subtitle="Notre cause prioritaire" :title="e($featuredCause->title)" :center="false" />

                            <div class="inner-content">
                                <div class="text-box">
                                    <p>{{ Str::limit(strip_tags($featuredCause->excerpt ?: $featuredCause->description), 280) }}</p>
                                </div>

                                <div class="progress-levels">
                                    <div class="progress-box wow">
                                        <div class="inner count-box">
                                            <div class="bar">
                                                <div class="bar-innner">
                                                    <div class="bar-fill" data-percent="{{ $featuredCause->progress }}" title="{{ $featuredCause->title }}"></div>
                                                </div>
                                                <div class="text">Objectif : {{ money($featuredCause->goal_amount) }}</div>
                                            </div>
                                            <div class="skill-percent">
                                                <span class="count-text" data-speed="3000" data-stop="{{ $featuredCause->progress }}">0</span>
                                                <span class="percent">%</span>
                                                <span class="outer-text">déjà collectés</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="btns-box">
                                    <a class="btn-one" href="{{ route('donations.create', ['cause' => $featuredCause->id]) }}">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Soutenir ce projet</span>
                                    </a>
                                    @if ($featuredCause->video_url)
                                        <a class="video-popup cause-video-button" title="Vidéo du projet" href="{{ $featuredCause->video_url }}">
                                            <span class="flaticon-play-button playicon"></span>
                                            <span class="txt">vidéo du projet</span>
                                        </a>
                                    @else
                                        <a class="btn-one marlft15" href="{{ route('causes.show', $featuredCause) }}">
                                            <span class="txt"><i class="arrow1 fa fa-check-circle"></i>En savoir plus</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ======================= Liste des causes ======================= --}}
    @if ($causes->isNotEmpty())
        <section class="cause-style2-area">
            <div class="thm-shape1 float-bob"><img src="{{ theme_asset('assets/images/shape/thm-shape-2.png') }}" alt=""></div>
            <div class="thm-shape2 zoom-fade"><img src="{{ theme_asset('assets/images/shape/thm-shape-3.png') }}" alt=""></div>
            <div class="auto-container">
                <x-site.sec-title subtitle="Nos collectes en cours" title="Appels à la solidarité" />

                <div class="row">
                    <div class="col-xl-12">
                        <div class="theme_carousel cause2-carousel owl-dot-style1 owl-theme owl-carousel"
                            data-options='{"loop": true, "margin": 40, "autoheight":true, "nav": false, "dots": true, "autoplay": true, "autoplayTimeout": 6000, "smartSpeed": 300, "responsive":{ "0" :{ "items": "1" }, "768" :{ "items" : "1" }, "992":{ "items" : "2" }, "1200":{ "items" : "3" }}}'>
                            @foreach ($causes as $cause)
                                <x-site.cause-card :cause="$cause" />
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 50px;">
                    <a class="btn-one" href="{{ route('causes.index') }}">
                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Toutes nos causes</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ======================= Compteurs ======================= --}}
    <x-site.counters :stats="$stats" />

    {{-- ======================= Mission & objectifs ======================= --}}
    <section class="mission-and-goals-area">
        <div class="container">
            <x-site.sec-title subtitle="Notre raison d'être" title="Mission &amp; objectifs" />

            <div class="row">
                <div class="col-xl-12">
                    <div class="mission-goals-content clearfix">
                        <div class="thm-shape1 wow slideInRight" data-wow-delay="0ms" data-wow-duration="3500ms">
                            <img class="rotate-me" src="{{ theme_asset('assets/images/shape/thm-shape-1.png') }}" alt="">
                        </div>
                        <div class="thm-shape2"><img src="{{ theme_asset('assets/images/shape/thm-shape-5.png') }}" alt=""></div>
                        <div class="mission-goals-image-box" style="background-image: url({{ theme_asset('assets/images/resources/mission-goals.jpg') }});"></div>
                        <div class="text-holder">
                            <div class="top">
                                <div class="icon">
                                    <img src="{{ theme_asset('assets/images/shape/thm-shape-4.png') }}" alt="">
                                </div>
                                <div class="title">
                                    <h3>{{ setting('mission_title', 'Un petit don aujourd\'hui, un grand changement demain.') }}</h3>
                                </div>
                            </div>
                            <div class="text">
                                <p>{{ setting('mission_text', 'Nous construisons des projets avec les communautés locales : points d\'eau, écoles, centres de santé et réponse rapide aux situations d\'urgence. Chaque franc est tracé et audité.') }}</p>
                            </div>
                            <ul class="clearfix">
                                <li>
                                    <div class="icon">
                                        <span class="flaticon-house-insurance"></span>
                                        <div class="shape"><img src="{{ theme_asset('assets/images/shape/mission-goal-shape-1.png') }}" alt=""></div>
                                    </div>
                                    <div class="title">
                                        <h3>Abri &amp; logement</h3>
                                    </div>
                                </li>
                                <li class="style2">
                                    <div class="icon">
                                        <span class="flaticon-water"></span>
                                        <div class="shape"><img src="{{ theme_asset('assets/images/shape/mission-goal-shape-2.png') }}" alt=""></div>
                                    </div>
                                    <div class="title">
                                        <h3>Eau &amp; nutrition</h3>
                                    </div>
                                </li>
                                <li class="style3">
                                    <div class="icon">
                                        <span class="flaticon-world"></span>
                                        <div class="shape"><img src="{{ theme_asset('assets/images/shape/mission-goal-shape-3.png') }}" alt=""></div>
                                    </div>
                                    <div class="title">
                                        <h3>Éducation partout</h3>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================= Don rapide ======================= --}}
    <x-site.donate-cta />

    {{-- ======================= Équipe ======================= --}}
    @if ($team->isNotEmpty())
        <section class="team-style1-area">
            <div class="container">
                <x-site.sec-title subtitle="Celles et ceux qui agissent" title="Notre équipe de terrain" />

                <div class="row">
                    @foreach ($team as $member)
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <x-site.team-card :member="$member" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ======================= Événements ======================= --}}
    @if ($events->isNotEmpty())
        <section class="event-style1-area">
            <div class="custom-container">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="event-style1_image-box" style="background-image: url({{ theme_asset('assets/images/resources/event-style1-image.jpg') }});"></div>
                    </div>
                    <div class="col-xl-6">
                        <div class="event-style1_content-box">
                            <x-site.sec-title subtitle="Prochains rendez-vous" title="Événements &amp; rencontres" :center="false" />

                            <div class="inner-content">
                                <div class="event-style1-carousel owl-carousel owl-theme owl-nav-style-one">
                                    @foreach ($events as $event)
                                        <x-site.event-item :event="$event" />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ======================= Témoignages ======================= --}}
    @if ($testimonials->isNotEmpty())
        <section class="testimonial-style1-area">
            <div class="testimonial-style1-area_bg" style="background-image: url({{ theme_asset('assets/images/pattern/thm-pattern-1.png') }});"></div>
            <div class="container">
                <div class="testimonial-style1-content">
                    <div class="testimonial-style1_carousel owl-carousel owl-theme">
                        @foreach ($testimonials as $testimonial)
                            <x-site.testimonial-item :testimonial="$testimonial" />
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ======================= Actualités ======================= --}}
    @if ($posts->isNotEmpty())
        <section class="blog-style1-area">
            <div class="thm-shape1 float-bob"><img src="{{ theme_asset('assets/images/shape/thm-shape-2.png') }}" alt=""></div>
            <div class="container">
                <x-site.sec-title subtitle="Des nouvelles du terrain" title="Actualités &amp; reportages" />

                <div class="row">
                    @foreach ($posts as $index => $post)
                        <div class="col-xl-4 col-lg-4">
                            <x-site.post-card :post="$post" :delay="($index * 150).'ms'" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center" style="margin-top: 20px;">
                    <a class="btn-one" href="{{ route('posts.index') }}">
                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Toutes les actualités</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ======================= Partenaires ======================= --}}
    <x-site.partners :partners="$partners" />

@endsection
