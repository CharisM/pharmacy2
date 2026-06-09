@if ($paginator->hasPages())
<nav class="pag-nav" role="navigation" aria-label="Pagination">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="pag-btn pag-disabled" aria-disabled="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="15 18 9 12 15 6"/></svg>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="pag-btn" rel="prev" aria-label="Previous">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="pag-ellipsis">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pag-btn pag-active" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="pag-btn" rel="next" aria-label="Next">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    @else
        <span class="pag-btn pag-disabled" aria-disabled="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
    @endif

</nav>

<style>
    .pag-nav {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .pag-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 6px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid transparent;
        text-decoration: none;
        transition: all 0.18s ease;
        cursor: pointer;
        user-select: none;
    }

    .pag-btn:hover:not(.pag-disabled):not(.pag-active) {
        background: #e2e8f0;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .pag-btn.pag-active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 3px 10px rgba(99,102,241,0.35);
        cursor: default;
    }

    .pag-btn.pag-disabled {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
    }

    .pag-ellipsis {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #94a3b8;
        user-select: none;
    }
</style>
@endif
