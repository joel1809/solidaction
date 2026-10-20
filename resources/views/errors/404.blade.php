@extends('layouts.site')

@section('title', 'Page introuvable')

@section('content')

    <x-site.breadcrumb title="Erreur 404" :items="['Page introuvable']" />

    <section class="error-area" style="padding: 120px 0;">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2 text-center">
                    <h2 style="font-size: 110px; line-height: 1; color: var(--thm-base, #f0564a); margin-bottom: 20px;">404</h2>
                    <h3 style="font-size: 32px; margin-bottom: 16px;">Cette page n'existe pas (ou plus)</h3>
                    <p style="margin-bottom: 34px;">Le lien est peut-être erroné. Vous pouvez revenir à l'accueil ou explorer nos causes en cours.</p>
                    <a class="btn-one" href="{{ route('home') }}">
                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Retour à l'accueil</span>
                    </a>
                    <a class="btn-one marlft15" href="{{ route('causes.index') }}">
                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Nos causes</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
