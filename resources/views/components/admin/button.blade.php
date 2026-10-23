@props(['variant' => 'primary', 'href' => null, 'icon' => null, 'type' => 'submit'])

@php
    $styles = [
        'primary' => 'bg-brand-yellow text-zinc-900 hover:bg-brand-yellow-dark focus-visible:outline-brand-yellow',
        'secondary' => 'border border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50',
        'dark' => 'bg-zinc-900 text-white hover:bg-zinc-800',
        'danger' => 'border border-rose-200 bg-white text-rose-600 hover:bg-rose-50',
        'ghost' => 'text-zinc-600 hover:bg-zinc-100',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg px-3.5 py-2 text-sm font-medium transition focus:outline-none '.($styles[$variant] ?? $styles['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon) <x-admin.icon :name="$icon" class="size-4" /> @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon) <x-admin.icon :name="$icon" class="size-4" /> @endif
        {{ $slot }}
    </button>
@endif
