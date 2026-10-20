@extends('layouts.admin')

@section('title', $slider->exists ? 'Modifier une diapositive' : 'Nouvelle diapositive')

@section('content')

    <x-admin.page-header :title="$slider->exists ? 'Modifier la diapositive' : 'Nouvelle diapositive'">
        <x-admin.button variant="secondary" :href="route('admin.sliders.index')">Retour à la liste</x-admin.button>
    </x-admin.page-header>

    <form action="{{ $slider->exists ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}"
        method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($slider->exists)
            @method('PUT')
        @endif

        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Textes">
                <div class="space-y-5">
                    <x-admin.field name="subtitle" label="Surtitre" :model="$slider"
                        placeholder="Ex. : Changer une vie, changer le monde" />
                    <x-admin.field name="title" label="Titre principal" type="textarea" rows="2" :model="$slider" required
                        help="Un retour à la ligne dans ce champ crée un saut de ligne dans le bandeau." />
                    <x-admin.field name="text" label="Texte d'accroche" type="textarea" rows="2" :model="$slider" />
                </div>
            </x-admin.card>

            <x-admin.card title="Boutons">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field name="button_text" label="Bouton principal" :model="$slider" placeholder="Ex. : Notre mission" />
                    <x-admin.field name="button_url" label="Lien du bouton principal" :model="$slider" placeholder="/a-propos" />
                    <x-admin.field name="button2_text" label="Bouton secondaire" :model="$slider" placeholder="Ex. : Faire un don" />
                    <x-admin.field name="button2_url" label="Lien du bouton secondaire" :model="$slider" placeholder="/faire-un-don" />
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Image de fond">
                <x-admin.image-field name="image" label="Visuel plein écran" :current="$slider->image"
                    help="Format large recommandé (1920 × 900 px), 6 Mo maximum." />
            </x-admin.card>

            <x-admin.card title="Affichage">
                <div class="space-y-5">
                    <x-admin.field name="sort_order" label="Ordre d'affichage" type="number" :model="$slider" />
                    <x-admin.field name="is_active" type="checkbox" label="Diapositive active" :model="$slider" />
                </div>
            </x-admin.card>

            <div class="flex items-center gap-3">
                <x-admin.button type="submit" icon="check">{{ $slider->exists ? 'Enregistrer' : 'Créer' }}</x-admin.button>
                <x-admin.button variant="ghost" :href="route('admin.sliders.index')">Annuler</x-admin.button>
            </div>
        </div>
    </form>

@endsection
