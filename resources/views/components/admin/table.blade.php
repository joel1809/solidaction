@props(['head' => []])

<div class="overflow-x-auto">
    <table class="w-full text-sm">
        @if (count($head))
            <thead class="bg-zinc-50 text-xs uppercase tracking-wide text-zinc-500">
                <tr>
                    @foreach ($head as $label => $align)
                        <th class="px-5 py-3 font-medium {{ is_int($label) ? 'text-left' : 'text-'.$align }}">
                            {{ is_int($label) ? $align : $label }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-zinc-100">
            {{ $slot }}
        </tbody>
    </table>
</div>
