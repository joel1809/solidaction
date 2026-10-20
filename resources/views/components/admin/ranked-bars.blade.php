@props([
    'items' => [],   // [['label' => 'Forage…', 'value' => 12400, 'href' => null], …]
    'unit' => '',
])

@php
    $items = collect($items)->values();
    $max = (float) $items->max('value') ?: 1;
@endphp

<ul {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @foreach ($items as $item)
        @php $share = $max > 0 ? max(2, round(((float) $item['value'] / $max) * 100, 1)) : 0; @endphp
        <li>
            <div class="flex items-baseline justify-between gap-3">
                <p class="min-w-0 truncate text-sm text-zinc-700">
                    @if (! empty($item['href']))
                        <a href="{{ $item['href'] }}" class="hover:text-zinc-900 hover:underline">{{ $item['label'] }}</a>
                    @else
                        {{ $item['label'] }}
                    @endif
                </p>
                {{-- Libellé direct : pas de légende nécessaire pour une série unique --}}
                <p class="shrink-0 text-sm font-semibold tabular-nums text-zinc-900">
                    {{ number_format((float) $item['value'], 0, ',', ' ') }} {{ $unit }}
                </p>
            </div>
            <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-zinc-100">
                <div class="h-full rounded-full bg-[#e11d48]" style="width: {{ $share }}%"></div>
            </div>
        </li>
    @endforeach
</ul>
