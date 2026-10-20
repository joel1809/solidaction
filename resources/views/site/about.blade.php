@extends('layouts.site')

@section('title', 'Qui sommes-nous')
@section('meta_description', 'SolidAction, ONG de droit ivoirien : notre histoire, notre mission, notre gouvernance et nos engagements de transparence.')

@section('content')

    <x-site.breadcrumb title="Qui sommes-nous" :items="['Qui sommes-nous']" image="theme/assets/images/breadcrumb/breadcrumb-1.jpg" />

    {{-- ============ Présentation ============ --}}
    <section class="about-style1-area" style="padding: 110px 0 90px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="causes-style1_image-box">
                        <div class="shape wow slideInLeft" data-wow-delay="0ms" data-wow-duration="3500ms">
                            <img class="zoom-fade" src="{{ theme_asset('assets/images/shape/thm-shape-7.png') }}" alt="">
                        </div>
                        <div class="main">
                            <img src="{{ theme_asset('assets/images/about/about-style1-image-1.jpg') }}" alt="Nos équipes sur le terrain">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <x-site.sec-title subtitle="Notre histoire" :title="e(setting('about_title', 'Vingt ans d\'engagement aux côtés des plus fragiles'))" :center="false" />

                    <div class="inner-content">
                        <div class="text-box">
                            <p>{{ setting('about_text') }}</p>
                        </div>

                        <table class="info-table" style="margin-top: 24px;">
                            <tbody>
                                <tr>
                                    <th>Statut</th>
                                    <td>ONG de droit ivoirien (loi n° 60-315 du 21 septembre 1960)</td>
                                </tr>
                                <tr>
                                    <th>Zones d'intervention</th>
                                    <td>14 régions de Côte d'Ivoire, toujours avec une association communautaire</td>
                                </tr>
                                <tr>
                                    <th>Contrôle des comptes</th>
                                    <td>Certification annuelle par un commissaire aux comptes</td>
                                </tr>
                                <tr>
                                    <th>Part affectée aux programmes</th>
                                    <td>85 % des dons collectés</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="btns-box" style="margin-top: 26px;">
                            <a class="btn-one" href="{{ route('pages.show', 'transparence-financiere') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Notre transparence</span>
                            </a>
                            <a class="btn-one marlft15" href="{{ route('volunteers.create') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Devenir bénévole</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ Chiffres clés ============ --}}
    <x-site.counters :stats="$stats" />

    {{-- ============ Mission ============ --}}
    <section class="mission-and-goals-area">
        <div class="container">
            <x-site.sec-title subtitle="Notre raison d'être" title="Mission &amp; objectifs" />

            <div class="row">
                <div class="col-xl-12">
                    <div class="mission-goals-content clearfix">
                        <div class="thm-shape2"><img src="{{ theme_asset('assets/images/shape/thm-shape-5.png') }}" alt=""></div>
                        <div class="mission-goals-image-box" style="background-image: url({{ theme_asset('assets/images/resources/mission-goals.jpg') }});"></div>
                        <div class="text-holder">
                            <div class="top">
                                <div class="icon">
                                    <img src="{{ theme_asset('assets/images/shape/thm-shape-4.png') }}" alt="">
                                </div>
                                <div class="title">
                                    <h3>{{ setting('mission_title') }}</h3>
                                </div>
                            </div>
                            <div class="text">
                                <p>{{ setting('mission_text') }}</p>
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

    {{-- ============ Équipe ============ --}}
    @if ($team->isNotEmpty())
        <section class="team-style1-area">
            <div class="container">
                <x-site.sec-title subtitle="Celles et ceux qui agissent" title="L'équipe permanente" />

                <div class="row">
                    @foreach ($team as $member)
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <x-site.team-card :member="$member" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center" style="margin-top: 20px;">
                    <a class="btn-one" href="{{ route('team') }}">
                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Toute l'équipe</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============ FAQ ============ --}}
    @if ($faqs->isNotEmpty())
        <section class="faq-style1-area" style="padding: 100px 0;">
            <div class="container">
                <x-site.sec-title subtitle="Vos questions" title="Ce qu'on nous demande le plus" />

                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="faq-style1-content">
                            <div class="accordion-box">
                                @foreach ($faqs as $index => $faq)
                                    <div class="accordion accordion-block">
                                        <div class="accord-btn {{ $index === 0 ? 'active' : '' }}">
                                            <h4>{{ $faq->question }}</h4>
                                        </div>
                                        <div class="accord-content {{ $index === 0 ? 'collapsed' : '' }}">
                                            <p>{{ $faq->answer }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 44px;">
                    <a class="btn-one" href="{{ route('faq') }}">
                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Toutes les questions</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============ Témoignages ============ --}}
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

    <x-site.donate-cta />

    <x-site.partners :partners="$partners" />

@endsection
