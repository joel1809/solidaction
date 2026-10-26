@props(['member'])

<div class="single-team-style1">
    <div class="img-holder">
        <div class="inner">
            <img src="{{ media($member->photo, 'theme/assets/images/team/team-v1-1.jpg') }}" alt="{{ $member->name }}" />
            <div class="icon"><span class=""></span></div>
        </div>
        <div class="overly-box">
            <ul class="social-links">
                @forelse ($member->socialLinks() as $network => $url)
                    <li><a href="{{ $url }}" target="_blank" rel="noopener"><i class="fa fa-{{ $network }}" aria-hidden="true"></i></a></li>
                @empty
                    <li><a href="{{ route('team') }}"><i class="fa fa-user" aria-hidden="true"></i></a></li>
                @endforelse
            </ul>
        </div>
        <div class="shape"><img src="{{ media(setting('site_logo'), 'theme/assets/images/logo/logo-header.png') }}" alt="{{ setting('site_name', 'Solid’Action') }}"></div>
    </div>
    <div class="title-holder text-center">
        <h3><a href="{{ route('team') }}">{{ $member->name }}</a></h3>
        <p>{{ $member->role }}</p>
    </div>
</div>
