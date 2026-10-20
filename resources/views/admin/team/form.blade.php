@extends('layouts.admin')

@section('title', $member->exists ? 'Modifier un membre' : 'Ajouter un membre')

@section('content')

    <x-admin.page-header :title="$member->exists ? 'Modifier la fiche' : 'Ajouter un membre'"
        :subtitle="$member->exists ? $member->name : 'Les membres visibles apparaissent sur la page Notre équipe.'">
        <x-admin.button variant="secondary" :href="route('admin.team.index')">Retour à la liste</x-admin.button>
    </x-admin.page-header>

    <form action="{{ $member->exists ? route('admin.team.update', $member) : route('admin.team.store') }}"
        method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($member->exists)
            @method('PUT')
        @endif

        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Identité">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field name="name" label="Nom et prénom" :model="$member" required />
                    <x-admin.field name="role" label="Fonction" :model="$member" placeholder="Ex. : Responsable des programmes" />
                    <x-admin.field name="email" label="E-mail" type="email" :model="$member" />
                    <x-admin.field name="phone" label="Téléphone" :model="$member" />
                    <div class="sm:col-span-2">
                        <x-admin.field name="bio" label="Présentation" type="textarea" rows="4" :model="$member" />
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Réseaux sociaux">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field name="twitter" label="Twitter / X" :model="$member" placeholder="https://" />
                    <x-admin.field name="facebook" label="Facebook" :model="$member" placeholder="https://" />
                    <x-admin.field name="instagram" label="Instagram" :model="$member" placeholder="https://" />
                    <x-admin.field name="linkedin" label="LinkedIn" :model="$member" placeholder="https://" />
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Photo">
                <x-admin.image-field name="photo" label="Portrait" :current="$member->photo"
                    help="Format portrait recommandé (800 × 1000 px)." />
            </x-admin.card>

            <x-admin.card title="Affichage">
                <div class="space-y-5">
                    <x-admin.field name="sort_order" label="Ordre d'affichage" type="number" :model="$member" />
                    <x-admin.field name="is_active" type="checkbox" label="Afficher sur le site" :model="$member" />
                </div>
            </x-admin.card>

            <div class="flex items-center gap-3">
                <x-admin.button type="submit" icon="check">{{ $member->exists ? 'Enregistrer' : 'Ajouter' }}</x-admin.button>
                <x-admin.button variant="ghost" :href="route('admin.team.index')">Annuler</x-admin.button>
            </div>
        </div>
    </form>

@endsection
