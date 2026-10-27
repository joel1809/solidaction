@extends('layouts.site')

@section('title', 'Qui sommes-nous')
@section('meta_description', 'Solid’Action, ONG de droit ivoirien : notre histoire, notre mission, notre gouvernance et nos engagements de transparence.')

@section('content')

    <x-site.breadcrumb title="Qui sommes-nous" :items="['Qui sommes-nous']" image="theme/assets/images/breadcrumb/breadcrumb-1.jpg" />

    {{-- ============ Présentation ============ --}}
    <section class="about-style1-area" style="padding: 110px 0 90px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 about-intro clearfix">
                    <div class="causes-style1_image-box">
                        <div class="shape wow slideInLeft" data-wow-delay="0ms" data-wow-duration="3500ms">
                            <img class="zoom-fade" src="{{ theme_asset('assets/images/shape/thm-shape-7.png') }}" alt="">
                        </div>
                        <div class="main">
                            <img src="{{ theme_asset('assets/images/about/about-style1-image-1.jpg') }}" alt="Nos équipes sur le terrain">
                        </div>
                    </div>

                    <x-site.sec-title subtitle="Notre histoire" :title="e(setting('about_title', 'Depuis 2013 aux côtés des plus fragiles'))" :center="false" />

                    <div class="inner-content">
                        <div class="text-box">
                            @foreach (preg_split('/\R{2,}/', trim((string) setting('about_text'))) as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
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

    @push('styles')
        <style>
            /* L'image flotte à gauche : le texte et le tableau l'entourent puis continuent en dessous. */
            @media (min-width: 1200px) {
                .about-intro .causes-style1_image-box {
                    float: left;
                    width: 480px;
                    margin: 0 50px 30px 0;
                    shape-outside: circle(245px at 215px 240px);
                }
            }
            @media (max-width: 1199px) {
                .about-intro .causes-style1_image-box {
                    margin: 0 auto 40px;
                }
            }
        </style>
    @endpush

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
                                    <img src="{{ media(setting('site_logo'), 'theme/assets/images/logo/logo-header.png') }}" alt="{{ setting('site_name', 'Solid’Action') }}">
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

@endsection
