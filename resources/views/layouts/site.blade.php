<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', setting('site_name', 'Solid’Action')) &mdash; {{ setting('site_tagline', 'Agir ensemble, changer des vies') }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="@yield('meta_description', setting('site_description', 'ONG de solidarité internationale : dons, parrainages, bénévolat et actions de terrain.'))">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ theme_asset('assets/css/aos.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/imp.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/custom-animate.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/owl.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/scrollbar.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/hiddenbar.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/bootstrap-select.min.css') }}">

    {{-- Module css --}}
    @foreach ([
        'header-section', 'banner-section', 'about-section', 'blog-section',
        'fact-counter-section', 'faq-section', 'contact-page', 'breadcrumb-section',
        'team-section', 'partner-section', 'testimonial-section', 'footer-section',
    ] as $module)
        <link rel="stylesheet" href="{{ theme_asset('assets/css/module-css/'.$module.'.css') }}">
    @endforeach

    <link rel="stylesheet" href="{{ theme_asset('assets/css/color.css') }}">
    <link href="{{ theme_asset('assets/css/color/theme-color.css') }}" id="jssDefault" rel="stylesheet">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ theme_asset('assets/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ theme_asset('assets/images/favicon/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ theme_asset('assets/images/favicon/favicon-16x16.png') }}" sizes="16x16">

    @stack('styles')
</head>

<body class="@yield('body_class')">

    <div class="boxed_wrapper ltr">

        {{-- Preloader --}}
        <div class="loader-wrap">
            <div class="preloader">
                <div class="preloader-close">Fermer</div>
            </div>
            <div class="layer layer-one"><span class="overlay"></span></div>
            <div class="layer layer-two"><span class="overlay"></span></div>
            <div class="layer layer-three"><span class="overlay"></span></div>
        </div>

        @include('partials.site.aside')

        @include('partials.site.header')

        @yield('content')

        @include('partials.site.footer')

        <button class="scroll-top scroll-to-target" data-target="html">
            <span class="fa fa-angle-up"></span>
        </button>
    </div>

    <script src="{{ theme_asset('assets/js/jquery.js') }}"></script>
    <script src="{{ theme_asset('assets/js/aos.js') }}"></script>
    <script src="{{ theme_asset('assets/js/appear.js') }}"></script>
    <script src="{{ theme_asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/isotope.js') }}"></script>
    <script src="{{ theme_asset('assets/js/jquery.countTo.js') }}"></script>
    <script src="{{ theme_asset('assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/jquery.enllax.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/jquery.paroller.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/knob.js') }}"></script>
    <script src="{{ theme_asset('assets/js/owl.js') }}"></script>
    <script src="{{ theme_asset('assets/js/parallax.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/scrollbar.js') }}"></script>
    <script src="{{ theme_asset('assets/js/slick.js') }}"></script>
    <script src="{{ theme_asset('assets/js/wow.js') }}"></script>
    <script src="{{ theme_asset('assets/js/TweenMax.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/jquery-sidebar-content.js') }}"></script>
    <script src="{{ theme_asset('assets/js/custom.js') }}"></script>

    @stack('scripts')
</body>

</html>
