@props(['testimonial'])

<div class="single-testimonial-style1">
    <div class="img-holder">
        <div class="inner">
            <img src="{{ media($testimonial->photo, 'theme/assets/images/testimonial/testimonial-v1-1.png') }}" alt="{{ $testimonial->name }}">
        </div>
    </div>
    <div class="text-holder">
        <div class="quote">
            <span class="flaticon-right-quotes-symbol"></span>
        </div>
        <div class="text">
            <h3>{{ $testimonial->title }}</h3>
            <p>{{ $testimonial->content }}</p>
        </div>
        <div class="client-info">
            <h4>{{ $testimonial->name }}</h4>
            <span>{{ $testimonial->location }}</span>
        </div>
    </div>
</div>
