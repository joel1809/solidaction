@extends('layouts.site')

@section('title', $cause->title)
@section('meta_description', Str::limit(strip_tags($cause->excerpt), 155))

@section('content')

    <x-site.breadcrumb :title="$cause->title" :items="['Nos causes' => route('causes.index'), Str::limit($cause->title, 30)]"
        image="theme/assets/images/breadcrumb/breadcrumb-3.jpg" />

    <section class="cause-details-area" style="padding: 110px 0 80px;">
        <div class="container">
            <div class="row">
                {{-- ================= Colonne principale ================= --}}
                <div class="col-xl-8">
                    <div class="cause-details-content">
                        <x-site.alert />

                        <div class="cause-details-image" style="margin-bottom: 40px;">
                            <img src="{{ media($cause->image, 'theme/assets/images/causes/cause-details-image-1.jpg') }}"
                                alt="{{ $cause->title }}" style="width: 100%; border-radius: 6px;">
                        </div>

                        @if ($cause->category)
                            <div class="category" style="margin-bottom: 18px;">
                                <h6>{{ $cause->category->name }}</h6>
                            </div>
                        @endif

                        <div class="donate-form-box donate-form-box--style2">
                            <div class="top-title">
                                <h2>{{ $cause->title }}</h2>
                            </div>

                            <div class="progress-levels progress-levels-style2">
                                <div class="progress-box wow animated" style="visibility: visible;">
                                    <div class="inner count-box">
                                        <div class="bar">
                                            <div class="bar-innner">
                                                <div class="bar-fill" data-percent="{{ $cause->progress }}" title="{{ $cause->title }}"></div>
                                            </div>
                                        </div>
                                        <div class="bottom-box">
                                            <div class="rate-box">
                                                <p>Collecté<span>{{ money($cause->raised_amount) }}</span></p>
                                                <p>Objectif<span>{{ money($cause->goal_amount) }}</span></p>
                                            </div>
                                            <div class="skill-percent">
                                                <span class="count-text" data-speed="3000" data-stop="{{ $cause->progress }}">0</span>
                                                <span class="percent">%</span>
                                                <p class="outer-text">atteint</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="donation_wrapper">
                                <div class="bottom-box">
                                    <div class="btns">
                                        <a class="btn-one" href="{{ route('donations.create', ['cause' => $cause->id]) }}">
                                            <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Faire un don</span>
                                        </a>
                                    </div>
                                    <div class="social-share">
                                        <div class="title">
                                            <h5>Partager</h5>
                                        </div>
                                        <ul class="social-links">
                                            <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a></li>
                                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener"><i class="fa fa-linkedin"></i></a></li>
                                            <li><a href="mailto:?subject={{ urlencode($cause->title) }}&body={{ urlencode(request()->fullUrl()) }}"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="info-table">
                            <tbody>
                                @if ($cause->location)
                                    <tr>
                                        <th>Lieu d'intervention</th>
                                        <td>{{ $cause->location }}</td>
                                    </tr>
                                @endif
                                @if ($cause->deadline)
                                    <tr>
                                        <th>Échéance de la collecte</th>
                                        <td>
                                            {{ $cause->deadline->translatedFormat('d F Y') }}
                                            @if ($cause->days_left !== null)
                                                <span>&mdash; {{ $cause->days_left }} jour(s) restant(s)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Reste à collecter</th>
                                    <td>{{ money($cause->remaining) }}</td>
                                </tr>
                                <tr>
                                    <th>Nombre de donateurs</th>
                                    <td>{{ $cause->donations()->completed()->count() }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="content-prose">
                            {!! $cause->description !!}
                        </div>

                        @if ($recentDonations->isNotEmpty())
                            <div class="single-sidebar-box" style="margin-top: 40px;">
                                <div class="title">
                                    <h3>Derniers dons reçus</h3>
                                </div>
                                <ul class="donor-list">
                                    @foreach ($recentDonations as $donation)
                                        <li>
                                            <span class="donor">{{ $donation->display_name }}
                                                <small style="color:#999; font-weight:400;">&mdash; {{ $donation->created_at->diffForHumans() }}</small>
                                            </span>
                                            <span>{{ money($donation->amount) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ================= Colonne latérale ================= --}}
                <div class="col-xl-4">
                    <div class="sidebar-content-box">
                        <div class="single-sidebar-box">
                            <div class="sidebar-categories">
                                <div class="title">
                                    <h3>Nos programmes</h3>
                                </div>
                                <ul class="sidebar-categories-box">
                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="{{ route('causes.index', ['categorie' => $category->slug]) }}">
                                                <i class="fa fa-check-circle" aria-hidden="true"></i>{{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="single-sidebar-box">
                            <div class="sidebar-slogan-box" style="background-image: url({{ theme_asset('assets/images/sidebar/sidebar-slogan-img.jpg') }});">
                                <div class="icon">
                                    <img src="{{ media(setting('site_logo_footer'), 'theme/assets/images/logo/logo-footer.png') }}" alt="{{ setting('site_name', 'SolidAction') }}" />
                                </div>
                                <p>Un petit don, un grand impact</p>
                                <h2>Soutenez<br>ce projet</h2>
                                <div class="btn-box">
                                    <div class="hand"><img src="{{ theme_asset('assets/images/shape/hand.png') }}" alt=""></div>
                                    <a class="btn-one" href="{{ route('donations.create', ['cause' => $cause->id]) }}">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>je donne</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if ($related->isNotEmpty())
                            <div class="single-sidebar-box">
                                <div class="sidebar-campaigns">
                                    <div class="title">
                                        <h3>Autres causes</h3>
                                    </div>
                                    <ul class="recent-campaigns">
                                        @foreach ($related as $item)
                                            <li>
                                                <div class="inner">
                                                    <div class="img-box">
                                                        <img src="{{ media($item->image, 'theme/assets/images/sidebar/campaigns-1.jpg') }}" alt="{{ $item->title }}">
                                                        <div class="overlay-content">
                                                            <a href="{{ route('causes.show', $item) }}"><i class="fa fa-link" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="title-box">
                                                        <h4><a href="{{ route('causes.show', $item) }}">{{ Str::limit($item->title, 38) }}</a></h4>
                                                        <p>{{ money($item->raised_amount) }} / {{ money($item->goal_amount) }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="single-sidebar-box">
                            <div class="single-sidebar_search_box">
                                <div class="title">
                                    <h3>Rechercher</h3>
                                </div>
                                <div class="sidebar-search-box">
                                    <form class="search-form" action="{{ route('causes.index') }}" method="get">
                                        <input placeholder="Mot-clé" type="text" name="q" value="{{ request('q') }}">
                                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
