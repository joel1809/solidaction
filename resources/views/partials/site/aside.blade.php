{{-- Panneau latéral d'information --}}
<div class="xs-sidebar-group info-group info-sidebar">
    <div class="xs-overlay xs-bg-black"></div>
    <div class="xs-sidebar-widget">
        <div class="sidebar-widget-container">
            <div class="widget-heading">
                <a href="#" class="close-side-widget">X</a>
            </div>
            <div class="sidebar-textwidget">
                <div class="sidebar-info-contents">
                    <div class="content-inner">
                        <div class="logo">
                            <a href="{{ route('home') }}"><img src="{{ theme_asset('assets/images/resources/logo-3.png') }}" alt="{{ setting('site_name', 'SolidAction') }}" /></a>
                        </div>
                        <div class="content-box">
                            <h4>Qui sommes-nous ?</h4>
                            <p>{{ setting('about_short', 'SolidAction est une organisation de solidarité qui agit auprès des familles vulnérables : accès à l\'eau, éducation, santé et urgences humanitaires.') }}</p>
                        </div>
                        <div class="form-inner">
                            <h4>Écrivez-nous</h4>
                            <form action="{{ route('contact.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Nom" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="E-mail" required>
                                </div>
                                <div class="form-group">
                                    <textarea name="message" placeholder="Votre message..." required></textarea>
                                </div>
                                <div class="form-group message-btn">
                                    <button type="submit" class="btn-one btn-one-style2">
                                        <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Envoyer</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
