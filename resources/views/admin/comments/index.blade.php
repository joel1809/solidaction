@extends('layouts.admin')

@section('title', 'Commentaires')
@section('subtitle', 'Modération des commentaires d\'articles')

@section('content')

    <x-admin.page-header title="Commentaires"
        :subtitle="($counts['pending'] ?? 0).' en attente, '.($counts['approved'] ?? 0).' approuvé(s), '.($counts['spam'] ?? 0).' indésirable(s)'" />

    <x-admin.filters :action="route('admin.comments.index')" placeholder="Auteur ou contenu…" :selects="[
        'status' => ['label' => 'Statut', 'options' => \App\Models\Comment::STATUSES],
    ]" />

    <x-admin.card :padded="false">
        @if ($comments->isEmpty())
            <x-admin.empty title="Aucun commentaire" message="Aucun commentaire ne correspond à ces critères." />
        @else
            <ul class="divide-y divide-zinc-100">
                @foreach ($comments as $comment)
                    <li class="p-5 hover:bg-zinc-50">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-medium text-zinc-900">{{ $comment->name }}</p>
                                    <span class="text-xs text-zinc-500">{{ $comment->email }}</span>
                                    <x-admin.badge :color="['approved' => 'green', 'pending' => 'amber', 'spam' => 'red'][$comment->status]">
                                        {{ $comment->status_label }}
                                    </x-admin.badge>
                                </div>

                                <p class="mt-2 text-sm text-zinc-700">{{ $comment->body }}</p>

                                <p class="mt-2 text-xs text-zinc-500">
                                    {{ $comment->created_at->translatedFormat('d F Y à H\hi') }} &middot;
                                    @if ($comment->post)
                                        sur <a href="{{ route('admin.posts.edit', $comment->post) }}"
                                            class="hover:underline">{{ Str::limit($comment->post->title, 50) }}</a>
                                    @else
                                        article supprimé
                                    @endif
                                </p>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <x-admin.status-form :action="route('admin.comments.update', $comment)"
                                    :options="\App\Models\Comment::STATUSES" :current="$comment->status" />
                                <x-admin.delete-form :action="route('admin.comments.destroy', $comment)"
                                    confirm="Supprimer définitivement ce commentaire ?" compact />
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$comments" label="commentaire" />

@endsection
