@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

<style>
.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2.5rem;
    flex-wrap: wrap;
}
.page-item {
    display: inline-flex;
    list-style: none;
}
.page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border: 1px solid rgba(242, 239, 230, 0.12);
    background: var(--surface);
    color: var(--gray-light);
    font-family: var(--font-mono);
    font-size: 0.85rem;
    border-radius: 3px;
    text-decoration: none;
    transition: all 0.2s;
}
.page-link:hover {
    background: rgba(242, 239, 230, 0.08);
    color: var(--off-white);
    border-color: rgba(242, 239, 230, 0.2);
}
.page-item.active .page-link {
    background: var(--orange);
    border-color: var(--orange);
    color: white;
}
.page-item.disabled .page-link {
    opacity: 0.4;
    cursor: not-allowed;
}
</style>