@props([
    'title' => '',
    'items' => [],
    'image' => 'theme/assets/images/breadcrumb/breadcrumb-1.jpg',
])

<section class="breadcrumb-area" style="background-image: url({{ media($image, 'theme/assets/images/breadcrumb/breadcrumb-1.jpg') }});">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="inner-content text-center">
                    <div class="parallax-scene parallax-scene-1">
                        <div data-depth="0.20" class="parallax-layer shape wow zoomInRight" data-wow-duration="2000ms">
                            <div class="shape1">
                                <img class="float-bob" src="{{ theme_asset('assets/images/shape/breadcrumb-shape1.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="parallax-scene parallax-scene-1">
                        <div data-depth="0.20" class="parallax-layer shape wow zoomInRight" data-wow-duration="2000ms">
                            <div class="shape2">
                                <img class="zoominout" src="{{ theme_asset('assets/images/shape/breadcrumb-shape2.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="title">
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="border-box"></div>
                    <div class="breadcrumb-menu">
                        <ul>
                            <li><a href="{{ route('home') }}">Accueil</a></li>
                            @foreach ($items as $label => $url)
                                <li><span class="flaticon-right-arrow"></span></li>
                                @if (is_int($label))
                                    <li class="active">{{ $url }}</li>
                                @else
                                    <li><a href="{{ $url }}">{{ $label }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('structured_data')
    @php
        $trail = [['name' => 'Accueil', 'url' => route('home')]];
        foreach ($items as $label => $url) {
            $trail[] = is_int($label) ? ['name' => $url, 'url' => url()->current()] : ['name' => $label, 'url' => $url];
        }
    @endphp
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($trail)->values()->map(fn ($crumb, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ])->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
@endpush
