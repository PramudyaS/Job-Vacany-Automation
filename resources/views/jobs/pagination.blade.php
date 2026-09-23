@if ($paginator->hasPages())
    @php
        $start = max(1, $paginator->currentPage() - 2);
        $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
    @endphp
    <nav class="pager" aria-label="Vacancy pages">
        <div class="pager-summary">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </div>
        <div class="pager-links">
            @if ($paginator->onFirstPage())
                <span class="pager-button disabled">← Previous</span>
            @else
                <a class="pager-button" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Previous</a>
            @endif

            @if ($start > 1)
                <a class="pager-number" href="{{ $paginator->url(1) }}">1</a>
                @if ($start > 2)<span class="pager-dots">…</span>@endif
            @endif

            @foreach (range($start, $end) as $page)
                @if ($page === $paginator->currentPage())
                    <span class="pager-number active" aria-current="page">{{ $page }}</span>
                @else
                    <a class="pager-number" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)<span class="pager-dots">…</span>@endif
                <a class="pager-number" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="pager-button" href="{{ $paginator->nextPageUrl() }}" rel="next">Next →</a>
            @else
                <span class="pager-button disabled">Next →</span>
            @endif
        </div>
    </nav>
@endif
