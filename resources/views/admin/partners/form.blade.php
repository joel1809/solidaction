@extends('layouts.admin')

@section('title', $partner->exists ? 'Modifier un partenaire' : 'Nouveau partenaire')

@section('content')

    <x-admin.page-header :title="$partner->exists ? 'Modifier le partenaire' : 'Nouveau partenaire'">
        <x-admin.button variant="secondary" :href="route('admin.partners.index')">Retour à la liste</x-admin.button>
    </x-admin.page-header>

    <form action="{{ $partner->exists ? route('admin.partners.update', $partner) : route('admin.partners.store') }}"
        method="POST" enctype="multipart/form-data" class="max-w-2xl">
        @csrf
        @if ($partner->exists)
            @method('PUT')
        @endif

        <x-admin.card>
            <div class="space-y-5">
                <x-admin.field name="name" label="Nom du partenaire" :model="$partner" required />
                <x-admin.field name="url" label="Site web" :model="$partner" placeholder="https://" />
                <x-admin.image-field name="logo" label="Logo" :current="$partner->logo"
                    help="PNG à fond transparent recommandé, 300 × 120 px." />
                <x-admin.field name="sort_order" label="Ordre d'affichage" type="number" :model="$partner" />
                <x-admin.field name="is_active" type="checkbox" label="Afficher sur le site" :model="$partner" />
            </div>
        </x-admin.card>

        <div class="mt-6 flex items-center gap-3">
            <x-admin.button type="submit" icon="check">{{ $partner->exists ? 'Enregistrer' : 'Créer' }}</x-admin.button>
            <x-admin.button variant="ghost" :href="route('admin.partners.index')">Annuler</x-admin.button>
        </div>
    </form>

@endsection
