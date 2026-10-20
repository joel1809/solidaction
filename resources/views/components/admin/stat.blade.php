@props(['label', 'value', 'icon' => 'grid', 'hint' => null, 'trend' => null, 'href' => null])

@php $tag = $href ? 'a' : 'div'; @endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif
    class="block rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition {{ $href ? 'hover:border-zinc-300 hover:shadow' : '' }}">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-500">{{ $label }}</p>
            <p class="mt-2 truncate text-2xl font-semibold tracking-tight text-zinc-900">{{ $value }}</p>
            @if ($hint)
                <p class="mt-1 text-xs text-zinc-500">{{ $hint }}</p>
            @endif
        </div>
        <span class="grid size-10 shrink-0 place-items-center rounded-lg bg-zinc-100 text-zinc-500">
            <x-admin.icon :name="$icon" class="size-5" />
        </span>
    </div>
    @if (! is_null($trend))
        <p class="mt-3 inline-flex items-center gap-1 text-xs font-medium {{ $trend >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
            <x-admin.icon name="trend" class="size-3.5 {{ $trend >= 0 ? '' : 'rotate-90' }}" />
            {{ $trend >= 0 ? '+' : '' }}{{ $trend }} % sur 30 jours
        </p>
    @endif
</{{ $tag }}>
