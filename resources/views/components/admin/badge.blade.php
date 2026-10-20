@props(['color' => 'zinc'])

@php
    $palette = [
        'zinc' => 'bg-zinc-100 text-zinc-700',
        'green' => 'bg-emerald-100 text-emerald-700',
        'red' => 'bg-rose-100 text-rose-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'blue' => 'bg-sky-100 text-sky-700',
        'violet' => 'bg-violet-100 text-violet-700',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium '.($palette[$color] ?? $palette['zinc'])]) }}>
    {{ $slot }}
</span>
