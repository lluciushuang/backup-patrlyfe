{{-- resources/views/show.blade.php --}}
@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('title', $product->name ?? 'Detail Produk')

@push('styles')
<style>
    /* ── DETAIL LAYOUT CUBE ── */
    .detail-grid-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3.5rem;
        margin-bottom: 4rem;
        margin-top: 1rem;
    }
    @media (max-width: 992px) {
        .detail-grid-layout { grid-template-columns: 1fr; gap: 2rem; }
    }

    /* Industrial Image Showcase */
    .gallery-container {
        position: sticky;
        top: 100px;
    }
    .main-preview-box {
        aspect-ratio: 1;
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242, 239, 230, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        margin-bottom: 1rem;
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        padding: 1rem;
    }
    .main-preview-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .main-preview-svg {
        opacity: 0.15;
        transition: opacity 0.3s;
    }
    .main-preview-box:hover .main-preview-svg {
        opacity: 0.25;
    }
    .floating-badge {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        z-index: 10;
    }
    .gallery-thumbs-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }
    .thumb-cube {
        aspect-ratio: 1;
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.06);
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0.25rem;
    }
    .thumb-cube img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .thumb-cube:hover { border-color: rgba(232, 82, 26, 0.4); }
    .thumb-cube.active { border-color: var(--orange); background: rgba(232, 82, 26, 0.02); }
    .thumb-cube svg { opacity: 0.1; }

    /* Info Engine Content */
    .product-info-panel {
        display: flex;
        flex-direction: column;
    }
    .prod-sku-tag {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--orange);
        margin-bottom: 0.5rem;
    }
    .prod-main-title {
        font-family: var(--font-display);
        font-size: 2.5rem;
        line-height: 1.1;
        letter-spacing: 0.02em;
        color: var(--off-white);
        margin-bottom: 0.75rem;
    }
    .prod-rating-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--gray-mid);
        margin-bottom: 1.75rem;
    }
    .meta-divider { width: 1px; height: 12px; background: rgba(242, 239, 230, 0.15); }

    /* Industrial Wrapped Price Block */
    .price-plate {
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.04);
        border-left: 3px solid var(--orange);
        padding: 1.5rem;
        border-radius: 4px;
        margin-bottom: 2rem;
        box-shadow: inset 0 0 20px rgba(0,0,0,0.2);
    }
    .price-was {
        font-family: var(--font-mono);
        font-size: 0.85rem;
        color: var(--gray-mid);
        text-decoration: line-through;
        margin-bottom: 0.25rem;
    }
    .price-now {
        font-family: var(--font-display);
        font-size: 2.8rem;
        letter-spacing: 0.02em;
        line-height: 1;
        color: var(--off-white);
    }
    .discount-pill-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(232, 82, 26, 0.12);
        color: var(--orange);
        font-family: var(--font-mono);
        font-size: 0.68rem;
        font-weight: 700;
        padding: 0.2rem 0.5rem;
        border-radius: 2px;
        margin-left: 0.75rem;
        vertical-align: middle;
    }

    /* Attribute Selections */
    .attribute-section {
        margin-bottom: 1.5rem;
    }
    .attribute-label {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--gray-mid);
        text-transform: uppercase;
        margin-bottom: 0.6rem;
        letter-spacing: 0.05em;
    }
    .chip-group { display: flex; flex-wrap: wrap; gap: 0.6rem; }
    .selector-chip {
        padding: 0.5rem 1rem;
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.1);
        border-radius: 2px;
        font-size: 0.85rem;
        color: var(--gray-light);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .selector-chip:hover { border-color: rgba(232, 82, 26, 0.4); color: var(--off-white); }
    .selector-chip.active {
        border-color: var(--orange);
        color: var(--orange);
        background: rgba(232, 82, 26, 0.06);
        font-weight: 500;
    }

    /* Inventory Mechanics */
    .inventory-status-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        margin: 1.5rem 0;
        color: var(--gray-light);
    }
    .status-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--green);
        box-shadow: 0 0 8px var(--green);
    }
    .status-pulse-dot.low-stock { background: #F39C12; box-shadow: 0 0 8px #F39C12; }

    /* Quantity Form Controls */
    .checkout-form-action-block {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        border-top: 1px solid rgba(242, 239, 230, 0.06);
        padding-top: 1.5rem;
        margin-top: 1rem;
    }
    .quantity-row-panel {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .stepper-box {
        display: flex;
        align-items: center;
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.12);
        border-radius: 2px;
        overflow: hidden;
    }
    .stepper-action-btn {
        width: 40px;
        height: 40px;
        background: transparent;
        border: none;
        color: var(--off-white);
        font-size: 1.1rem;
        cursor: pointer;
        transition: background 0.15s;
    }
    .stepper-action-btn:hover { background: rgba(242,239,230,0.03); }
    .stepper-number-input {
        width: 55px;
        height: 40px;
        background: transparent;
        border: none;
        text-align: center;
        color: var(--off-white);
        font-family: var(--font-mono);
        font-size: 0.9rem;
        outline: none;
    }
    /* Hide HTML Spin Buttons */
    .stepper-number-input::-webkit-outer-spin-button,
    .stepper-number-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    /* Interactive Operational Buttons */
    .action-button-group {
        display: flex;
        gap: 0.85rem;
        width: 100%;
    }
    .submit-cart-btn {
        flex: 1;
        justify-content: center;
    }
    .favorite-toggle-btn {
        width: 48px;
        height: 48px;
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.12);
        color: var(--gray-light);
        border-radius: 2px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .favorite-toggle-btn:hover { color: var(--red); border-color: rgba(231,76,60,0.3); background: rgba(231,76,60,0.02); }

    /* Technical Specification Table */
    .technical-specs-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2.5rem;
    }
    .technical-specs-table tr {
        border-bottom: 1px solid rgba(242, 239, 230, 0.05);
    }
    .technical-specs-table tr:last-child { border-bottom: none; }
    .technical-specs-table td {
        padding: 0.85rem 0;
        font-size: 0.88rem;
        vertical-align: top;
    }
    .technical-specs-table td:first-child {
        color: var(--gray-mid);
        font-family: var(--font-mono);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        width: 35%;
    }
    .technical-specs-table td:last-child {
        color: var(--off-white);
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="page-wrapper"> {{-- Pembungkus Pengaman Tengah Layar --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
        <a href="{{ route('produk.index') }}" class="btn btn-outline btn-sm" style="background: var(--orange); color: var(--off-white); border: none; font-size: 0.85rem; padding: 0.5rem 1rem;">
            ← KEMBALI KE PRODUK
        </a>
    </div>
    <div class="breadcrumb">
        <a href="{{ route('home') }}">HOME</a> <span>/</span> 
        <a href="{{ route('produk.index') }}">SPARES</a> <span>/</span> 
        {{ strtoupper($product->name ?? 'KAMPAS REM BREMBO VARIO') }}
    </div>

<div class="detail-grid-layout">
    
    {{-- LEFT COMPONENT: INDUSTRIAL GALLERY COMPONENT --}}
    <div class="gallery-container">
        <div class="main-preview-box">
            <span class="badge badge-orange floating-badge">TERLARIS</span>
            
            @if(isset($product) && $product->images->count() > 0)
                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="main-preview-img">
            @else
                <svg class="main-preview-svg" width="160" height="160" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="42" fill="none" stroke="#F2EFE6" stroke-width="1.2"/>
                    <circle cx="50" cy="50" r="25" fill="none" stroke="#F2EFE6" stroke-width="0.8" stroke-dasharray="2,2"/>
                    <path d="M50 10 L50 90 M10 50 L90 50" stroke="#F2EFE6" stroke-width="0.5" opacity="0.3"/>
</svg>
                @endif
            </div>
        
        <div class="gallery-thumbs-row">
            @if(isset($product) && $product->images->count() > 0)
                @foreach($product->images->take(4) as $index => $img)
                <div class="thumb-cube {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $img->image_path) }}" alt="thumb">
                </div>
                @endforeach
            @else
                <div class="thumb-cube active">
                    <svg width="24" height="24" viewBox="0 0 100 100"><circle cx="50" cy="50" r="35" fill="none" stroke="#F2EFE6" stroke-width="1.5"/></svg>
                </div>
                @for($i = 2; $i <= 4; $i++)
                    <div class="thumb-cube">
                        <svg width="24" height="24" viewBox="0 0 100 100" opacity="0.4"><rect x="20" y="20" width="60" height="60" fill="none" stroke="#F2EFE6" stroke-width="1.5"/></svg>
                    </div>
                @endfor
            @endif
        </div>
    </div>

    {{-- RIGHT COMPONENT: INTERACTIVE DB OPERATIONS FORM --}}
    <div class="product-info-panel">
        <div class="prod-sku-tag">
            {{ $product->brand ?? 'BREMBO' }} · SKU: {{ $product->item_code ?? 'BRM-VP125-SET' }}
        </div>
        
        <h1 class="prod-main-title" style="margin-bottom: 0;">
            {{ $product->name ?? 'Kampas Rem Cakram Honda Vario 125 — Depan & Belakang' }}
        </h1>
        
        <div class="prod-rating-meta">
            <span style="color: var(--orange);">
                ⭐ <strong style="color: var(--off-white); margin-left: 0.25rem;">{{ number_format($product->rating ?? 0, 1) }}</strong>
            </span>
            <div class="meta-divider"></div>
            <span>{{ number_format($product->reviews_count ?? 0) }} Ulasan Verified</span>
            <div class="meta-divider"></div>
            <span>Terjual {{ number_format($product->sold_count ?? 0) }} Unit</span>
        </div>

        <div class="price-plate">
            @php
                $tierPrice = $product->prices->where('price_level', $tierPriceLevel)->first()?->price ?? $product->base_price ?? 0;
                $level1Price = $product->prices->where('price_level', 1)->first()?->price ?? $product->base_price ?? 0;
            @endphp
            @if($tierPriceLevel > 1)
                <div class="price-was">Rp {{ number_format($level1Price, 0, ',', '.') }}</div>
                <div class="price-now">
                    Rp {{ number_format($tierPrice, 0, ',', '.') }}
                </div>
            @else
                <div class="price-now">
                    Rp {{ number_format($tierPrice, 0, ',', '.') }}
                </div>
            @endif
        </div>

        {{-- DB STOCK FEEDBACK --}}
        <div class="inventory-status-bar">
            <div class="status-pulse-dot {{ ($product->current_stock ?? 24) < 10 ? 'low-stock' : '' }}"></div>
            <span>
                Stok Sistem Gudang: <strong style="color: #F39C12;">{{ $product->current_stock ?? 24 }} unit</strong> tersisa.
            </span>
        </div>

        {{-- ECO-DATABASE ACTION FORM (CRUD: CREATE FOR CART ITEMS) --}}
        <form action="{{ route('cart.index') }}" method="GET" class="checkout-form-action-block">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id ?? 1 }}">
            
            <div class="quantity-row-panel">
                <div class="attribute-label" style="margin-bottom: 0; width: 80px;">Kuantitas</div>
                <div class="stepper-box">
                    <button type="button" class="stepper-action-btn" onclick="decrementQty()">−</button>
                    <input type="number" id="purchaseQty" name="qty" class="stepper-number-input" value="1" min="1" max="{{ $product->current_stock ?? 24 }}">
                    <button type="button" class="stepper-action-btn" onclick="incrementQty()">+</button>
                </div>
                <span style="font-size: 0.78rem; color: var(--gray-mid); font-family: var(--font-mono);">Max pembelian: {{ $product->current_stock ?? 24 }} unit</span>
            </div>

            <div class="action-button-group" style="margin-top: 0.5rem;">
                <button type="button" class="btn btn-primary btn-lg submit-cart-btn" onclick="addToCartFromDetail({{ $product->id }})">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Tambah ke Keranjang
                </button>

                <button type="button" class="btn btn-outline btn-lg" style="flex: 0 0 auto; white-space: nowrap;" onclick="buyNow({{ $product->id }})">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    Beli Sekarang
                </button>

                @auth
                    <button type="button" class="favorite-toggle-btn" title="Simpan ke Wishlist" onclick="toggleWishlist({{ $product->id }})">
                        <svg viewBox="0 0 24 24" fill="{{ $isInWishlist ?? false ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color: {{ $isInWishlist ?? false ? 'var(--red)' : 'inherit' }};"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </button>
                @endauth
            </div>
        </form>

        {{-- TECHNICAL SPECIFICATION SHEET --}}
        <table class="technical-specs-table">
            <tr>
                <td>Manufaktur</td>
                <td>{{ $product->manufacturer ?? '-' }}</td>
            </tr>
            <tr>
                <td>Material Pad</td>
                <td>{{ $product->material ?? '-' }}</td>
            </tr>
            <tr>
                <td>Rekomendasi Batas Pakai</td>
                <td>{{ $product->lifetime ?? '-' }}</td>
            </tr>
            <tr>
                <td>Berat Total</td>
                <td>{{ $product->weight ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div> {{-- Penutup detail-grid-layout --}}

{{-- RECOMMENDATIONS - FULL WIDTH --}}
@if(isset($recommendations) && $recommendations->count() > 0)
<div style="margin-top: 3rem;">
    <h2 class="sec-title" style="font-size: 1.5rem; margin-bottom: 1.5rem;">Produk Serupa Lainnya</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
        @foreach($recommendations as $rec)
        <a href="{{ route('produk.show', $rec->id) }}" style="text-decoration: none; color: inherit; background: #111110; border: 1px solid rgba(242, 239, 230, 0.05); border-radius: 4px; padding: 1rem; display: flex; flex-direction: column; transition: all 0.2s; height: 100%;">
            <div style="aspect-ratio: 4/3; background: #151513; border-radius: 2px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.75rem; overflow: hidden; padding: 0.25rem;">
                @if($rec->images->first())
                    <img src="{{ asset('storage/' . $rec->images->first()->image_path) }}" alt="{{ $rec->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                @else
                    <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="var(--gray-mid)" stroke-width="1" opacity="0.3"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                @endif
            </div>
            <div style="font-family: var(--font-mono); font-size: 0.65rem; color: var(--orange); letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 0.25rem;">{{ $rec->brand }}</div>
            <div style="font-weight: 500; font-size: 0.85rem; color: var(--off-white); line-height: 1.3; margin-bottom: 0.5rem; flex: 1;">{{ Str::limit($rec->name, 40) }}</div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: auto;">
                <div style="font-family: var(--font-mono); font-size: 0.95rem; font-weight: 600; color: var(--off-white);">
                    Rp {{ number_format($rec->prices->first()?->price ?? $rec->base_price ?? 0, 0, ',', '.') }}
                </div>
                <button type="button" onclick="event.preventDefault(); addToCartFromDetail({{ $rec->id }})" style="width: 32px; height: 32px; background: rgba(232, 82, 26, 0.1); border: 1px solid rgba(232, 82, 26, 0.2); border-radius: 3px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; color: var(--orange);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </button>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

</div> {{-- Penutup page-wrapper --}}
@endsection

@push('scripts')
<script>
    const qtyInput = document.getElementById('purchaseQty');
    const maxStock = parseInt(qtyInput.getAttribute('max')) || 24;

    function incrementQty() {
        let current = parseInt(qtyInput.value);
        if (current < maxStock) {
            qtyInput.value = current + 1;
        }
    }

    function decrementQty() {
        let current = parseInt(qtyInput.value);
        if (current > 1) {
            qtyInput.value = current - 1;
        }
    }

    function addToCartFromDetail(productId) {
        const qty = parseInt(qtyInput.value) || 1;

        fetch(`/customer/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ qty: qty }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message || 'Produk masuk keranjang');
            } else {
                showToast('error', data.message || 'Gagal menambahkan produk');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('error', 'Terjadi kesalahan jaringan.');
        });
    }

    function buyNow(productId) {
        const qty = parseInt(qtyInput.value) || 1;

        fetch(`/buy-now/${productId}?qty=${qty}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            } else {
                showToast('error', data.message || 'Gagal memulai pembelian langsung.');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('error', 'Terjadi kesalahan jaringan.');
        });
    }

    function toggleWishlist(productId) {
        fetch(`/customer/wishlist/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: '',
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showToast('error', data.message || 'Gagal memperbarui wishlist.');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('error', 'Terjadi kegagalan jaringan.');
        });
    }
</script>
@endpush