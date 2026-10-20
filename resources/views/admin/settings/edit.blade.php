@extends('layouts.admin')

@section('title', 'Paramètres du site')
@section('subtitle', 'Identité, contact, réseaux sociaux et contenus éditoriaux')

@section('content')

    @php
        $groupTitles = [
            'general' => ['Identité du site', 'Nom, accroche et description utilisés partout sur le site.'],
            'contact' => ['Coordonnées', 'Affichées dans le pied de page et sur la page Contact.'],
            'social' => ['Réseaux sociaux', 'Laissez vide pour masquer l\'icône correspondante.'],
            'content' => ['Contenus éditoriaux', 'Textes de présentation de l\'association.'],
            'donation' => ['Dons', 'Montants proposés, coordonnées bancaires et mention fiscale.'],
        ];
    @endphp

    <x-admin.page-header title="Paramètres du site"
        subtitle="Ces valeurs sont utilisées directement par les pages publiques." />

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        @foreach ($schema as $group => $fields)
            <x-admin.card :title="$groupTitles[$group][0] ?? $group" :subtitle="$groupTitles[$group][1] ?? null">
                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach ($fields as $key => $field)
                        <div class="{{ $field['type'] === 'textarea' ? 'sm:col-span-2' : '' }}">
                            <x-admin.field :name="$key" :label="$field['label']" :type="$field['type']"
                                :value="setting($key)" :help="$field['help'] ?? null" rows="4" />
                        </div>
                    @endforeach
                </div>
            </x-admin.card>
        @endforeach

        <x-admin.card title="Logos" subtitle="Le thème utilise quatre déclinaisons. Laissez vide pour garder celles du thème.">
            <div class="grid gap-6 sm:grid-cols-2">
                @foreach ($logos as $key => $label)
                    <x-admin.image-field :name="$key" :label="$label" :current="setting($key)"
                        help="PNG transparent recommandé." />
                @endforeach
            </div>
        </x-admin.card>

        <div class="sticky bottom-4 flex items-center gap-3 rounded-xl border border-zinc-200 bg-white/95 p-4 shadow-lg">
            <x-admin.button type="submit" icon="check">Enregistrer les paramètres</x-admin.button>
            <p class="text-xs text-zinc-500">Les modifications sont visibles immédiatement sur le site public.</p>
        </div>
    </form>

@endsection
