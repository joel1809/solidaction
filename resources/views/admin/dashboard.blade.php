@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('subtitle', 'Vue d\'ensemble de la collecte et des contenus')

@section('content')

    {{-- ================== Actions en attente ================== --}}
    @if (array_sum($todo) > 0)
        <div class="mb-6 flex flex-wrap items-center gap-3 rounded-xl border border-[#e5e5e5] bg-white px-5 py-4">
            <p class="text-sm font-medium text-amber-900">À traiter :</p>
            @if ($todo['donations'])
                <a href="{{ route('admin.donations.index', ['status' => 'pending']) }}"
                    class="rounded-lg bg-white px-3 py-1.5 text-sm font-medium text-amber-900 shadow-sm hover:bg-amber-100">
                    {{ $todo['donations'] }} don(s) à valider
                </a>
            @endif
            @if ($todo['comments'])
                <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}"
                    class="rounded-lg bg-white px-3 py-1.5 text-sm font-medium text-amber-900 shadow-sm hover:bg-amber-100">
                    {{ $todo['comments'] }} commentaire(s) à modérer
                </a>
            @endif
            @if ($todo['volunteers'])
                <a href="{{ route('admin.volunteers.index', ['status' => 'pending']) }}"
                    class="rounded-lg bg-white px-3 py-1.5 text-sm font-medium text-amber-900 shadow-sm hover:bg-amber-100">
                    {{ $todo['volunteers'] }} candidature(s) bénévole
                </a>
            @endif
            @if ($todo['messages'])
                <a href="{{ route('admin.messages.index', ['status' => 'new']) }}"
                    class="rounded-lg bg-white px-3 py-1.5 text-sm font-medium text-amber-900 shadow-sm hover:bg-amber-100">
                    {{ $todo['messages'] }} message(s) non lu(s)
                </a>
            @endif
        </div>
    @endif

    {{-- ================== Indicateurs ================== --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat label="Total collecté" :value="money($kpis['collected'])" icon="banknotes"
            :trend="$kpis['collected_trend']" :hint="money($kpis['last30']).' sur 30 jours'"
            :href="route('admin.donations.index')" />

        <x-admin.stat label="Donateurs uniques" :value="number_format($kpis['donors'], 0, ',', ' ')" icon="users"
            :hint="'Don moyen : '.money($kpis['average'], true)" :href="route('admin.donations.index')" />

        <x-admin.stat label="Causes publiées" :value="$kpis['causes']" icon="heart"
            :hint="$kpis['posts'].' article(s) en ligne'" :href="route('admin.causes.index')" />

        <x-admin.stat label="Abonnés newsletter" :value="number_format($kpis['subscribers'], 0, ',', ' ')" icon="inbox"
            :hint="$kpis['volunteers'].' bénévole(s) actif(s)'" :href="route('admin.subscribers.index')" />
    </div>

    {{-- ================== Graphiques ================== --}}
    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <x-admin.card class="xl:col-span-2" title="Dons encaissés par mois"
            subtitle="Montants validés sur les douze derniers mois, en {{ setting('currency_symbol', 'FCFA') }}">
            <x-admin.bar-chart :series="$monthly" :unit="setting('currency_symbol', 'FCFA')" table-label="Montant encaissé" />
        </x-admin.card>

        <x-admin.card title="Causes les mieux financées" subtitle="Montant collecté par projet">
            @if (collect($topCauses)->sum('value') > 0)
                <x-admin.ranked-bars :items="$topCauses" :unit="setting('currency_symbol', 'FCFA')" />
            @else
                <x-admin.empty title="Aucun don enregistré" message="Les montants apparaîtront dès le premier don validé." />
            @endif
        </x-admin.card>
    </div>

    {{-- ================== Détails ================== --}}
    <div class="mt-6 grid gap-6 xl:grid-cols-3">

        {{-- Derniers dons --}}
        <x-admin.card class="xl:col-span-2" title="Derniers dons" :padded="false">
            <x-slot:actions>
                <x-admin.button variant="secondary" :href="route('admin.donations.index')">Tout voir</x-admin.button>
            </x-slot:actions>

            @if ($recentDonations->isEmpty())
                <x-admin.empty title="Aucun don pour l'instant" message="Les dons enregistrés depuis le site apparaîtront ici." />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 text-xs uppercase tracking-wide text-zinc-500">
                            <tr>
                                <th class="px-5 py-3 text-left font-medium">Donateur</th>
                                <th class="px-5 py-3 text-left font-medium">Cause</th>
                                <th class="px-5 py-3 text-right font-medium">Montant</th>
                                <th class="px-5 py-3 text-left font-medium">Statut</th>
                                <th class="px-5 py-3 text-left font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach ($recentDonations as $donation)
                                <tr class="hover:bg-zinc-50">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('admin.donations.show', $donation) }}" class="font-medium text-zinc-900 hover:underline">
                                            {{ $donation->display_name }}
                                        </a>
                                        <p class="text-xs text-zinc-500">{{ $donation->reference }}</p>
                                    </td>
                                    <td class="px-5 py-3 text-zinc-600">{{ Str::limit($donation->cause->title ?? 'Fonds libre', 28) }}</td>
                                    <td class="px-5 py-3 text-right font-semibold tabular-nums text-zinc-900">{{ money($donation->amount) }}</td>
                                    <td class="px-5 py-3">
                                        <x-admin.badge :color="['completed' => 'green', 'pending' => 'amber', 'failed' => 'red', 'refunded' => 'zinc'][$donation->status] ?? 'zinc'">
                                            {{ $donation->status_label }}
                                        </x-admin.badge>
                                    </td>
                                    <td class="px-5 py-3 text-zinc-500">{{ $donation->created_at->translatedFormat('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-admin.card>

        <div class="space-y-6">
            {{-- Répartition par statut --}}
            <x-admin.card title="Répartition des dons" subtitle="Par statut de paiement">
                <ul class="space-y-3">
                    @foreach ($statusBreakdown as $row)
                        <li class="flex items-center justify-between gap-3">
                            <x-admin.badge :color="['completed' => 'green', 'pending' => 'amber', 'failed' => 'red', 'refunded' => 'zinc'][$row['status']] ?? 'zinc'">
                                {{ $row['label'] }}
                            </x-admin.badge>
                            <span class="text-sm text-zinc-500">
                                {{ $row['total'] }} don(s)
                                <span class="ml-2 font-semibold tabular-nums text-zinc-900">{{ money($row['amount']) }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </x-admin.card>

            {{-- Prochains événements --}}
            <x-admin.card title="Prochains événements">
                @if ($upcomingEvents->isEmpty())
                    <p class="text-sm text-zinc-500">Aucun événement programmé.</p>
                @else
                    <ul class="space-y-4">
                        @foreach ($upcomingEvents as $event)
                            <li class="flex items-start gap-3">
                                <span class="grid size-11 shrink-0 place-items-center rounded-lg bg-zinc-100 text-center leading-none">
                                    <span class="block text-sm font-bold text-zinc-900">{{ $event->starts_at->format('d') }}</span>
                                    <span class="block text-[10px] uppercase text-zinc-500">{{ $event->starts_at->translatedFormat('M') }}</span>
                                </span>
                                <span class="min-w-0">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="block truncate text-sm font-medium text-zinc-900 hover:underline">
                                        {{ $event->title }}
                                    </a>
                                    <span class="block text-xs text-zinc-500">
                                        {{ $event->registrations_count }} inscription(s)
                                        @if ($event->capacity) / {{ $event->capacity }} places @endif
                                    </span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-admin.card>
        </div>
    </div>

@endsection
