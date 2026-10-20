@extends('layouts.admin')

@section('title', $cause->exists ? 'Modifier une cause' : 'Nouvelle cause')
@section('subtitle', $cause->exists ? $cause->title : 'Créer un projet de collecte')

@section('content')

    <x-admin.page-header :title="$cause->exists ? 'Modifier la cause' : 'Nouvelle cause'"
        :subtitle="$cause->exists ? 'Dernière modification le '.$cause->updated_at->translatedFormat('d F Y à H\hi') : 'Les champs marqués d\'une étoile sont obligatoires.'">
        <x-admin.button variant="secondary" :href="route('admin.causes.index')">Retour à la liste</x-admin.button>
        @if ($cause->exists)
            <x-admin.button variant="secondary" :href="route('causes.show', $cause)" icon="eye">Voir en ligne</x-admin.button>
        @endif
    </x-admin.page-header>

    <form action="{{ $cause->exists ? route('admin.causes.update', $cause) : route('admin.causes.store') }}"
        method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($cause->exists)
            @method('PUT')
        @endif

        {{-- ============ Contenu ============ --}}
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Présentation">
                <div class="space-y-5">
                    <x-admin.field name="title" label="Titre du projet" :model="$cause" required
                        slug-target="#field-slug" placeholder="Ex. : Un forage pour le village de Boundiali" />

                    <x-admin.field name="slug" label="Identifiant d'URL" :model="$cause"
                        help="Laissez vide pour le générer automatiquement à partir du titre." />

                    <x-admin.field name="excerpt" label="Résumé" type="textarea" rows="3" :model="$cause"
                        help="Affiché sur les cartes et dans les résultats de recherche (600 caractères max.)." />

                    <x-admin.field name="description" label="Description complète" type="textarea" rows="16" :model="$cause"
                        help="Le HTML simple est accepté : paragraphes, titres, listes, citations." />
                </div>
            </x-admin.card>

            <x-admin.card title="Visuel">
                <x-admin.image-field name="image" label="Image principale" :current="$cause->image"
                    help="Format paysage recommandé (1200 × 800 px). JPG, PNG ou WebP, 4 Mo maximum." />
            </x-admin.card>
        </div>

        {{-- ============ Paramètres ============ --}}
        <div class="space-y-6">
            <x-admin.card title="Publication">
                <div class="space-y-5">
                    <x-admin.field name="status" label="Statut" type="select" :model="$cause" required
                        :options="['draft' => 'Brouillon', 'published' => 'Publiée', 'closed' => 'Clôturée']" />

                    <x-admin.field name="category_id" label="Programme" type="select" :model="$cause"
                        :options="$categories->all()" placeholder="Non classé" />

                    <x-admin.field name="sort_order" label="Ordre d'affichage" type="number" :model="$cause"
                        help="Les plus petits nombres apparaissent en premier." />

                    <x-admin.field name="is_featured" type="checkbox" label="Mettre à la une"
                        :model="$cause" help="Affichée en grand sur la page d'accueil." />
                </div>
            </x-admin.card>

            <x-admin.card title="Collecte">
                <div class="space-y-5">
                    <x-admin.field name="goal_amount" label="Objectif ({{ setting('currency_symbol', 'FCFA') }})"
                        type="number" step="0.01" min="0" :model="$cause" required />

                    @if ($cause->exists)
                        <div class="rounded-lg bg-zinc-50 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-zinc-500">Déjà collecté</p>
                            <p class="mt-1 text-xl font-semibold text-zinc-900">{{ money($cause->raised_amount) }}</p>
                            <p class="mt-1 text-xs text-zinc-500">
                                Calculé automatiquement à partir des dons validés ({{ $cause->progress }} % de l'objectif).
                            </p>
                        </div>
                    @endif

                    <x-admin.field name="deadline" label="Échéance" type="date"
                        :value="old('deadline', $cause->deadline?->toDateString())" />

                    <x-admin.field name="location" label="Lieu d'intervention" :model="$cause"
                        placeholder="Ville, pays" />

                    <x-admin.field name="video_url" label="Vidéo du projet" :model="$cause"
                        placeholder="https://www.youtube.com/watch?v=…" />
                </div>
            </x-admin.card>

            <div class="flex items-center gap-3">
                <x-admin.button type="submit" icon="check">
                    {{ $cause->exists ? 'Enregistrer' : 'Créer la cause' }}
                </x-admin.button>
                <x-admin.button variant="ghost" :href="route('admin.causes.index')">Annuler</x-admin.button>
            </div>
        </div>
    </form>

@endsection
