@props([
    'subtitle' => null,
    'title' => '',
    'center' => true,
    'icon' => true,
])

<div class="sec-title {{ $center ? 'text-center' : '' }}">
    @if ($subtitle)
        <div class="sub-title {{ $center ? '' : 'martop0' }}">
            <div class="inner">
                <h3>{{ $subtitle }}</h3>
            </div>
            @if ($icon && $center)
                <div class="outer"><img src="{{ theme_asset('assets/images/icon/loveicon.png') }}" alt=""></div>
            @endif
        </div>
    @endif
    <h2>{!! $title !!}</h2>
    @if (trim($slot) !== '')
        <p>{{ $slot }}</p>
    @endif
</div>
