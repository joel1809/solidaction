@props(['partners'])

@if ($partners->isNotEmpty())
    <section class="partner-area">
        <div class="container">
            <x-site.sec-title subtitle="Ils nous font confiance" title="Nos partenaires" />

            <div class="row">
                <div class="col-xl-12">
                    <div class="partner-content">
                        <div class="theme_carousel owl-theme owl-carousel"
                            data-options='{"loop": true, "margin": 30, "nav": false, "dots": false, "autoplay": true, "autoplayTimeout": 5000, "smartSpeed": 500, "responsive":{ "0" :{ "items": "1" }, "480" :{ "items" : "2" }, "768" :{ "items" : "3" }, "992":{ "items" : "4" }, "1200":{ "items" : "5" }}}'>
                            @foreach ($partners as $partner)
                                <div class="single-partner-logo">
                                    <figure class="logo-box">
                                        <a href="{{ $partner->url ?: '#' }}" @if ($partner->url) target="_blank" rel="noopener" @endif>
                                            <img src="{{ media($partner->logo, 'theme/assets/images/brand/brand-logo-1.png') }}" alt="{{ $partner->name }}">
                                        </a>
                                    </figure>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
