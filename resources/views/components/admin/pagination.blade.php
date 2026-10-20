@props(['paginator', 'label' => 'élément'])

@if ($paginator->total() > 0)
    <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-zinc-500">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} sur
            <span class="font-medium text-zinc-700">{{ number_format($paginator->total(), 0, ',', ' ') }}</span>
            {{ Str::plural($label, $paginator->total()) }}
        </p>

        @if ($paginator->hasPages())
            <nav class="flex items-center gap-1" aria-label="Pagination">
                @if ($paginator->onFirstPage())
                    <span class="grid size-9 place-items-center rounded-lg border border-zinc-200 text-zinc-300">&lsaquo;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Page précédente"
                        class="grid size-9 place-items-center rounded-lg border border-zinc-200 text-zinc-600 transition hover:bg-zinc-50">&lsaquo;</a>
                @endif

                @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                            class="grid size-9 place-items-center rounded-lg bg-zinc-900 text-sm font-medium text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="grid size-9 place-items-center rounded-lg border border-zinc-200 text-sm text-zinc-600 transition hover:bg-zinc-50">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Page suivante"
                        class="grid size-9 place-items-center rounded-lg border border-zinc-200 text-zinc-600 transition hover:bg-zinc-50">&rsaquo;</a>
                @else
                    <span class="grid size-9 place-items-center rounded-lg border border-zinc-200 text-zinc-300">&rsaquo;</span>
                @endif
            </nav>
        @endif
    </div>
@endif
