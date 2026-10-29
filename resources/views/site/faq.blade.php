@extends('layouts.site')

@section('title', 'Questions fréquentes')
@section('meta_description', 'Dons, Mobile Money, bénévolat, transparence financière : toutes les réponses aux questions les plus posées.')

@section('content')

    <x-site.breadcrumb title="Questions fréquentes" :items="['Association' => route('about'), 'FAQ']"
        image="theme/assets/images/breadcrumb/breadcrumb-faq.jpg" />

    <section class="faq-style1-area" style="padding: 110px 0 90px;">
        <div class="container">
            <x-site.sec-title subtitle="Nous vous répondons" title="Vos questions, nos réponses" :icon="false" />

            <div class="row">
                <div class="col-xl-10 offset-xl-1">
                    @forelse ($faqs as $group => $items)
                        <h3 style="font-size: 26px; margin: 40px 0 22px;">{{ $group }}</h3>
                        <div class="faq-style1-content">
                            <div class="accordion-box">
                                @foreach ($items as $index => $faq)
                                    <div class="accordion accordion-block">
                                        <div class="accord-btn {{ $loop->parent->first && $index === 0 ? 'active' : '' }}">
                                            <h4>{{ $faq->question }}</h4>
                                        </div>
                                        <div class="accord-content {{ $loop->parent->first && $index === 0 ? 'collapsed' : '' }}">
                                            <p>{{ $faq->answer }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <h3>Aucune question publiée</h3>
                            <p>Posez directement la vôtre, nous répondons sous 48 heures.</p>
                            <a class="btn-one" href="{{ route('contact.create') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Poser une question</span>
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            @if ($faqs->isNotEmpty())
                <div class="row" style="margin-top: 60px;">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="empty-state">
                            <h3>Votre question n'est pas dans la liste ?</h3>
                            <p>Notre équipe vous répond par e-mail sous 48 heures ouvrées.</p>
                            <a class="btn-one" href="{{ route('contact.create') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Nous écrire</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
