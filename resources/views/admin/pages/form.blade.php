@extends('layouts.admin')

@section('title', $page->exists ? 'Modifier une page' : 'Nouvelle page')

@section('content')

    <x-admin.page-header :title="$page->exists ? 'Modifier la page' : 'Nouvelle page'"
        :subtitle="$page->exists ? url('/page/'.$page->slug) : null">
        <x-admin.button variant="secondary" :href="route('admin.pages.index')">Retour à la liste</x-admin.button>
        @if ($page->exists && $page->is_active)
            <x-admin.button variant="secondary" :href="route('pages.show', $page)" icon="eye">Voir en ligne</x-admin.button>
        @endif
    </x-admin.page-header>

    <form action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
        method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($page->exists)
            @method('PUT')
        @endif

        <div class="lg:col-span-2">
            <x-admin.card title="Contenu">
                <div class="space-y-5">
                    <x-admin.field name="title" label="Titre" :model="$page" required slug-target="#field-slug" />
                    <x-admin.field name="slug" label="Identifiant d'URL" :model="$page"
                        help="L'adresse finale sera /page/identifiant." />
                    <x-admin.field name="content" label="Contenu" type="textarea" rows="20" :model="$page"
                        help="HTML simple accepté : paragraphes, titres, listes, gras." />
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Référencement">
                <div class="space-y-5">
                    <x-admin.field name="meta_description" label="Description (SEO)" type="textarea" rows="3" :model="$page"
                        help="160 caractères environ, affichés dans les résultats de recherche." />
                    <x-admin.field name="is_active" type="checkbox" label="Page en ligne" :model="$page" />
                </div>
            </x-admin.card>

            <x-admin.card title="Bandeau">
                <x-admin.image-field name="image" label="Image d'en-tête" :current="$page->image"
                    help="Optionnelle : un visuel par défaut est utilisé sinon." />
            </x-admin.card>

            <div class="flex items-center gap-3">
                <x-admin.button type="submit" icon="check">{{ $page->exists ? 'Enregistrer' : 'Créer' }}</x-admin.button>
                <x-admin.button variant="ghost" :href="route('admin.pages.index')">Annuler</x-admin.button>
            </div>
        </div>
    </form>

@endsection
