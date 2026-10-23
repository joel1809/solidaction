@extends('layouts.admin')

@section('title', $post->exists ? 'Modifier un article' : 'Nouvel article')
@section('subtitle', $post->exists ? $post->title : 'Rédiger une actualité')

@php
    // post_max_size en octets (ex. « 256M »), pour bloquer un envoi trop lourd avant qu'il ne parte.
    $maxPostSize = (function (string $value): int {
        $number = (int) $value;

        return match (strtoupper(substr(trim($value), -1))) {
            'G' => $number * 1024 ** 3,
            'M' => $number * 1024 ** 2,
            'K' => $number * 1024,
            default => $number,
        };
    })((string) ini_get('post_max_size'));
@endphp

@section('content')

    <x-admin.page-header :title="$post->exists ? 'Modifier l\'article' : 'Nouvel article'"
        :subtitle="$post->exists ? 'Créé le '.$post->created_at->translatedFormat('d F Y') : null">
        <x-admin.button variant="secondary" :href="route('admin.posts.index')">Retour à la liste</x-admin.button>
        @if ($post->exists && $post->status === 'published')
            <x-admin.button variant="secondary" :href="route('posts.show', $post)" icon="eye">Voir en ligne</x-admin.button>
        @endif
    </x-admin.page-header>

    <form action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
        method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3"
        data-max-post-size="{{ $maxPostSize }}" data-max-post-label="{{ ini_get('post_max_size') }}o">
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

            <x-admin.card title="Galerie photos et vidéos">
                <div class="space-y-5">
                    @if ($gallery->isNotEmpty())
                        <ul class="divide-y divide-zinc-100 rounded-lg border border-zinc-200">
                            @foreach ($gallery as $item)
                                <li class="flex items-center gap-4 p-3">
                                    <div class="grid size-16 shrink-0 place-items-center overflow-hidden rounded-lg border border-zinc-200 bg-zinc-50">
                                        @if ($item->thumbnail)
                                            <img src="{{ $item->thumbnail }}" alt="" class="size-full object-cover">
                                        @elseif (! $item->isEmbed())
                                            <video src="{{ $item->src }}#t=0.5" preload="metadata" muted class="size-full object-cover"></video>
                                        @else
                                            <span class="text-xs font-medium text-zinc-500">Vidéo</span>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1 space-y-1.5">
                                        <p class="truncate text-xs text-zinc-500">
                                            {{ $item->isVideo() ? 'Vidéo' : 'Image' }}
                                            @if ($item->isEmbed())
                                                · <a href="{{ $item->url }}" target="_blank" rel="noopener" class="hover:underline">{{ $item->url }}</a>
                                            @endif
                                        </p>
                                        <input type="text" name="gallery[{{ $item->id }}][caption]" placeholder="Légende (facultative)"
                                            value="{{ old('gallery.'.$item->id.'.caption', $item->caption) }}"
                                            class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm transition placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-100">
                                    </div>

                                    <div class="w-20 shrink-0">
                                        <label class="block text-xs text-zinc-500">Ordre</label>
                                        <input type="number" min="0" name="gallery[{{ $item->id }}][sort_order]"
                                            value="{{ old('gallery.'.$item->id.'.sort_order', $item->sort_order) }}"
                                            class="mt-0.5 w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm transition placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-100">
                                    </div>

                                    <label class="inline-flex shrink-0 items-center gap-1.5 text-xs text-zinc-600">
                                        <input type="checkbox" name="gallery[{{ $item->id }}][remove]" value="1"
                                            class="size-3.5 rounded border-zinc-300 text-rose-500 focus:ring-rose-200">
                                        Retirer
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="space-y-2">
                        <label for="field-gallery-files" class="block text-sm font-medium text-zinc-700">Ajouter des photos ou des vidéos</label>
                        <input type="file" name="gallery_files[]" id="field-gallery-files" multiple
                            accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime"
                            class="block w-full cursor-pointer rounded-lg border border-zinc-200 bg-white text-sm text-zinc-600 shadow-sm file:mr-3 file:cursor-pointer file:border-0 file:bg-zinc-100 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                        <p class="text-xs text-zinc-500">Sélection multiple possible. Images JPG, PNG, WebP ou GIF ; vidéos MP4, WebM ou MOV — 40 Mo maximum par fichier, {{ ini_get('post_max_size') }}o au total par envoi.</p>
                        <p id="gallery-size-error" class="hidden text-xs font-medium text-rose-600"></p>
                        @error('gallery_files')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                        @foreach ($errors->get('gallery_files.*') as $messages)
                            <p class="text-xs font-medium text-rose-600">{{ $messages[0] }}</p>
                        @endforeach
                    </div>

                    <x-admin.field name="gallery_videos" label="Vidéos YouTube ou Vimeo" type="textarea" rows="3"
                        :value="old('gallery_videos')"
                        help="Un lien par ligne, par exemple https://www.youtube.com/watch?v=… — ajoutés à la suite de la galerie." />
                </div>
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

@push('scripts')
    <script>
        (() => {
            const form = document.querySelector('form[data-max-post-size]');
            const error = document.getElementById('gallery-size-error');
            if (!form || !error) return;

            const maxTotal = Number(form.dataset.maxPostSize);
            const maxFile = 40 * 1024 * 1024;
            const toMo = (bytes) => (bytes / 1024 / 1024).toFixed(1).replace('.', ',') + ' Mo';

            const check = () => {
                const files = [...form.querySelectorAll('input[type=file]')].flatMap((input) => [...input.files]);
                const total = files.reduce((sum, file) => sum + file.size, 0);
                const tooBig = files.filter((file) => file.size > maxFile);
                let message = '';

                if (tooBig.length) {
                    message = 'Fichier(s) de plus de 40 Mo : ' + tooBig.map((file) => file.name + ' (' + toMo(file.size) + ')').join(', ') + '.';
                } else if (maxTotal && total > maxTotal) {
                    message = 'La sélection pèse ' + toMo(total) + ', au-delà de la limite de ' + form.dataset.maxPostLabel
                        + ' par envoi. Ajoutez moins de fichiers à la fois.';
                }

                error.textContent = message;
                error.classList.toggle('hidden', message === '');

                return message === '';
            };

            form.addEventListener('change', (event) => {
                if (event.target.type === 'file') check();
            });

            form.addEventListener('submit', (event) => {
                if (!check()) {
                    event.preventDefault();
                    error.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        })();
    </script>
@endpush
