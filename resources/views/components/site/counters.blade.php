@props(['stats'])

@php
    $items = [
        [
            'icon' => 'fact-counter-1',
            'value' => round(($stats['donations'] ?? 0) / 1000, 1),
            'suffix' => 'K',
            'prefix' => setting('currency_symbol', '€'),
            'label' => 'Collectés grâce à<br> nos donateurs',
            'style' => '',
        ],
        [
            'icon' => 'fact-counter-2',
            'value' => $stats['causes'] ?? 0,
            'suffix' => '',
            'prefix' => null,
            'label' => 'Projets menés avec<br> nos partenaires',
            'style' => 'style2',
        ],
        [
            'icon' => 'fact-counter-3',
            'value' => round(($stats['beneficiaries'] ?? 0) / 1000, 1),
            'suffix' => 'K',
            'prefix' => null,
            'label' => 'Bénéficiaires accompagnés<br> sur le terrain',
            'style' => 'style3',
        ],
        [
            'icon' => 'fact-counter-4',
            'value' => $stats['volunteers'] ?? 0,
            'suffix' => '',
            'prefix' => null,
            'label' => 'Bénévoles engagés<br> à nos côtés',
            'style' => 'style4',
        ],
    ];
@endphp

<section class="fact-counter-area">
    <div class="fact-counter-area_bg" style="background-image: url({{ theme_asset('assets/images/parallax-background/fact-counter-area_bg.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-7">
                <div class="fact-counter_box">
                    <ul class="clearfix">
                        @foreach ($items as $index => $item)
                            <li class="single-fact-counter wow slideInLeft" data-wow-delay="{{ $index * 100 }}ms" data-wow-duration="1500ms">
                                <div class="outer-box">
                                    <div class="shape1"><img src="{{ theme_asset('assets/images/shape/thm-shape-5.png') }}" alt=""></div>
                                    <div class="shape2 zoominout"><img src="{{ theme_asset('assets/images/shape/thm-shape-6.png') }}" alt=""></div>
                                    <div class="top">
                                        <div class="icon-box">
                                            <div class="icon"><img src="{{ theme_asset('assets/images/icon/fact-counter/'.$item['icon'].'.png') }}" alt=""></div>
                                        </div>
                                        <div class="count-outer count-box {{ $item['style'] }}">
                                            @if ($item['prefix'])
                                                <span class="dolor-sign">{{ $item['prefix'] }}</span>
                                            @endif
                                            <span class="count-text" data-speed="3000" data-stop="{{ $item['value'] }}">0</span><span>{{ $item['suffix'] }}</span>
                                        </div>
                                    </div>
                                    <div class="text">
                                        <p>{!! $item['label'] !!}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="video-holder-box">
                    <div class="icon wow zoomIn" data-wow-delay="300ms" data-wow-duration="1500ms">
                        <a class="video-popup" title="Vidéo de présentation" href="{{ setting('intro_video', 'https://www.youtube.com/watch?v=MLpWrANjFbI') }}">
                            <span class="flaticon-play-button-1"></span>
                        </a>
                        <div class="title">
                            <h5>Voir la vidéo de présentation</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
