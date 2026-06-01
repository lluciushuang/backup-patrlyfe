{{-- resources/views/catalog.blade.php --}}
@extends('layouts.app')
@section('title', 'Katalog Produk')

@push('styles')
<style>
.shop-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 2rem;
}

.sidebar {
    flex-shrink: 0;
}

.sidebar-card {
    background: var(--surface);
    border: 1px solid rgba(242, 239, 230, 0.07);
    border-radius: 3px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.sidebar-title {
    font-family: var(--font-mono);
    font-size: 0.65rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--orange);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sidebar-title::before {
    content: '';
    width: 14px;
    height: 1px;
    background: var(--orange);
}

.filter-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.filter-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0.6rem;
    border-radius: 2px;
    cursor: pointer;
    font-size: 0.85rem;
    color: var(--gray-light);
    transition: all 0.15s;
}

.filter-item:hover {
    background: rgba(242, 239, 230, 0.05);
    color: var(--off-white);
}

.filter-item.active {
    background: rgba(232, 82, 26, 0.12);
    color: var(--orange);
}

.filter-item .count {
    font-family: var(--font-mono);
    font-size: 0.65rem;
    color: var(--gray-mid);
    padding: 0.1rem 0.4rem;
    background: rgba(242, 239, 230, 0.06);
    border-radius: 2px;
}

.filter-check {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.45rem 0;
    cursor: pointer;
    font-size: 0.85rem;
    color: var(--gray-light);
}

.filter-check input[type=checkbox] {
    display: none;
}

.check-box {
    width: 16px;
    height: 16px;
    border: 1px solid rgba(242, 239, 230, 0.2);
    border-radius: 2px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filter-check.checked .check-box {
    background: var(--orange);
    border-color: var(--orange);
}

.filter-check.checked .check-box::after {
    content: '✓';
    font-size: 10px;
    color: white;
}

.price-inputs {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    margin-top: 0.75rem;
}

.price-input {
    flex: 1;
    padding: 0.45rem 0.7rem;
    background: var(--surface-2);
    border: 1px solid rgba(242, 239, 230, 0.1);
    border-radius: 2px;
    color: var(--off-white);
    font-family: var(--font-mono);
    font-size: 0.72rem;
    outline: none;
    width: 0;
}

.rating-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0;
    cursor: pointer;
}

.stars-mini {
    display: flex;
    gap: 1px;
}

.star-ico {
    color: var(--orange);
    font-size: 0.8rem;
}

.star-ico.empty {
    color: rgba(242, 239, 230, 0.15);
}

.shop-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.sort-select {
    padding: 0.5rem 2.2rem 0.5rem 0.9rem;
    background: var(--surface);
    border: 1px solid rgba(242, 239, 230, 0.1);
    border-radius: 2px;
    color: var(--off-white);
    font-family: var(--font-body);
    font-size: 0.82rem;
    outline: none;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23888880' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.7rem center;
}

.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.7rem;
    background: rgba(232, 82, 26, 0.1);
    border: 1px solid rgba(232, 82, 26, 0.2);
    border-radius: 2px;
    font-size: 0.75rem;
    color: var(--orange);
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.prod-card {
    background: var(--surface);
    position: relative;
    overflow: hidden;
    transition: background 0.2s;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    border-radius: 4px;
    border: 1px solid rgba(242, 239, 230, 0.03);
}

.prod-card:hover {
    background: var(--surface-2);
}

.prod-img {
    aspect-ratio: 1;
    background: #1A1A16;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.prod-img-ph {
    opacity: 0.15;
}

.prod-body {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.prod-brand {
    font-family: var(--font-mono);
    font-size: 0.6rem;
    color: var(--orange);
    margin-bottom: 0.3rem;
    text-transform: uppercase;
}

.prod-name {
    font-size: 0.87rem;
    font-weight: 500;
    line-height: 1.45;
    margin-bottom: 0.4rem;
}

.prod-spec {
    font-size: 0.75rem;
    color: var(--gray-mid);
    margin-bottom: 0.75rem;
}

.prod-rating {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.75rem;
    font-size: 0.72rem;
    color: var(--gray-mid);
}

.prod-rating .s {
    color: var(--orange);
}

.prod-footer-price {
    margin-top: auto;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
}

.prod-price {
    font-family: var(--font-display);
    font-size: 1.35rem;
    color: var(--off-white);
}

.prod-price-old {
    font-size: 0.72rem;
    color: var(--gray-mid);
    text-decoration: line-through;
}

.prod-add-btn {
    width: 34px;
    height: 34px;
    background: transparent;
    border: 1px solid rgba(242, 239, 230, 0.2);
    border-radius: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--off-white);
}

.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    margin-top: 3rem;
}

.page-btn {
    width: 36px;
    height: 36px;
    border: 1px solid rgba(242, 239, 230, 0.1);
    background: transparent;
    color: var(--gray-light);
    font-family: var(--font-mono);
    font-size: 0.78rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.page-btn.active {
    background: var(--orange);
    border-color: var(--orange);
    color: white;
}

/* Tambahkan atau pastikan baris media query ini ada di file catalog.blade.php kamu */
@media (max-width: 1024px) {
    .shop-layout {
        grid-template-columns: 1fr !important;
    }

    .sidebar {
        display: none;
    }

    /* Sembunyikan filter sidebar besar di HP */
    .product-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.75rem !important;
    }
}

@media (max-width: 480px) {
    .product-grid {
        grid-template-columns: 1fr !important;
    }

    /* 1 Kolom penuh di HP berlayar kecil */
}
</style>
@endpush

@section('content')
<div class="page-wrapper"> {{-- Pembungkus Pengaman Tengah Layar --}}
    <div class="breadcrumb">
        <a href="{{ route('home') }}">HOME</a> <span>/</span> KATALOG
    </div>
<div class="shop-layout">
    <aside class="sidebar">
        <div class="sidebar-card">
            <div class="sidebar-title">Kategori</div>
            <ul class="filter-list">
                <li class="filter-item active">Semua Produk <span class="count">{{ \App\Models\Product::count() }}</span></li>
                @foreach(\App\Models\Category::withCount('products')->get() as $cat)
                <li class="filter-item">
                    {{ $cat->name }} <span class="count">{{ number_format($cat->products_count) }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="sidebar-card">
            <div class="sidebar-title">Merek Motor</div>
            @foreach([['Honda', true], ['Yamaha', true], ['Suzuki', false], ['Kawasaki', false]] as [$brand, $checked])
            <label class="filter-check {{ $checked ? 'checked' : '' }}">
                <input type="checkbox" {{ $checked ? 'checked' : '' }}>
                <span class="check-box"></span> {{ $brand }}
            </label>
            @endforeach
        </div>
    </aside>

    <main class="shop-main">
        <div class="shop-topbar">
            @php
                $products = \App\Models\Product::with(['prices', 'images'])->paginate(9);
            @endphp
            <div style="font-size:0.82rem; color:var(--gray-mid);">Menampilkan <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong> dari
                <strong>{{ $products->total() }}</strong> produk</div>
            <select class="sort-select">
                <option>Terlaris</option>
                <option>Harga Terendah</option>
            </select>
        </div>

        <div class="product-grid">
            @foreach($products as $p)
            <a href="{{ route('produk.show', $p->id) }}" class="prod-card">
                <div class="prod-img">
                    @if($p->images->count() > 0)
                        <img src="{{ asset('storage/' . $p->images->first()->image_path) }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <svg width="80" height="80" viewBox="0 0 100 100" class="prod-img-ph"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="15" y="15" width="70" height="70" rx="4" fill="none" stroke="#F2EFE6"
                                stroke-width="1.5" opacity="0.3" />
                        </svg>
                    @endif
                </div>
                <div class="prod-body">
                    <div class="prod-brand">{{ $p->brand }}</div>
                    <div class="prod-name">{{ $p->name }}</div>
                    <div class="prod-spec">Stok: {{ $p->current_stock }} {{ $p->unit }}</div>
                    <div class="prod-rating"><span class="s">★</span> 4.8</div>
                    <div class="prod-footer-price">
                        <div>
                            @php $price = $p->prices->where('price_level', 1)->first(); @endphp
                            @if($p->base_price > ($price->price ?? 0))
                                <div class="prod-price-old">Rp {{ number_format($p->base_price, 0, ',', '.') }}</div>
                            @endif
                            <div class="prod-price">Rp {{ number_format($price->price ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <button class="prod-add-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M12 5v14M5 12h14" />
                            </svg></button>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="pagination">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </main>
</div>
</div>
@endsection