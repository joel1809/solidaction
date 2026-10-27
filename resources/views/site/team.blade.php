@extends('layouts.site')

@section('title', 'Notre équipe')
@section('meta_description', 'Les salariés et responsables de programme qui pilotent les projets de Solid’Action au quotidien.')

@section('content')

    <x-site.breadcrumb title="Notre équipe" :items="['Association' => route('about'), 'Notre équipe']"
        image="theme/assets/images/breadcrumb/breadcrumb-7.jpg" />

    <section class="team-page-one" style="padding: 110px 0 80px;">
        <div class="container">
            <x-site.sec-title subtitle="Celles et ceux qui agissent" title="Une équipe resserrée, des partenaires nombreux">
                Nous restons volontairement une petite structure : l'essentiel des moyens humains est mobilisé
                localement, par les associations partenaires qui connaissent le terrain.
            </x-site.sec-title>

            <div class="row">
                @forelse ($members as $member)
                    <div class="col-xl-3 col-lg-4 col-md-6" style="margin-bottom: 40px;">
                        <x-site.team-card :member="$member" />
                    </div>
                @empty
                    <div class="col-xl-12">
                        <div class="empty-state">
                            <h3>L'équipe sera présentée prochainement</h3>
                            <p>En attendant, découvrez nos projets en cours.</p>
                            <a class="btn-one" href="{{ route('causes.index') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Nos causes</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="volunteer-call-area" style="padding: 0 0 110px;">
        <div class="container">
            <div class="empty-state" style="padding: 60px 40px;">
                <h3>Et si vous rejoigniez l'équipe ?</h3>
                <p>Nous cherchons en permanence des bénévoles en logistique, communication, éducation et santé.</p>
                <a class="btn-one" href="{{ route('volunteers.create') }}">
                    <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Devenir bénévole</span>
                </a>
            </div>
        </div>
    </section>

@endsection
