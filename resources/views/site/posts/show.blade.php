@extends('layouts.site')

@section('title', $post->title)
@section('meta_description', Str::limit(strip_tags($post->excerpt), 155))

@section('content')

    <x-site.breadcrumb :title="$post->title" :items="['Actualités' => route('posts.index'), Str::limit($post->title, 30)]"
        image="theme/assets/images/breadcrumb/breadcrumb-6.jpg" />

    <section class="blog-details-area" style="padding: 110px 0 80px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <x-site.alert />

                    <div class="blog-details-image" style="margin-bottom: 34px;">
                        <img src="{{ media($post->image, 'theme/assets/images/blog/blog-details-1.jpg') }}"
                            alt="{{ $post->title }}" style="width: 100%; border-radius: 6px;">
                    </div>

                    <ul class="meta-info post-meta">
                        <li><i class="fa fa-calendar"></i> {{ optional($post->published_at)->translatedFormat('d F Y') }}</li>
                        <li><i class="fa fa-user"></i> {{ $post->author->name ?? setting('site_name') }}</li>
                        @if ($post->category)
                            <li><i class="fa fa-folder-o"></i>
                                <a href="{{ route('posts.index', ['categorie' => $post->category->slug]) }}">{{ $post->category->name }}</a>
                            </li>
                        @endif
                        <li><i class="fa fa-clock-o"></i> {{ $post->reading_time }} min de lecture</li>
                        <li><i class="fa fa-eye"></i> {{ number_format($post->views, 0, ',', ' ') }} vues</li>
                    </ul>

                    <h2 style="font-size: 36px; line-height: 46px; margin-bottom: 26px;">{{ $post->title }}</h2>

                    <div class="content-prose">
                        {!! $post->body !!}
                    </div>

                    @if ($post->gallery->isNotEmpty())
                        {{-- Galerie photos et vidéos --}}
                        <div class="post-gallery">
                            <h3>Galerie</h3>
                            <div class="post-gallery-grid">
                                @foreach ($post->gallery as $item)
                                    @php
                                        $popup = match (true) {
                                            ! $item->isVideo() => 'mfp-image',
                                            $item->isEmbed() => 'mfp-iframe',
                                            default => 'mfp-inline',
                                        };
                                    @endphp
                                    <figure class="post-gallery-item">
                                        <a href="{{ $popup === 'mfp-inline' ? '#post-media-'.$item->id : $item->src }}"
                                            class="{{ $popup }}" title="{{ $item->caption }}">
                                            @if ($item->thumbnail)
                                                <img src="{{ $item->thumbnail }}" alt="{{ $item->caption ?: $post->title }}" loading="lazy">
                                            @elseif (! $item->isEmbed())
                                                <video src="{{ $item->src }}#t=0.5" preload="metadata" muted playsinline></video>
                                            @else
                                                <span class="post-gallery-placeholder"></span>
                                            @endif
                                            @if ($item->isVideo())
                                                <span class="post-gallery-play"><i class="fa fa-play"></i></span>
                                            @endif
                                        </a>
                                        @if ($item->caption)
                                            <figcaption>{{ $item->caption }}</figcaption>
                                        @endif
                                    </figure>

                                    @if ($popup === 'mfp-inline')
                                        <div id="post-media-{{ $item->id }}" class="post-gallery-video mfp-hide">
                                            <video src="{{ $item->src }}" controls playsinline preload="none"></video>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (filled($post->tags))
                        <div class="sidebar-tag-box" style="margin: 36px 0;">
                            <ul>
                                @foreach ($post->tags as $tag)
                                    <li><a href="{{ route('posts.index', ['tag' => $tag]) }}">{{ $tag }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Navigation entre articles --}}
                    <div class="filter-bar" style="margin: 40px 0;">
                        <div>
                            @if ($previous)
                                <a href="{{ route('posts.show', $previous) }}" style="font-weight: 600;">
                                    <i class="fa fa-angle-left"></i> {{ Str::limit($previous->title, 34) }}
                                </a>
                            @endif
                        </div>
                        <div style="text-align: right;">
                            @if ($next)
                                <a href="{{ route('posts.show', $next) }}" style="font-weight: 600;">
                                    {{ Str::limit($next->title, 34) }} <i class="fa fa-angle-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Commentaires --}}
                    <div class="comments-area">
                        <h3 style="font-size: 28px; margin-bottom: 24px;">
                            {{ $post->approvedComments->count() }} commentaire(s)
                        </h3>

                        @if ($post->approvedComments->isNotEmpty())
                            <ul class="comment-list">
                                @foreach ($post->approvedComments as $comment)
                                    <li>
                                        <div class="comment-head">
                                            <h4>
                                                @if ($comment->website)
                                                    <a href="{{ $comment->website }}" target="_blank" rel="noopener nofollow">{{ $comment->name }}</a>
                                                @else
                                                    {{ $comment->name }}
                                                @endif
                                            </h4>
                                            <span>{{ $comment->created_at->translatedFormat('d F Y à H\hi') }}</span>
                                        </div>
                                        <p>{{ $comment->body }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if ($post->allow_comments)
                            <h3 style="font-size: 28px; margin-bottom: 24px;">Laisser un commentaire</h3>
                            <form action="{{ route('posts.comment', $post) }}" method="post" class="app-form">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-row">
                                            <label for="name">Nom <span class="required">*</span></label>
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                                            @error('name') <span class="field-error">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-row">
                                            <label for="email">E-mail <span class="required">*</span></label>
                                            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                                            <span class="field-help">Votre e-mail n'est jamais publié.</span>
                                            @error('email') <span class="field-error">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label for="website">Site web</label>
                                    <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://">
                                    @error('website') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-row">
                                    <label for="body">Commentaire <span class="required">*</span></label>
                                    <textarea name="body" id="body" required>{{ old('body') }}</textarea>
                                    @error('body') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                                <div class="button-box">
                                    <button class="btn-one" type="submit">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Publier</span>
                                    </button>
                                </div>
                            </form>
                        @else
                            <p>Les commentaires sont fermés pour cet article.</p>
                        @endif
                    </div>
                </div>

                <div class="col-xl-4">
                    @include('partials.site.news-sidebar', ['plain' => true])
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $('.post-gallery-grid').magnificPopup({
            delegate: '.post-gallery-item > a',
            type: 'image',
            gallery: { enabled: true, tCounter: '%curr% / %total%' },
            image: { titleSrc: 'title' },
            tClose: 'Fermer (Échap)',
            tLoading: 'Chargement…',
            callbacks: {
                // Lecture automatique des vidéos envoyées, arrêtées à la fermeture.
                change: function () {
                    $('.post-gallery-video video').each(function () { this.pause(); });
                    if (this.currItem.type === 'inline') {
                        var video = this.currItem.inlineElement.find('video').get(0);
                        if (video) { video.play().catch(function () {}); }
                    }
                },
                close: function () {
                    $('.post-gallery-video video').each(function () { this.pause(); });
                }
            }
        });
    </script>
@endpush
