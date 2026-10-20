@extends('layouts.admin')

@section('title', 'Événements')
@section('subtitle', 'Courses, dîners, webinaires et formations')

@section('content')

    <x-admin.page-header title="Événements" :subtitle="$events->total().' événement(s)'">
        <x-admin.button variant="secondary" :href="route('admin.registrations.index')" icon="ticket">Inscriptions</x-admin.button>
        <x-admin.button icon="plus" :href="route('admin.events.create')">Nouvel événement</x-admin.button>
    </x-admin.page-header>

    <x-admin.filters :action="route('admin.events.index')" placeholder="Titre de l'événement…" :selects="[
        'status' => ['label' => 'Statut', 'options' => ['draft' => 'Brouillon', 'published' => 'Publié', 'cancelled' => 'Annulé']],
        'periode' => ['label' => 'Période', 'options' => ['upcoming' => 'À venir', 'past' => 'Passés']],
    ]" />

    <x-admin.card :padded="false">
        @if ($events->isEmpty())
            <x-admin.empty title="Aucun événement" message="Programmez un rendez-vous pour mobiliser vos donateurs.">
                <x-admin.button icon="plus" :href="route('admin.events.create')">Nouvel événement</x-admin.button>
            </x-admin.empty>
        @else
            <x-admin.table :head="['Événement', 'Date', 'Lieu', 'Inscriptions' => 'center', 'Tarif' => 'right', 'Statut', 'Actions' => 'right']">
                @foreach ($events as $event)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <span class="grid size-11 shrink-0 place-items-center rounded-lg bg-zinc-100 text-center leading-none">
                                    <span class="block text-sm font-bold text-zinc-900">{{ $event->starts_at->format('d') }}</span>
                                    <span class="block text-[10px] uppercase text-zinc-500">{{ $event->starts_at->translatedFormat('M') }}</span>
                                </span>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.events.edit', $event) }}"
                                        class="block truncate font-medium text-zinc-900 hover:underline">{{ $event->title }}</a>
                                    <p class="text-xs text-zinc-500">
                                        {{ $event->is_past ? 'Terminé' : 'À venir' }}
                                        @if ($event->is_featured) &middot; <span class="font-medium text-rose-600">à la une</span> @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-zinc-600">
                            {{ $event->starts_at->translatedFormat('d/m/Y') }}
                            <span class="block text-xs text-zinc-500">{{ $event->starts_at->format('H\hi') }}</span>
                        </td>
                        <td class="px-5 py-3 text-zinc-600">{{ Str::limit($event->location, 24) }}</td>
                        <td class="px-5 py-3 text-center tabular-nums text-zinc-600">
                            {{ $event->registrations_count }}
                            @if ($event->capacity)
                                <span class="text-xs text-zinc-400">/ {{ $event->capacity }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right text-zinc-600">
                            {{ $event->ticket_price > 0 ? money($event->ticket_price) : 'Gratuit' }}
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.badge :color="['published' => 'green', 'draft' => 'amber', 'cancelled' => 'red'][$event->status]">
                                {{ ['published' => 'Publié', 'draft' => 'Brouillon', 'cancelled' => 'Annulé'][$event->status] }}
                            </x-admin.badge>
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.row-actions
                                :preview="$event->status === 'published' ? route('events.show', $event) : null"
                                :edit="route('admin.events.edit', $event)"
                                :destroy="route('admin.events.destroy', $event)"
                                confirm="Supprimer « {{ $event->title }} » et ses {{ $event->registrations_count }} inscription(s) ?" />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$events" label="événement" />

@endsection
