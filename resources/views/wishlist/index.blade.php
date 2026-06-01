{{-- resources/views/wishlist/index.blade.php ── WISHLIST CYBER-INDUSTRIAL GRID --}}
@extends('layouts.app')
@section('title', 'Wishlist Saya')

@push('styles')
<style>
    .wishlist-container {
        margin-top: 1rem;
    }
    .wishlist-header {
        border-bottom: 1px solid rgba(242, 239, 230, 0.06);
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .wishlist-title {
        font-family: var(--font-display);
        font-size: 2.2rem;
        letter-spacing: 0.03em;
        color: var(--off-white);
    }
    .wishlist-count {
        font-family: var(--font-mono);
        font-size: 0.85rem;
        color: var(--gray-mid);
    }

    /* Wishlist Responsive Grid Match With Catalog */
    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
    }

    .wish-card {
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 4px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .wish-card:hover {
        transform: translateY(-4px);
        border-color: rgba(232, 82, 26, 0.2);
    }

    /* Delete Button Floating On Top Right */
    .wish-delete-trigger {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        width: 30px;
        height: 30px;
        background: rgba(13, 13, 11, 0.8);
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray-mid);
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s ease;
    }
    .wish-delete-trigger:hover {
        color: var(--red);
        border-color: rgba(231, 76, 60, 0.3);
        background: #0d0d0b;
    }

    .wish-img-area {
        aspect-ratio: 1;
        background: #151513;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(242, 239, 230, 0.15);
        border-bottom: 1px solid rgba(242, 239, 230, 0.03);
    }

    .wish-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .wish-brand {
        font-family: var(--font-mono);
        font-size: 0.65rem;
        color: var(--orange);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .wish-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--off-white);
        margin: 0.2rem 0 0.75rem;
        line-height: 1.4;
        flex: 1;
    }
    .wish-stock-status {
        font-family: var(--font-mono);
        font-size: 0.68rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .wish-stock-status.in { color: var(--green); }
    .wish-stock-status.out { color: var(--red); }

    .wish-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(242, 239, 230, 0.04);
    }
    .wish-price {
        font-family: var(--font-mono);
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--off-white);
    }
    .wish-add-cart-btn {
        width: 34px;
        height: 34px;
        background: rgba(232, 82, 26, 0.08);
        border: 1px solid rgba(232, 82, 26, 0.2);
        color: var(--orange);
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .wish-add-cart-btn:hover {
        background: var(--orange);
        color: white;
        box-shadow: 0 0 10px rgba(232, 82, 26, 0.2);
    }
</style>
@endpush

@section('content')
<div class="page-wrapper"> {{-- Pembungkus Pengaman Tengah Layar --}}
<div class="wishlist-container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">HOME</a> <span>/</span> WISHLIST
    </div>

    <div class="wishlist-header">
        <h1 class="wishlist-title">MY WISHLIST</h1>
        <span class="wishlist-count">3 ITEMS SAVED</span>
    </div>

    <div class="wishlist-grid">
        @foreach([
            ['BREMBO', 'Master Rem Brembo RCS 19 Corsa Corta Corto', 'Rp 4.950.000', 'In Stock', true],
            ['OHLINS', 'Shockbreaker Ohlins Tabung Bawah NMAX 335mm', 'Rp 9.250.000', 'In Stock', true],
            ['ASTRAL PARTS', 'Knalpot Full System Stainless Racing V.4', 'Rp 1.850.000', 'Out of Stock', false]
        ] as [$brand, $name, $price, $stock, $isInStock])
        <div class="wish-card">
            <button class="wish-delete-trigger" title="Hapus dari Wishlist">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            
            <div class="wish-img-area">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            </div>

            <div class="wish-body">
                <span class="wish-brand">{{ $brand }}</span>
                <h3 class="wish-name">{{ $name }}</h3>
                
                <div class="wish-stock-status {{ $isInStock ? 'in' : 'out' }}">
                    <span style="font-size: 8px;">●</span> {{ $stock }}
                </div>

                <div class="wish-footer">
                    <span class="wish-price">{{ $price }}</span>
                    @if($isInStock)
                    <button class="wish-add-cart-btn" title="Masukkan Keranjang">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection