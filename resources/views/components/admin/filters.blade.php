@props([
    'action',
    'placeholder' => 'Rechercher…',
    'selects' => [],   // ['status' => ['label' => 'Statut', 'options' => [...]], …]
    'search' => true,
    'dates' => false,
])

<form action="{{ $action }}" method="GET"
    class="mb-5 flex flex-wrap items-end gap-3 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">

    @if ($search)
        <div class="min-w-[220px] flex-1">
            <label for="filter-q" class="mb-1.5 block text-xs font-medium text-zinc-500">Recherche</label>
            <input type="search" name="q" id="filter-q" value="{{ request('q') }}" placeholder="{{ $placeholder }}"
                class="w-full rounded-lg border border-zinc-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-100">
        </div>
    @endif

    @foreach ($selects as $name => $config)
        <div class="min-w-[160px]">
            <label for="filter-{{ $name }}" class="mb-1.5 block text-xs font-medium text-zinc-500">{{ $config['label'] }}</label>
            <select name="{{ $name }}" id="filter-{{ $name }}"
                class="w-full rounded-lg border border-zinc-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-100">
                <option value="">{{ $config['placeholder'] ?? 'Tous' }}</option>
                @foreach ($config['options'] as $value => $label)
                    <option value="{{ $value }}" @selected((string) request($name) === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    @endforeach

    @if ($dates)
        <div>
            <label for="filter-from" class="mb-1.5 block text-xs font-medium text-zinc-500">Du</label>
            <input type="date" name="from" id="filter-from" value="{{ request('from') }}"
                class="rounded-lg border border-zinc-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-100">
        </div>
        <div>
            <label for="filter-to" class="mb-1.5 block text-xs font-medium text-zinc-500">Au</label>
            <input type="date" name="to" id="filter-to" value="{{ request('to') }}"
                class="rounded-lg border border-zinc-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-100">
        </div>
    @endif

    <div class="flex items-center gap-2">
        <x-admin.button type="submit" variant="dark">Filtrer</x-admin.button>
        @if (collect(request()->except('page'))->filter()->isNotEmpty())
            <x-admin.button variant="ghost" :href="$action">Réinitialiser</x-admin.button>
        @endif
    </div>

    {{ $slot }}
</form>
