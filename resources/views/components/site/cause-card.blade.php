@props(['cause'])

<div class="single-cause-style1">
    <div class="img-holder">
        <a href="{{ route('causes.show', $cause) }}">
            <img src="{{ media($cause->image, 'theme/assets/images/causes/causes-v1-1.jpg') }}" alt="{{ $cause->title }}">
        </a>
        @if ($cause->category)
            <div class="cause-category"><span>{{ $cause->category->name }}</span></div>
        @endif
    </div>
    <div class="text-holder">
        <h3><a href="{{ route('causes.show', $cause) }}">{{ $cause->title }}</a></h3>
        <p>{{ Str::limit(strip_tags($cause->excerpt ?: $cause->description), 110) }}</p>

        <div class="progress-levels progress-levels-style2">
            <div class="progress-box wow">
                <div class="inner count-box">
                    <div class="bar">
                        <div class="bar-innner">
                            <div class="bar-fill" data-percent="{{ $cause->progress }}" title="{{ $cause->title }}"></div>
                        </div>
                    </div>
                    <div class="bottom-box">
                        <div class="rate-box">
                            <p>Collecté<span>{{ money($cause->raised_amount) }}</span></p>
                            <p>Objectif<span>{{ money($cause->goal_amount) }}</span></p>
                        </div>
                        <div class="skill-percent">
                            <span class="count-text" data-speed="3000" data-stop="{{ $cause->progress }}">0</span>
                            <span class="percent">%</span>
                            <p class="outer-text">atteint</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btns-box">
            <a class="btn-one" href="{{ route('donations.create', ['cause' => $cause->id]) }}">
                <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Faire un don</span>
            </a>
        </div>
    </div>
</div>
