@php
    $menu = [
        ['label' => 'Accueil', 'route' => 'home'],
        ['label' => 'Association', 'route' => 'about', 'children' => [
            ['label' => 'Qui sommes-nous', 'route' => 'about'],
            ['label' => 'Notre équipe', 'route' => 'team'],
            ['label' => 'Témoignages', 'route' => 'testimonials'],
            ['label' => 'Questions fréquentes', 'route' => 'faq'],
            ['label' => 'Devenir bénévole', 'route' => 'volunteers.create'],
        ]],
        ['label' => 'Nos causes', 'route' => 'causes.index'],
        ['label' => 'Événements', 'route' => 'events.index'],
        ['label' => 'Actualités', 'route' => 'posts.index'],
        ['label' => 'Contact', 'route' => 'contact.create'],
    ];

    $isCurrent = function (array $item) {
        $routes = collect($item['children'] ?? [])->pluck('route')->push($item['route'])->all();

        foreach ($routes as $name) {
            $base = str_replace(['.index', '.create'], '', $name);
            if (request()->routeIs($name) || request()->routeIs($base.'.*')) {
                return true;
            }
        }

        return false;
    };
@endphp

<header class="main-header header-style-one">
    <div class="header-top">
        <div class="container">
            <div class="outer-box clearfix">
                <div class="header-top_left pull-left">
                    <div class="icon">
                        <img src="{{ theme_asset('assets/images/icon/arrow-1.png') }}" alt="">
                    </div>
                    <div class="header-top_left-content">
                        <div class="theme_carousel header-top_left-carousel owl-theme owl-carousel"
                            data-options='{"loop": true, "margin": 0, "autoheight":true, "nav": true, "dots": false, "autoplay": true, "autoplayTimeout": 6000, "smartSpeed": 300, "responsive":{ "0" :{ "items": "1" }, "768" :{ "items" : "1" }, "1200":{ "items" : "1" }}}'>
                            <div class="single-item">
                                <p>Parrainez un enfant et offrez-lui une année scolaire complète</p>
                            </div>
                            <div class="single-item">
                                <p>Appelez le {{ setting('contact_phone', '+33 1 84 80 12 34') }} pour soutenir nos actions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="header-top_right pull-right">
                    <div class="header-social-link-1">
                        <ul class="clearfix">
                            @foreach (['twitter' => 'social_twitter', 'facebook' => 'social_facebook', 'linkedin' => 'social_linkedin', 'youtube' => 'social_youtube', 'instagram' => 'social_instagram'] as $icon => $key)
                                @if ($url = setting($key))
                                    <li><a href="{{ $url }}" target="_blank" rel="noopener"><i class="fa fa-{{ $icon }}" aria-hidden="true"></i></a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header">
        <div class="container">
            <div class="outer-box clearfix">
                <div class="header-left clearfix pull-left">
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ media(setting('site_logo'), 'theme/assets/images/resources/logo.png') }}"
                                alt="{{ setting('site_name', 'SolidAction') }}">
                        </a>
                    </div>
                </div>

                <div class="header-right pull-right">
                    <div class="nav-outer style1 clearfix">
                        <div class="mobile-nav-toggler">
                            <div class="inner">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </div>
                        </div>

                        <nav class="main-menu style1 navbar-expand-md navbar-light">
                            <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    @foreach ($menu as $item)
                                        <li class="{{ isset($item['children']) ? 'dropdown' : '' }} {{ $isCurrent($item) ? 'current' : '' }}">
                                            <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                                            @isset($item['children'])
                                                <ul>
                                                    @foreach ($item['children'] as $child)
                                                        <li><a href="{{ route($child['route']) }}">{{ $child['label'] }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endisset
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </nav>
                    </div>

                    <div class="header-right_buttom">
                        <div class="btns-box">
                            <a class="btn-one" href="{{ route('donations.create') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Faire un don</span>
                            </a>
                        </div>
                        <div class="side-content-button">
                            <a class="navSidebar-button" href="#">
                                <ul><li></li><li></li><li></li></ul>
                                <ul><li></li><li></li><li></li></ul>
                                <ul><li></li><li></li><li></li></ul>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky header --}}
    <div class="sticky-header">
        <div class="container">
            <div class="clearfix">
                <div class="logo float-left">
                    <a href="{{ route('home') }}" class="img-responsive">
                        <img src="{{ media(setting('site_logo_sticky'), 'theme/assets/images/resources/sticky-logo.png') }}" alt="{{ setting('site_name', 'SolidAction') }}">
                    </a>
                </div>
                <div class="right-col float-right">
                    <nav class="main-menu clearfix">
                        {{-- Le menu est cloné par custom.js --}}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Menu mobile --}}
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><span class="icon fa fa-times-circle"></span></div>
        <nav class="menu-box">
            <div class="nav-logo">
                <a href="{{ route('home') }}">
                    <img src="{{ media(setting('site_logo_mobile'), 'theme/assets/images/resources/mobilemenu-logo.png') }}" alt="{{ setting('site_name', 'SolidAction') }}">
                </a>
            </div>
            <div class="menu-outer">
                {{-- Le menu est cloné par custom.js --}}
            </div>
            <div class="social-links">
                <ul class="clearfix">
                    @foreach (['facebook-square' => 'social_facebook', 'twitter-square' => 'social_twitter', 'linkedin-square' => 'social_linkedin', 'youtube-square' => 'social_youtube'] as $icon => $key)
                        @if ($url = setting($key))
                            <li><a href="{{ $url }}" target="_blank" rel="noopener"><span class="fab fa fa-{{ $icon }}"></span></a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</header>
