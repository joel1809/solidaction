@extends('layouts.admin')

@section('title', $faq->exists ? 'Modifier une question' : 'Nouvelle question')

@section('content')

    <x-admin.page-header :title="$faq->exists ? 'Modifier la question' : 'Nouvelle question'">
        <x-admin.button variant="secondary" :href="route('admin.faqs.index')">Retour à la liste</x-admin.button>
    </x-admin.page-header>

    <form action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}"
        method="POST" class="max-w-2xl">
        @csrf
        @if ($faq->exists)
            @method('PUT')
        @endif

        <x-admin.card>
            <div class="space-y-5">
                <x-admin.field name="question" label="Question" :model="$faq" required />
                <x-admin.field name="answer" label="Réponse" type="textarea" rows="6" :model="$faq" required />
                <x-admin.field name="category" label="Groupe" :model="$faq"
                    help="Les questions sont regroupées par ce libellé sur la page FAQ (ex. : Dons, Bénévolat)." />
                <x-admin.field name="sort_order" label="Ordre d'affichage" type="number" :model="$faq" />
                <x-admin.field name="is_active" type="checkbox" label="Question publiée" :model="$faq" />
            </div>
        </x-admin.card>

        <div class="mt-6 flex items-center gap-3">
            <x-admin.button type="submit" icon="check">{{ $faq->exists ? 'Enregistrer' : 'Créer' }}</x-admin.button>
            <x-admin.button variant="ghost" :href="route('admin.faqs.index')">Annuler</x-admin.button>
        </div>
    </form>

@endsection
