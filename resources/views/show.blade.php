{{-- resources/views/show.blade.php ── CLEAN INDUSTRIAL DETAIL PRODUCT WITH ECO-DATABASE FORM --}}
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
                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: contain; position: relative; z-index: 5;">
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
                    <img src="{{ asset('storage/' . $img->image_path) }}" alt="thumb" style="width: 80%; height: 80%; object-fit: contain;">
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
        
        <h1 class="prod-main-title">
            {{ $product->name ?? 'Kampas Rem Cakram Honda Vario 125 — Depan & Belakang' }}
        </h1>
        
        <div class="prod-rating-meta">
            <span style="color: var(--orange);">⭐⭐⭐⭐⭐ <strong style="color: var(--off-white); margin-left: 0.25rem;">4.9</strong></span>
            <div class="meta-divider"></div>
            <span>{{ number_format($product->reviews_count ?? 1240) }} Ulasan Verified</span>
            <div class="meta-divider"></div>
            <span>Terjual {{ number_format($product->sold_count ?? 8420) }} Unit</span>
        </div>

        <div class="price-plate">
            @php
                $retailPrice = isset($product) ? $product->prices->where('price_level', 1)->first()->price ?? 0 : 79000;
                $basePrice = isset($product) ? $product->base_price : 95000;
            @endphp
            @if($basePrice > $retailPrice)
                <div class="price-was">Rp {{ number_format($basePrice, 0, ',', '.') }}</div>
            @endif
            <div class="price-now">
                Rp {{ number_format($retailPrice, 0, ',', '.') }}
                @if($basePrice > $retailPrice && $basePrice > 0)
                    <span class="discount-pill-badge">HEMAT {{ round((($basePrice - $retailPrice) / $basePrice) * 100) }}%</span>
                @endif
            </div>
        </div>

        {{-- VARIANT SELECTIONS --}}
        <div class="attribute-section">
            <div class="attribute-label">Pilihan Varian Part</div>
            <div class="chip-group">
                <span class="selector-chip active">Depan + Belakang (Set)</span>
                <span class="selector-chip">Depan Saja</span>
            </div>
        </div>

        <div class="attribute-section">
            <div class="attribute-label">Kombinasi Motor</div>
            <div class="chip-group">
                <span class="selector-chip active">Vario 125 (2012 - 2019)</span>
                <span class="selector-chip">Vario 150</span>
            </div>
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
            {{-- Mengirim ID produk tersembunyi untuk backend CRUD proses nanti --}}
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
                <button type="submit" class="btn btn-primary btn-lg submit-cart-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Tambah ke Keranjang
                </button>
                
                <button type="button" class="favorite-toggle-btn" title="Simpan ke Wishlist" onclick="window.location.href='{{ route('wishlist.index') }}'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </button>
            </div>
        </form>

        {{-- TECHNICAL SPECIFICATION SHEET --}}
        <table class="technical-specs-table">
            <tr>
                <td>Manufaktur</td>
                <td>{{ $product->manufacturer ?? 'Brembo Italy Performance' }}</td>
            </tr>
            <tr>
                <td>Material Pad</td>
                <td>{{ $product->material ?? 'Semi-metallic high friction compound' }}</td>
            </tr>
            <tr>
                <td>Rekomendasi Batas Pakai</td>
                <td>{{ $product->lifetime ?? '± 30.000 km (Kondisi Jalan Normal)' }}</td>
            </tr>
            <tr>
                <td>Berat Total</td>
                <td>{{ $product->weight ?? '340 Gram' }}</td>
            </tr>
        </table>

    </div>
</div>
</div>
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
</script>
@endpush