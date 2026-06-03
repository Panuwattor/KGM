@if ($paginator->hasPages())
<nav class="kgm-pagination">
    {{-- First Page --}}
    @if ($paginator->onFirstPage())
        <span class="pg-btn disabled"><i class="bi bi-chevron-bar-left"></i></span>
        <span class="pg-btn disabled"><i class="bi bi-chevron-left"></i></span>
    @else
        <a class="pg-btn" href="{{ $paginator->url(1) }}"><i class="bi bi-chevron-bar-left"></i></a>
        <a class="pg-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left"></i></a>
    @endif

    <span class="pg-info">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

    {{-- Last Page --}}
    @if ($paginator->hasMorePages())
        <a class="pg-btn" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="bi bi-chevron-right"></i></a>
        <a class="pg-btn" href="{{ $paginator->url($paginator->lastPage()) }}"><i class="bi bi-chevron-bar-right"></i></a>
    @else
        <span class="pg-btn disabled"><i class="bi bi-chevron-right"></i></span>
        <span class="pg-btn disabled"><i class="bi bi-chevron-bar-right"></i></span>
    @endif
</nav>
@endif
