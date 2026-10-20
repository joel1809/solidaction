@extends('layouts.admin')

@section('title', $event->exists ? 'Modifier un événement' : 'Nouvel événement')
@section('subtitle', $event->exists ? $event->title : 'Programmer un rendez-vous')

@section('content')

    <x-admin.page-header :title="$event->exists ? 'Modifier l\'événement' : 'Nouvel événement'">
        <x-admin.button variant="secondary" :href="route('admin.events.index')">Retour à la liste</x-admin.button>
        @if ($event->exists)
            <x-admin.button variant="secondary" :href="route('admin.registrations.index', ['event' => $event->id])" icon="ticket">
                Inscriptions ({{ $event->registrations_count ?? $event->registrations()->count() }})
            </x-admin.button>
        @endif
    </x-admin.page-header>

    <form action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}"
        method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($event->exists)
            @method('PUT')
        @endif

        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Présentation">
                <div class="space-y-5">
                    <x-admin.field name="title" label="Titre" :model="$event" required slug-target="#field-slug" />
                    <x-admin.field name="slug" label="Identifiant d'URL" :model="$event"
                        help="Généré automatiquement si laissé vide." />
                    <x-admin.field name="excerpt" label="Résumé" type="textarea" rows="3" :model="$event" />
                    <x-admin.field name="description" label="Programme détaillé" type="textarea" rows="14" :model="$event"
                        help="HTML simple accepté." />
                </div>
            </x-admin.card>

            <x-admin.card title="Lieu et horaires">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field name="starts_at" label="Début" type="datetime-local" required
                        :value="old('starts_at', $event->starts_at?->format('Y-m-d\TH:i'))" />
                    <x-admin.field name="ends_at" label="Fin" type="datetime-local"
                        :value="old('ends_at', $event->ends_at?->format('Y-m-d\TH:i'))" />
                    <x-admin.field name="location" label="Lieu" :model="$event" placeholder="Ville, salle" />
                    <x-admin.field name="address" label="Adresse complète" :model="$event" />
                </div>
            </x-admin.card>

            <x-admin.card title="Visuel">
                <x-admin.image-field name="image" label="Image de l'événement" :current="$event->image" />
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Publication">
                <div class="space-y-5">
                    <x-admin.field name="status" label="Statut" type="select" :model="$event" required
                        :options="['draft' => 'Brouillon', 'published' => 'Publié', 'cancelled' => 'Annulé']" />
                    <x-admin.field name="organizer" label="Organisateur" :model="$event" />
                    <x-admin.field name="is_featured" type="checkbox" label="Mettre à la une" :model="$event" />
                </div>
            </x-admin.card>

            <x-admin.card title="Participation">
                <div class="space-y-5">
                    <x-admin.field name="capacity" label="Nombre de places" type="number" min="1" :model="$event"
                        help="Laissez vide pour un accès illimité." />
                    <x-admin.field name="ticket_price" label="Tarif ({{ setting('currency_symbol', 'FCFA') }})"
                        type="number" step="0.01" min="0" :model="$event" help="0 pour un événement gratuit." />

                    @if ($event->exists && $event->capacity)
                        <div class="rounded-lg bg-zinc-50 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-zinc-500">Places réservées</p>
                            <p class="mt-1 text-xl font-semibold text-zinc-900">
                                {{ $event->seats_taken }} <span class="text-sm font-normal text-zinc-500">/ {{ $event->capacity }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            </x-admin.card>

            <div class="flex items-center gap-3">
                <x-admin.button type="submit" icon="check">
                    {{ $event->exists ? 'Enregistrer' : 'Créer l\'événement' }}
                </x-admin.button>
                <x-admin.button variant="ghost" :href="route('admin.events.index')">Annuler</x-admin.button>
            </div>
        </div>
    </form>

@endsection
