@extends('layouts.admin')

@section('title', 'Actualités')
@section('subtitle', 'Articles, reportages et communiqués')

@section('content')

    <x-admin.page-header title="Actualités" :subtitle="$posts->total().' article(s)'">
        <x-admin.button icon="plus" :href="route('admin.posts.create')">Nouvel article</x-admin.button>
    </x-admin.page-header>

    <x-admin.filters :action="route('admin.posts.index')" placeholder="Titre de l'article…" :selects="[
        'status' => ['label' => 'Statut', 'options' => ['draft' => 'Brouillon', 'published' => 'Publié']],
        'category' => ['label' => 'Catégorie', 'options' => $categories->all()],
    ]" />

    <x-admin.card :padded="false">
        @if ($posts->isEmpty())
            <x-admin.empty title="Aucun article" message="Publiez votre premier article pour alimenter la page Actualités.">
                <x-admin.button icon="plus" :href="route('admin.posts.create')">Nouvel article</x-admin.button>
            </x-admin.empty>
        @else
            <x-admin.table :head="['Article', 'Catégorie', 'Auteur', 'Commentaires' => 'center', 'Vues' => 'center', 'Statut', 'Actions' => 'right']">
                @foreach ($posts as $post)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ media($post->image, 'theme/assets/images/blog/blog-v1-1.jpg') }}" alt=""
                                    class="size-11 shrink-0 rounded-lg object-cover">
                                <div class="min-w-0">
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                        class="block truncate font-medium text-zinc-900 hover:underline">{{ $post->title }}</a>
                                    <p class="text-xs text-zinc-500">
                                        {{ $post->published_at?->translatedFormat('d M Y') ?? 'Non publié' }}
                                        @if ($post->is_featured) &middot; <span class="font-medium text-rose-600">à la une</span> @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-zinc-600">{{ $post->category->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-zinc-600">{{ $post->author->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-center tabular-nums text-zinc-600">{{ $post->comments_count }}</td>
                        <td class="px-5 py-3 text-center tabular-nums text-zinc-600">{{ number_format($post->views, 0, ',', ' ') }}</td>
                        <td class="px-5 py-3">
                            <x-admin.badge :color="$post->status === 'published' ? 'green' : 'amber'">
                                {{ $post->status === 'published' ? 'Publié' : 'Brouillon' }}
                            </x-admin.badge>
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.row-actions
                                :preview="$post->status === 'published' ? route('posts.show', $post) : null"
                                :edit="route('admin.posts.edit', $post)"
                                :destroy="route('admin.posts.destroy', $post)"
                                confirm="Supprimer l'article « {{ $post->title }} » et ses commentaires ?" />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$posts" label="article" />

@endsection
