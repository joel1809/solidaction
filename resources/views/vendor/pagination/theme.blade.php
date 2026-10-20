@if ($paginator->hasPages())
    <ul class="styled-pagination text-center clearfix">
        @if ($paginator->onFirstPage())
            <li class="arrow prev disabled"><span><span class="flaticon-long-arrow-pointing-to-the-right"></span></span></li>
        @else
            <li class="arrow prev"><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><span class="flaticon-long-arrow-pointing-to-the-right"></span></a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><a href="{{ $url }}">{{ $page }}</a></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="arrow next"><a href="{{ $paginator->nextPageUrl() }}" rel="next"><span class="flaticon-long-arrow-pointing-to-the-right"></span></a></li>
        @else
            <li class="arrow next disabled"><span><span class="flaticon-long-arrow-pointing-to-the-right"></span></span></li>
        @endif
    </ul>
@endif
