@extends('layouts.admin')

@section('title', $category->exists ? 'Modifier une catégorie' : 'Nouvelle catégorie')

@section('content')

    <x-admin.page-header :title="$category->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie'"
        :subtitle="$category->exists ? $category->name : null">
        <x-admin.button variant="secondary" :href="route('admin.categories.index')">Retour à la liste</x-admin.button>
    </x-admin.page-header>

    <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
        method="POST" class="max-w-2xl">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <x-admin.card>
            <div class="space-y-5">
                <x-admin.field name="name" label="Nom" :model="$category" required slug-target="#field-slug" />

                <x-admin.field name="slug" label="Identifiant d'URL" :model="$category"
                    help="Généré automatiquement si laissé vide." />

                <x-admin.field name="type" label="Type" type="select" :model="$category" required
                    :options="['cause' => 'Programme (classe les causes)', 'post' => 'Rubrique (classe les actualités)']" />

                <x-admin.field name="description" label="Description" type="textarea" rows="3" :model="$category" />

                <x-admin.field name="sort_order" label="Ordre d'affichage" type="number" :model="$category" />

                <x-admin.field name="is_active" type="checkbox" label="Catégorie active"
                    :model="$category" help="Les catégories masquées n'apparaissent pas dans les filtres du site." />
            </div>
        </x-admin.card>

        <div class="mt-6 flex items-center gap-3">
            <x-admin.button type="submit" icon="check">
                {{ $category->exists ? 'Enregistrer' : 'Créer la catégorie' }}
            </x-admin.button>
            <x-admin.button variant="ghost" :href="route('admin.categories.index')">Annuler</x-admin.button>
        </div>
    </form>

@endsection
