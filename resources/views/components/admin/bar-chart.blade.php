@props([
    'series' => [],          // [['label' => 'Mars', 'value' => 1200, 'caption' => '03/2025'], …]
    'unit' => '',            // suffixe affiché dans l'infobulle
    'height' => 240,
    'tableLabel' => 'Valeur',
])

@php
    $series = collect($series)->values();
    $max = (float) $series->max('value') ?: 1;

    // Arrondi « propre » de l'axe : 1, 2 ou 5 × puissance de 10.
    $magnitude = 10 ** floor(log10($max));
    $niceMax = collect([1, 2, 2.5, 5, 10])
        ->map(fn ($factor) => $factor * $magnitude)
        ->first(fn ($candidate) => $candidate >= $max) ?? $max;

    $width = 720;
    $padding = ['top' => 16, 'right' => 10, 'bottom' => 30, 'left' => 52];
    $plotWidth = $width - $padding['left'] - $padding['right'];
    $plotHeight = $height - $padding['top'] - $padding['bottom'];
    $baseline = $padding['top'] + $plotHeight;

    $count = max(1, $series->count());
    $slot = $plotWidth / $count;
    $barWidth = min(44, $slot * 0.62);

    // Un libellé sur deux au-delà de 12 colonnes pour éviter les collisions.
    $labelEvery = $count > 12 ? 2 : 1;

    $formatValue = fn ($value) => number_format((float) $value, 0, ',', ' ');
@endphp

<figure {{ $attributes->merge(['class' => 'm-0']) }}>
    <svg viewBox="0 0 {{ $width }} {{ $height }}" class="h-auto w-full" role="img"
        aria-label="Histogramme : {{ $tableLabel }} par période">

        {{-- Grille et graduations (volontairement discrètes) --}}
        @foreach ([0, 0.25, 0.5, 0.75, 1] as $step)
            @php $y = $padding['top'] + $plotHeight * (1 - $step); @endphp
            <line x1="{{ $padding['left'] }}" y1="{{ round($y, 1) }}" x2="{{ $width - $padding['right'] }}"
                y2="{{ round($y, 1) }}" stroke="{{ $step === 0 ? '#d4d4d4' : '#f0f0f0' }}" stroke-width="1"
                @if ($step > 0) stroke-dasharray="3 4" @endif />
            <text x="{{ $padding['left'] - 10 }}" y="{{ round($y + 4, 1) }}" text-anchor="end"
                font-size="11" fill="#737373">{{ $formatValue($niceMax * $step) }}</text>
        @endforeach

        {{-- Barres --}}
        @foreach ($series as $index => $point)
            @php
                $value = (float) ($point['value'] ?? 0);
                $barHeight = $niceMax > 0 ? max(0, ($value / $niceMax) * $plotHeight) : 0;
                $x = $padding['left'] + $slot * $index + ($slot - $barWidth) / 2;
                $top = $baseline - $barHeight;
                $radius = min(4, $barWidth / 2, max(0, $barHeight));
                $path = $barHeight <= 0.5
                    ? null
                    : sprintf(
                        'M %.1f %.1f a %.1f %.1f 0 0 1 %.1f %.1f h %.1f a %.1f %.1f 0 0 1 %.1f %.1f V %.1f H %.1f Z',
                        $x, $top + $radius,
                        $radius, $radius, $radius, -$radius,
                        $barWidth - 2 * $radius,
                        $radius, $radius, $radius, $radius,
                        $baseline, $x
                    );
                $tip = ($point['caption'] ?? $point['label'] ?? '').' : '.$formatValue($value).' '.$unit;
            @endphp

            <g class="group" data-tip="{{ trim($tip) }}">
                {{-- Zone de survol plus large que la barre --}}
                <rect x="{{ round($padding['left'] + $slot * $index, 1) }}" y="{{ $padding['top'] }}"
                    width="{{ round($slot, 1) }}" height="{{ $plotHeight }}" fill="transparent" />
                @if ($path)
                    <path d="{{ $path }}" fill="#e11d48" class="transition-[fill] group-hover:fill-[#9f1239]" />
                @else
                    <line x1="{{ round($x, 1) }}" y1="{{ $baseline }}" x2="{{ round($x + $barWidth, 1) }}"
                        y2="{{ $baseline }}" stroke="#d4d4d4" stroke-width="2" />
                @endif
            </g>

            @if ($index % $labelEvery === 0)
                <text x="{{ round($padding['left'] + $slot * $index + $slot / 2, 1) }}" y="{{ $height - 10 }}"
                    text-anchor="middle" font-size="11" fill="#737373">{{ $point['label'] ?? '' }}</text>
            @endif
        @endforeach
    </svg>

    {{-- Vue tabulaire équivalente --}}
    <details class="mt-3 text-sm">
        <summary class="cursor-pointer text-xs font-medium text-zinc-500 hover:text-zinc-700">Afficher les données</summary>
        <div class="mt-2 max-h-64 overflow-auto rounded-lg border border-zinc-200">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 text-xs uppercase tracking-wide text-zinc-500">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium">Période</th>
                        <th class="px-3 py-2 text-right font-medium">{{ $tableLabel }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach ($series as $point)
                        <tr>
                            <td class="px-3 py-2 text-zinc-600">{{ $point['caption'] ?? $point['label'] }}</td>
                            <td class="px-3 py-2 text-right font-medium text-zinc-900">
                                {{ $formatValue($point['value'] ?? 0) }} {{ $unit }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </details>
</figure>
