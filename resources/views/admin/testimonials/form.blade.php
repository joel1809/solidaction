@extends('layouts.admin')

@section('title', $testimonial->exists ? 'Modifier un témoignage' : 'Nouveau témoignage')

@section('content')

    <x-admin.page-header :title="$testimonial->exists ? 'Modifier le témoignage' : 'Nouveau témoignage'">
        <x-admin.button variant="secondary" :href="route('admin.testimonials.index')">Retour à la liste</x-admin.button>
    </x-admin.page-header>

    <form action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
        method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($testimonial->exists)
            @method('PUT')
        @endif

        <div class="lg:col-span-2">
            <x-admin.card title="Contenu">
                <div class="space-y-5">
                    <x-admin.field name="title" label="Titre du témoignage" :model="$testimonial"
                        placeholder="Ex. : Je sais exactement où va mon don" />
                    <x-admin.field name="content" label="Témoignage" type="textarea" rows="7" :model="$testimonial" required />
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-admin.field name="name" label="Auteur" :model="$testimonial" required />
                        <x-admin.field name="location" label="Qualité / ville" :model="$testimonial"
                            placeholder="Ex. : Donatrice mensuelle, Rennes" />
                    </div>
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Photo">
                <x-admin.image-field name="photo" label="Portrait" :current="$testimonial->photo" />
            </x-admin.card>

            <x-admin.card title="Affichage">
                <div class="space-y-5">
                    <x-admin.field name="rating" label="Note sur 5" type="number" min="1" max="5" :model="$testimonial" />
                    <x-admin.field name="sort_order" label="Ordre d'affichage" type="number" :model="$testimonial" />
                    <x-admin.field name="is_active" type="checkbox" label="Publier le témoignage" :model="$testimonial" />
                </div>
            </x-admin.card>

            <div class="flex items-center gap-3">
                <x-admin.button type="submit" icon="check">{{ $testimonial->exists ? 'Enregistrer' : 'Créer' }}</x-admin.button>
                <x-admin.button variant="ghost" :href="route('admin.testimonials.index')">Annuler</x-admin.button>
            </div>
        </div>
    </form>

@endsection
