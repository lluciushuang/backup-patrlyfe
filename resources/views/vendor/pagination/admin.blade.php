@if ($paginator->hasPages())
    <nav aria-label="Pagination">
        <ul class="admin-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item disabled">
                    <span class="pagination-link">&lsaquo; Sebelumnya</span>
                </li>
            @else
                <li class="pagination-item">
                    <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo; Sebelumnya</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="pagination-item disabled"><span class="pagination-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item active"><span class="pagination-link">{{ $page }}</span></li>
                        @else
                            <li class="pagination-item"><a class="pagination-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Selanjutnya &rsaquo;</a>
                </li>
            @else
                <li class="pagination-item disabled">
                    <span class="pagination-link">Selanjutnya &rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

<style>
.admin-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
    padding: 0;
    margin: 1.5rem 0 0;
}
.pagination-item {
    display: inline-flex;
    list-style: none;
}
.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 0.75rem;
    border: 1px solid rgba(242, 239, 230, 0.12);
    background: var(--surface);
    color: var(--gray-light);
    font-family: var(--font-mono);
    font-size: 0.85rem;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.pagination-link:hover {
    background: rgba(242, 239, 230, 0.08);
    color: var(--off-white);
    border-color: rgba(242, 239, 230, 0.2);
}
.pagination-item.active .pagination-link {
    background: var(--orange);
    border-color: var(--orange);
    color: white;
}
.pagination-item.disabled .pagination-link {
    opacity: 0.4;
    cursor: not-allowed;
}
</style>