@extends('layouts.admin')

@section('title', $post->exists ? 'Modifier un article' : 'Nouvel article')
@section('subtitle', $post->exists ? $post->title : 'Rédiger une actualité')

@section('content')

    <x-admin.page-header :title="$post->exists ? 'Modifier l\'article' : 'Nouvel article'"
        :subtitle="$post->exists ? 'Créé le '.$post->created_at->translatedFormat('d F Y') : null">
        <x-admin.button variant="secondary" :href="route('admin.posts.index')">Retour à la liste</x-admin.button>
        @if ($post->exists && $post->status === 'published')
            <x-admin.button variant="secondary" :href="route('posts.show', $post)" icon="eye">Voir en ligne</x-admin.button>
        @endif
    </x-admin.page-header>

    <form action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
        method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($post->exists)
            @method('PUT')
        @endif

        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Rédaction">
                <div class="space-y-5">
                    <x-admin.field name="title" label="Titre" :model="$post" required slug-target="#field-slug" />
                    <x-admin.field name="slug" label="Identifiant d'URL" :model="$post"
                        help="Généré automatiquement si laissé vide." />
                    <x-admin.field name="excerpt" label="Chapô" type="textarea" rows="3" :model="$post"
                        help="Résumé affiché sur les cartes et dans les métadonnées." />
                    <x-admin.field name="body" label="Contenu" type="textarea" rows="20" :model="$post"
                        help="HTML simple accepté : &lt;p&gt;, &lt;h3&gt;, &lt;ul&gt;, &lt;blockquote&gt;." />
                </div>
            </x-admin.card>

            <x-admin.card title="Image à la une">
                <x-admin.image-field name="image" label="Visuel de l'article" :current="$post->image"
                    help="Format paysage recommandé (1200 × 800 px)." />
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Publication">
                <div class="space-y-5">
                    <x-admin.field name="status" label="Statut" type="select" :model="$post" required
                        :options="['draft' => 'Brouillon', 'published' => 'Publié']" />

                    <x-admin.field name="published_at" label="Date de publication" type="datetime-local"
                        :value="old('published_at', $post->published_at?->format('Y-m-d\TH:i'))"
                        help="Renseignée automatiquement à la première publication." />

                    <x-admin.field name="category_id" label="Catégorie" type="select" :model="$post"
                        :options="$categories->all()" placeholder="Non classé" />

                    <x-admin.field name="tags" label="Mots-clés"
                        :value="old('tags', collect($post->tags ?? [])->implode(', '))"
                        help="Séparés par des virgules." />

                    <x-admin.field name="is_featured" type="checkbox" label="Mettre à la une" :model="$post" />

                    <x-admin.field name="allow_comments" type="checkbox" label="Autoriser les commentaires"
                        :model="$post" help="Les commentaires restent soumis à modération." />
                </div>
            </x-admin.card>

            @if ($post->exists)
                <x-admin.card title="Statistiques">
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-zinc-500">Vues</dt>
                            <dd class="mt-1 text-xl font-semibold text-zinc-900">{{ number_format($post->views, 0, ',', ' ') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-zinc-500">Commentaires</dt>
                            <dd class="mt-1 text-xl font-semibold text-zinc-900">{{ $post->comments()->count() }}</dd>
                        </div>
                    </dl>
                    <a href="{{ route('admin.comments.index') }}" class="mt-3 inline-block text-sm text-zinc-600 hover:underline">
                        Modérer les commentaires →
                    </a>
                </x-admin.card>
            @endif

            <div class="flex items-center gap-3">
                <x-admin.button type="submit" icon="check">
                    {{ $post->exists ? 'Enregistrer' : 'Créer l\'article' }}
                </x-admin.button>
                <x-admin.button variant="ghost" :href="route('admin.posts.index')">Annuler</x-admin.button>
            </div>
        </div>
    </form>

@endsection
