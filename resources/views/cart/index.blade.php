{{-- resources/views/cart/index.blade.php ── CARTS RE-DESIGN INDUSTRIAL SEAMLESS --}}
@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    .cart-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2.5rem;
        align-items: flex-start;
        margin-top: 1rem;
    }
    @media (max-width: 992px) {
        .cart-layout { grid-template-columns: 1fr; }
    }

    /* Header & Control */
    .cart-header-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        border-bottom: 1px solid rgba(242, 239, 230, 0.06);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    .cart-page-title {
        font-family: var(--font-display);
        font-size: 2.2rem;
        letter-spacing: 0.03em;
        color: var(--off-white);
    }
    .cart-count-badge {
        font-family: var(--font-mono);
        font-size: 0.8rem;
        color: var(--gray-mid);
    }
    .cart-actions-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #111110;
        border: 1px solid rgba(242,239,230,0.04);
        padding: 0.75rem 1.25rem;
        border-radius: 3px;
        margin-bottom: 1rem;
        font-size: 0.85rem;
    }
    .check-wrapper {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        cursor: pointer;
        color: var(--gray-light);
    }

    /* Cart Items Loop Styles */
    .cart-items-stack {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .cart-item-card {
        display: grid;
        grid-template-columns: auto 100px 1fr;
        gap: 1.25rem;
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 4px;
        padding: 1.25rem;
        align-items: center;
    }
    .cart-item-img-box {
        width: 100px;
        height: 100px;
        background: #161614;
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray-mid);
    }
    .cart-item-details {
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: space-between;
    }
    .ci-meta-brand {
        font-family: var(--font-mono);
        font-size: 0.68rem;
        color: var(--orange);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .ci-meta-name {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--off-white);
        margin: 0.15rem 0 0.5rem;
        line-height: 1.3;
    }
    .ci-price-row {
        display: flex;
        align-items: baseline;
        gap: 0.75rem;
    }
    .ci-price {
        font-family: var(--font-mono);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--off-white);
    }
    .ci-price-old {
        font-family: var(--font-mono);
        font-size: 0.8rem;
        color: var(--gray-mid);
        text-decoration: line-through;
    }

    /* Quantity Industrial Controls */
    .ci-controls-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
    }
    .qty-stepper {
        display: flex;
        align-items: center;
        background: #161614;
        border: 1px solid rgba(242, 239, 230, 0.08);
        border-radius: 2px;
        overflow: hidden;
    }
    .qty-btn {
        background: transparent;
        border: none;
        width: 32px;
        height: 32px;
        color: var(--gray-light);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: background 0.2s;
    }
    .qty-btn:hover { background: rgba(242, 239, 230, 0.03); color: var(--off-white); }
    .qty-val {
        width: 40px;
        text-align: center;
        font-family: var(--font-mono);
        font-size: 0.85rem;
        color: var(--off-white);
    }
    .ci-action-icon-btn {
        background: transparent;
        border: none;
        color: var(--gray-mid);
        cursor: pointer;
        padding: 0.5rem;
        transition: color 0.2s;
    }
    .ci-action-icon-btn:hover { color: var(--red); }

    /* ── ORDER SUMMARY BLOCK ── */
    .summary-card {
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 4px;
        padding: 1.75rem;
        position: sticky;
        top: 100px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }
    .summary-title {
        font-family: var(--font-display);
        font-size: 1.4rem;
        letter-spacing: 0.05em;
        color: var(--off-white);
        border-bottom: 1px solid rgba(242, 239, 230, 0.06);
        padding-bottom: 0.85rem;
        margin-bottom: 1.25rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.88rem;
        color: var(--gray-light);
        margin-bottom: 0.85rem;
    }
    .summary-row.discount-text { color: var(--green); }
    .summary-row.total-bill {
        border-top: 1px solid rgba(242, 239, 230, 0.06);
        padding-top: 1.25rem;
        margin-top: 1.25rem;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--off-white);
    }
    .summary-row.total-bill .price-val {
        font-family: var(--font-mono);
        color: var(--orange);
        font-size: 1.3rem;
        font-weight: 700;
    }
    .free-shipping-pill {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(46, 204, 113, 0.06);
        border: 1px solid rgba(46, 204, 113, 0.15);
        color: var(--green);
        padding: 0.65rem 0.85rem;
        border-radius: 3px;
        font-size: 0.78rem;
        margin: 1.25rem 0;
        line-height: 1.4;
    }
    .free-shipping-pill svg { width: 16px; height: 16px; flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="page-wrapper"> {{-- Pembungkus Pengaman Tengah Layar --}}
<div class="breadcrumb">
    <a href="{{ route('home') }}">HOME</a> <span>/</span> KERANJANG
</div>

<div class="cart-layout">
    {{-- BARANG DALAM KERANJANG --}}
    <div style="display: flex; flex-direction: column;">
        <div class="cart-header-row">
            <h1 class="cart-page-title">SHOPPING CART</h1>
            <span class="cart-count-badge">2 ITEMS SELECTED</span>
        </div>

        <div class="cart-actions-bar">
            <label class="check-wrapper">
                <input type="checkbox" checked style="accent-color: var(--orange);">
                <span>Pilih Semua Barang</span>
            </label>
            <a href="#" style="color: var(--gray-mid); text-decoration: none; font-size: 0.82rem;" onmouseover="this.style.color='var(--red)'" onmouseout="this.style.color='var(--gray-mid)'">Hapus Pilihan</a>
        </div>

        <div class="cart-items-stack">
            @foreach([
                ['RACING BOY', 'Shockbreaker RCB Black Series WD Line 330mm', 'Rp 1.450.000', 'Rp 1.680.000', 1],
                ['DIZZY MECHANIC', 'Piston Kit Bore-Up Karburator Kit Set V.2', 'Rp 320.000', null, 2]
            ] as [$brand, $name, $price, $old, $qty])
            <div class="cart-item-card">
                <input type="checkbox" checked style="accent-color: var(--orange); cursor: pointer;">
                <div class="cart-item-img-box">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.4"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                </div>
                <div class="cart-item-details">
                    <div>
                        <span class="ci-meta-brand">{{ $brand }}</span>
                        <h3 class="ci-meta-name">{{ $name }}</h3>
                        <div class="ci-price-row">
                            <span class="ci-price">{{ $price }}</span>
                            @if($old)<span class="ci-price-old">{{ $old }}</span>@endif
                        </div>
                    </div>
                    
                    <div class="ci-controls-wrapper">
                        <div class="qty-stepper">
                            <button class="qty-btn">−</button>
                            <div class="qty-val">{{ $qty }}</div>
                            <button class="qty-btn">+</button>
                        </div>
                        <button class="ci-action-icon-btn" title="Hapus Item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- SUMMARY ORDER SIDEBAR --}}
    <aside class="summary-card">
        <h2 class="summary-title">RINGKASAN BELANJA</h2>
        
        <div class="summary-row">
            <span>Total Harga (3 Barang)</span>
            <span style="font-family: var(--font-mono); color: var(--off-white);">Rp 2.090.000</span>
        </div>
        <div class="summary-row discount-text">
            <span>Diskon Toko</span>
            <span style="font-family: var(--font-mono);">-Rp 230.000</span>
        </div>
        <div class="summary-row">
            <span>Ongkos Kirim</span>
            <span style="font-family: var(--font-mono); color: var(--green);">GRATIS</span>
        </div>

        <div class="free-shipping-pill">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <span>Aman! Belanjamu di atas Rp 200.000, otomatis dapat gratis ongkir reguler.</span>
        </div>

        <div class="summary-row total-bill">
            <span>Total Harga</span>
            <span class="price-val">Rp 1.860.000</span>
        </div>

        <button class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1.5rem; justify-content: center;">
            Lanjut ke Checkout
        </button>
    </aside>
</div>
</div>
@endsection