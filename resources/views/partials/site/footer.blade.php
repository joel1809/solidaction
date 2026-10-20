<footer class="footer-area">
    <div class="footer">
        <div class="auto-container">
            <div class="row">
                {{-- Présentation --}}
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 wow animated fadeInUp" data-wow-delay="0.1s">
                    <div class="single-footer-widget">
                        <div class="our-company-info">
                            <div class="footer-logo">
                                <a href="{{ route('home') }}">
                                    <img src="{{ media(setting('site_logo_footer'), 'theme/assets/images/logo/logo-footer.png') }}" alt="{{ setting('site_name', 'SolidAction') }}" />
                                </a>
                            </div>
                            <div class="text-box">
                                <p>{{ setting('footer_text', 'Depuis 2004, SolidAction mobilise donateurs et bénévoles autour de projets durables : eau potable, écoles, santé et réponse aux urgences.') }}</p>
                            </div>

                            <div class="footer-contact-info">
                                <div class="icon">
                                    <span class="flaticon-phone-call"></span>
                                </div>
                                <div class="support-box">
                                    <h5>Appelez-nous : <a href="tel:{{ preg_replace('/\s+/', '', setting('contact_phone', '+33184801234')) }}">{{ setting('contact_phone', '+33 1 84 80 12 34') }}</a></h5>
                                    <div class="email">
                                        <p><a href="mailto:{{ setting('contact_email', 'contact@solidaction.org') }}">E-mail : {{ setting('contact_email', 'contact@solidaction.org') }}</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Causes récentes --}}
                <div class="col-xl-2 col-lg-4 col-md-3 col-sm-12 wow animated fadeInUp" data-wow-delay="0.3s">
                    <div class="single-footer-widget martop pd40-0">
                        <div class="title">
                            <h3>Nos appels</h3>
                        </div>
                        <ul class="footer-widget-links1">
                            @forelse ($footerCauses ?? [] as $cause)
                                <li><a href="{{ route('causes.show', $cause) }}">{{ Str::limit($cause->title, 26) }}</a></li>
                            @empty
                                <li><a href="{{ route('causes.index') }}">Toutes nos causes</a></li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Liens association --}}
                <div class="col-xl-2 col-lg-4 col-md-3 col-sm-12 wow animated fadeInUp" data-wow-delay="0.5s">
                    <div class="single-footer-widget martop">
                        <div class="title">
                            <h3>L'association</h3>
                        </div>
                        <ul class="footer-widget-links1">
                            <li><a href="{{ route('about') }}">Qui sommes-nous</a></li>
                            <li><a href="{{ route('team') }}">Notre équipe</a></li>
                            <li><a href="{{ route('posts.index') }}">Actualités</a></li>
                            <li><a href="{{ route('volunteers.create') }}">Devenir bénévole</a></li>
                            <li><a href="{{ route('faq') }}">Questions fréquentes</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Programmes --}}
                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 wow animated fadeInUp" data-wow-delay="0.5s">
                    <div class="single-footer-widget martop pdtop40">
                        <div class="title">
                            <h3>Programmes</h3>
                        </div>
                        <ul class="footer-widget-links1">
                            @forelse ($footerCategories ?? [] as $category)
                                <li><a href="{{ route('causes.index', ['categorie' => $category->slug]) }}">{{ $category->name }}</a></li>
                            @empty
                                <li><a href="{{ route('causes.index') }}">Nos programmes</a></li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Newsletter --}}
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 wow animated fadeInUp" data-wow-delay="0.7s">
                    <div class="single-footer-widget fixwidth martop pdtop40">
                        <div class="title">
                            <h3>Newsletter</h3>
                        </div>
                        <div class="text">
                            <p>Recevez chaque mois les nouvelles du terrain.</p>
                        </div>
                        <form action="{{ route('newsletter.store') }}" method="post" class="widget-subscribe-form">
                            @csrf
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Votre e-mail" required>
                                <button class="submit" type="submit"><i class="flaticon-opened"></i></button>
                            </div>
                            @error('email')
                                <p class="footer-form-error">{{ $message }}</p>
                            @enderror
                        </form>
                        @if (session('newsletter_success'))
                            <p class="footer-form-success">{{ session('newsletter_success') }}</p>
                        @endif
                        <div class="footer-donate-link">
                            <a class="btn-one btn-one-style2" href="{{ route('donations.create') }}">
                                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Soutenir maintenant</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="auto-container">
            <div class="footer-bottom_content_box text-center">
                <div class="copyright-text">
                    <p>&copy; {{ date('Y') }} <a href="{{ route('home') }}">{{ setting('site_name', 'SolidAction') }}</a>. Tous droits réservés.
                        @auth
                            <a href="{{ route('admin.dashboard') }}">Administration</a>
                        @else
                            <a href="{{ route('login') }}">Espace équipe</a>
                        @endauth
                    </p>
                </div>
                <div class="footer-social-link">
                    <ul class="social-links-style1">
                        @foreach (['twitter' => 'social_twitter', 'facebook' => 'social_facebook', 'linkedin' => 'social_linkedin', 'youtube' => 'social_youtube', 'instagram' => 'social_instagram'] as $icon => $key)
                            @if ($url = setting($key))
                                <li><a href="{{ $url }}" target="_blank" rel="noopener"><i class="fa fa-{{ $icon }}" aria-hidden="true"></i></a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
