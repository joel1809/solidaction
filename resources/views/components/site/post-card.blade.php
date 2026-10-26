@props(['post', 'delay' => '0ms'])

<div class="single-blog-style1 post-card wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $delay }}">
    <div class="img-holder">
        <div class="inner">
            <img src="{{ media($post->image, 'theme/assets/images/blog/blog-v1-1.jpg') }}" alt="{{ $post->title }}">
            <div class="overlay-icon">
                <a href="{{ route('posts.show', $post) }}"><span class="flaticon-plus"></span></a>
            </div>
        </div>
        <div class="date-box">
            <h2>{{ optional($post->published_at)->format('d') ?? $post->created_at->format('d') }}</h2>
            <p>{{ Str::upper(optional($post->published_at ?? $post->created_at)->translatedFormat('M')) }}</p>
        </div>
    </div>
    <div class="text-holder">
        <h3 class="blog-title">
            <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
        </h3>
        <div class="text">
            <p>{{ Str::limit(strip_tags($post->excerpt ?: $post->body), 105) }}</p>
        </div>
        <ul class="meta-info">
            <li><i class="fa fa-user" aria-hidden="true"></i> {{ $post->author->name ?? setting('site_name', 'Solid’Action') }}</li>
            <li><i class="fa fa-comment-o" aria-hidden="true"></i> {{ $post->approved_comments_count ?? $post->approvedComments()->count() }} commentaires</li>
        </ul>
    </div>
</div>
