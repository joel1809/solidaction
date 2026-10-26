@extends('layouts.site')

@section('title', 'Actualités')
@section('meta_description', 'Reportages de terrain, vie de l\'association et conseils aux donateurs : toute l\'actualité de Solid’Action.')

@section('content')

    <x-site.breadcrumb title="Actualités" :items="['Actualités']" image="theme/assets/images/breadcrumb/breadcrumb-6.jpg" />

    <section class="blog-page-one" style="padding: 110px 0 80px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <x-site.alert />

                    <div class="filter-bar">
                        <ul class="filter-links">
                            <li><a href="{{ route('posts.index', array_merge(request()->except(['vue', 'page']), ['vue' => 'grille'])) }}"
                                    class="{{ $layout === 'grid' ? 'is-active' : '' }}"><i class="fa fa-th"></i> Grille</a></li>
                            <li><a href="{{ route('posts.index', array_merge(request()->except(['vue', 'page']), ['vue' => 'liste'])) }}"
                                    class="{{ $layout === 'list' ? 'is-active' : '' }}"><i class="fa fa-list"></i> Liste</a></li>
                        </ul>
                        <p style="margin: 0; color: #777;">{{ $posts->total() }} article(s)</p>
                    </div>

                    <div class="row">
                        @forelse ($posts as $post)
                            @if ($layout === 'grid')
                                <div class="col-xl-6 col-lg-6" style="margin-bottom: 40px;">
                                    <x-site.post-card :post="$post" />
                                </div>
                            @else
                                <div class="col-xl-12" style="margin-bottom: 40px;">
                                    <div class="single-blog-style1" style="display: flex; flex-wrap: wrap; gap: 26px; align-items: center;">
                                        <div class="img-holder" style="flex: 0 0 280px; max-width: 100%;">
                                            <div class="inner">
                                                <img src="{{ media($post->image, 'theme/assets/images/blog/blog-v1-1.jpg') }}" alt="{{ $post->title }}">
                                                <div class="overlay-icon">
                                                    <a href="{{ route('posts.show', $post) }}"><span class="flaticon-plus"></span></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-holder" style="flex: 1 1 320px;">
                                            @if ($post->category)
                                                <p style="color: var(--thm-primary); font-weight: 700; margin-bottom: 8px;">
                                                    {{ $post->category->name }}
                                                </p>
                                            @endif
                                            <h3 class="blog-title">
                                                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                                            </h3>
                                            <div class="text">
                                                <p>{{ Str::limit(strip_tags($post->excerpt ?: $post->body), 180) }}</p>
                                            </div>
                                            <ul class="meta-info">
                                                <li><i class="fa fa-calendar"></i> {{ optional($post->published_at)->translatedFormat('d M Y') }}</li>
                                                <li><i class="fa fa-user"></i> {{ $post->author->name ?? setting('site_name') }}</li>
                                                <li><i class="fa fa-comment-o"></i> {{ $post->approved_comments_count }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-xl-12">
                                <div class="empty-state">
                                    <h3>Aucun article trouvé</h3>
                                    <p>Essayez un autre mot-clé ou revenez à la liste complète.</p>
                                    <a class="btn-one" href="{{ route('posts.index') }}">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Toutes les actualités</span>
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if ($posts->hasPages())
                        <div class="row">
                            <div class="col-xl-12">{{ $posts->links() }}</div>
                        </div>
                    @endif
                </div>

                <div class="col-xl-4">
                    @include('partials.site.news-sidebar', ['plain' => true])
                </div>
            </div>
        </div>
    </section>

@endsection
