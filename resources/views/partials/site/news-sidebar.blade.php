@php($plain = $plain ?? false)
<div class="sidebar-content-box{{ $plain ? ' sidebar-content-box--plain post-sidebar' : '' }}">
    {{-- Recherche --}}
    <div class="single-sidebar-box">
        <div class="single-sidebar_search_box">
            <div class="title">
                <h3>Rechercher</h3>
            </div>
            <div class="sidebar-search-box">
                <form class="search-form" action="{{ route('posts.index') }}" method="get">
                    <input placeholder="Mot-clé" type="text" name="q" value="{{ request('q') }}">
                    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
    </div>

    {{-- Catégories --}}
    @if ($sidebarCategories->isNotEmpty())
        <div class="single-sidebar-box">
            <div class="sidebar-categories">
                <div class="title">
                    <h3>Catégories</h3>
                </div>
                <ul class="sidebar-categories-box">
                    @foreach ($sidebarCategories as $category)
                        <li>
                            <a href="{{ route('posts.index', ['categorie' => $category->slug]) }}">
                                <i class="fa fa-check-circle" aria-hidden="true"></i>{{ $category->name }} ({{ $category->posts_count }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Articles récents --}}
    @if ($sidebarRecent->isNotEmpty())
        <div class="single-sidebar-box">
            <div class="sidebar-campaigns">
                <div class="title">
                    <h3>Articles récents</h3>
                </div>
                <ul class="recent-campaigns recent-news">
                    @foreach ($sidebarRecent as $recent)
                        <li>
                            <div class="inner">
                                <div class="img-box">
                                    <img src="{{ media($recent->image, 'theme/assets/images/sidebar/campaigns-1.jpg') }}" alt="{{ $recent->title }}">
                                    <div class="overlay-content">
                                        <a href="{{ route('posts.show', $recent) }}"><i class="fa fa-link" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                                <div class="title-box">
                                    <h4><a href="{{ route('posts.show', $recent) }}">{{ Str::limit($recent->title, 38) }}</a></h4>
                                    <p>{{ optional($recent->published_at)->translatedFormat('d M Y') }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Appel au don --}}
    @if ($plain)
        <div class="single-sidebar-box">
            <div class="post-sidebar-cta">
                <h3>Soutenez nos actions</h3>
                <p>Un petit don, un grand impact pour l'éducation et la santé de chaque enfant.</p>
                <a class="btn-one" href="{{ route('donations.create') }}">
                    <span class="txt">Je donne</span>
                </a>
            </div>
        </div>
    @else
    <div class="single-sidebar-box">
        <div class="sidebar-slogan-box" style="background-image: url({{ theme_asset('assets/images/sidebar/sidebar-slogan-img.jpg') }});">
            <div class="icon">
                <img src="{{ media(setting('site_logo_footer'), 'theme/assets/images/logo/logo-footer.png') }}" alt="{{ setting('site_name', 'SolidAction') }}" />
            </div>
            <p>Un petit don, un grand impact</p>
            <h2>Éducation &amp; santé<br>pour chaque enfant</h2>
            <div class="btn-box">
                <div class="hand"><img src="{{ theme_asset('assets/images/shape/hand.png') }}" alt=""></div>
                <a class="btn-one" href="{{ route('donations.create') }}">
                    <span class="txt"><i class="arrow1 fa fa-check-circle"></i>je donne</span>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Mots-clés --}}
    @if ($sidebarTags->isNotEmpty())
        <div class="single-sidebar-box">
            <div class="sidebar-tag-box">
                <div class="title">
                    <h3>Mots-clés</h3>
                </div>
                <ul>
                    @foreach ($sidebarTags as $tag)
                        <li><a href="{{ route('posts.index', ['tag' => $tag]) }}">{{ $tag }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
