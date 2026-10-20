@props(['title' => null, 'subtitle' => null, 'padded' => true])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-zinc-200 bg-white shadow-sm']) }}>
    @if ($title || isset($actions))
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 px-5 py-4">
            <div>
                @if ($title)
                    <h2 class="text-sm font-semibold text-zinc-900">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p class="text-xs text-zinc-500">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </header>
    @endif

    <div class="{{ $padded ? 'p-5' : '' }}">
        {{ $slot }}
    </div>
</section>
