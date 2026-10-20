@extends('layouts.site')

@section('title', 'Nos causes')
@section('meta_description', 'Découvrez les collectes en cours de SolidAction : eau potable, éducation, santé, urgences et alimentation.')

@section('content')

    <x-site.breadcrumb title="Nos causes" :items="['Nos causes']" image="theme/assets/images/breadcrumb/breadcrumb-2.jpg" />

    <section class="causes-page-one" style="padding: 110px 0 80px;">
        <div class="container">
            <x-site.alert />

            <div class="filter-bar">
                <ul class="filter-links">
                    <li>
                        <a href="{{ route('causes.index') }}" class="{{ request()->missing('categorie') ? 'is-active' : '' }}">
                            Toutes ({{ $categories->sum('causes_count') }})
                        </a>
                    </li>
                    @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('causes.index', ['categorie' => $category->slug]) }}"
                                class="{{ request('categorie') === $category->slug ? 'is-active' : '' }}">
                                {{ $category->name }} ({{ $category->causes_count }})
                            </a>
                        </li>
                    @endforeach
                </ul>

                <form action="{{ route('causes.index') }}" method="get">
                    @if (request('categorie'))
                        <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                    @endif
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher une cause">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <div class="row">
                @forelse ($causes as $cause)
                    <div class="col-xl-4 col-lg-6 col-md-6" style="margin-bottom: 40px;">
                        <x-site.cause-card :cause="$cause" />
                    </div>
                @empty
                    <div class="col-xl-12">
                        <div class="empty-state">
                            <h3>Aucune cause ne correspond à votre recherche</h3>
                            <p>Essayez un autre mot-clé ou parcourez l'ensemble de nos programmes.</p>
                            <a class="btn-one" href="{{ route('causes.index') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Voir toutes les causes</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($causes->hasPages())
                <div class="row">
                    <div class="col-xl-12">
                        {{ $causes->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    <x-site.donate-cta />

@endsection
