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

            @php
                $focusX = old('focus_x', $slider->focus_x);
                $focusY = old('focus_y', $slider->focus_y);
                $focusImage = media($slider->image, 'theme/assets/images/slides/slide-v1-1.jpg');
            @endphp
            <x-admin.card title="Point focal" subtitle="Cliquez sur la partie de l'image à garder visible, quel que soit l'écran.">
                <div data-focus-picker data-focus-source="#field-image" class="space-y-4">
                    <input type="hidden" name="focus_x" value="{{ $focusX }}" data-focus-x>
                    <input type="hidden" name="focus_y" value="{{ $focusY }}" data-focus-y>

                    <div data-focus-area class="relative cursor-crosshair overflow-hidden rounded-lg border border-zinc-200 bg-zinc-50">
                        <img src="{{ $focusImage }}" alt="" data-focus-image class="block w-full select-none" draggable="false">
                        <span data-focus-marker
                            class="pointer-events-none absolute size-8 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white bg-rose-500/40 shadow-[0_0_0_2px_rgba(0,0,0,0.35)] {{ $focusX === null ? 'hidden' : '' }}"
                            style="left: {{ $focusX ?? 50 }}%; top: {{ $focusY ?? 50 }}%"></span>
                    </div>

                    <div class="grid grid-cols-[1fr_auto] items-end gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-zinc-600">Aperçu ordinateur</p>
                            <div data-focus-preview class="aspect-[1920/900] rounded-md border border-zinc-200 bg-cover bg-no-repeat"
                                style="background-image: url('{{ $focusImage }}'); background-position: {{ $slider->focusPosition() ?? 'center bottom' }}"></div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-zinc-600">Mobile</p>
                            <div data-focus-preview class="h-28 aspect-[9/16] rounded-md border border-zinc-200 bg-cover bg-no-repeat"
                                style="background-image: url('{{ $focusImage }}'); background-position: {{ $slider->focusPosition() ?? 'center bottom' }}"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <p data-focus-label class="text-xs text-zinc-500">
                            {{ $focusX === null ? 'Aucun point focal : cadrage par défaut (centre bas).' : "Point focal : {$focusX} % horizontal, {$focusY} % vertical." }}
                        </p>
                        <x-admin.button variant="ghost" type="button" data-focus-reset>Réinitialiser</x-admin.button>
                    </div>

                    @error('focus_x')
                        <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
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
