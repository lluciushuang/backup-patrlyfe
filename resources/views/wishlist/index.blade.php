@extends('layouts.app')
@section('title', 'Wishlist Saya')

@push('styles')
<style>
    .wishlist-container { margin-top: 1rem; }
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
        overflow: hidden;
        border-bottom: 1px solid rgba(242, 239, 230, 0.03);
        padding: 0.5rem;
    }
    .wish-img-area img {
        width: 100%;
        height: 100%;
        object-fit: contain;
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
    .empty-wishlist {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--gray-mid);
    }
    .empty-wishlist svg {
        width: 80px;
        height: 80px;
        opacity: 0.3;
        margin-bottom: 1.5rem;
    }
    .toast-container {
        position: fixed;
        top: 80px;
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        pointer-events: none;
    }
    .toast-notif {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242, 239, 230, 0.08);
        border-left: 3px solid var(--orange);
        border-radius: 4px;
        min-width: 280px;
        max-width: 380px;
        transform: translateX(120%);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        pointer-events: all;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    .toast-notif.show {
        transform: translateX(0);
        opacity: 1;
    }
    .toast-notif.success {
        border-left-color: var(--green);
    }
    .toast-notif.error {
        border-left-color: var(--red);
    }
    .toast-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }
    .toast-icon svg {
        width: 100%;
        height: 100%;
    }
    .toast-content {
        flex: 1;
    }
    .toast-title {
        font-family: var(--font-mono);
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--off-white);
        margin-bottom: 0.15rem;
    }
    .toast-message {
        font-size: 0.78rem;
        color: var(--gray-mid);
    }
    .toast-close {
        background: transparent;
        border: none;
        color: var(--gray-mid);
        cursor: pointer;
        font-size: 1.1rem;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .confirm-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .confirm-backdrop.active {
        opacity: 1;
        pointer-events: all;
    }
    .confirm-modal {
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242, 239, 230, 0.1);
        border-radius: 4px;
        padding: 1.75rem;
        width: 340px;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    .confirm-backdrop.active .confirm-modal {
        transform: scale(1);
    }
    .confirm-title {
        font-family: var(--font-display);
        font-size: 1.2rem;
        color: var(--off-white);
        margin-bottom: 0.5rem;
    }
    .confirm-message {
        font-size: 0.85rem;
        color: var(--gray-light);
        margin-bottom: 1.5rem;
    }
    .confirm-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
        <a href="{{ route('produk.index') }}" class="btn btn-outline btn-sm" style="background: var(--orange); color: var(--off-white); border: none; font-size: 0.85rem; padding: 0.5rem 1rem;">
            ← KEMBALI KE PRODUK
        </a>
    </div>
    <div class="wishlist-container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">HOME</a> <span>/</span> WISHLIST
        </div>

    <div class="wishlist-header">
        <h1 class="wishlist-title">MY WISHLIST</h1>
        <span class="wishlist-count">{{ $wishlists->count() }} ITEM{{ $wishlists->count() !== 1 ? 'S' : '' }} SAVED</span>
    </div>

    @if($wishlists->isEmpty())
    <div class="empty-wishlist">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        <h3 class="wishlist-title" style="font-size: 1.5rem;">WISHLIST KOSONG</h3>
        <p style="margin-bottom: 2rem;">Belum ada produk yang disimpan.</p>
        <a href="{{ route('produk.index') }}" class="btn btn-primary">Jelajahi Produk</a>
    </div>
    @else

    <div class="wishlist-grid">
        @foreach($wishlists as $item)
@php
            $price = $item->product->prices->where('price_level', 1)->first()?->price ?? $item->product->base_price ?? 0;
            $isInStock = $item->product->current_stock > 0;
        @endphp
        <div class="wish-card" id="wish-{{ $item->id }}">
            <button class="wish-delete-trigger" onclick="removeFromWishlist({{ $item->id }})" title="Hapus dari Wishlist">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>

            <a href="{{ route('produk.show', $item->product_id) }}" class="wish-img-area" style="text-decoration: none; color: inherit;">
                @if($item->product->images->count() > 0)
                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}">
                @else
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.15"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                @endif
            </a>

            <div class="wish-body">
                <span class="wish-brand">{{ $item->product->brand }}</span>
                <a href="{{ route('produk.show', $item->product_id) }}" style="text-decoration: none; color: inherit;">
                    <h3 class="wish-name">{{ $item->product->name }}</h3>
                </a>

                <div class="wish-stock-status {{ $isInStock ? 'in' : 'out' }}">
                    <span style="font-size: 8px;">●</span>
                    {{ $isInStock ? 'Stok Tersedia (' . $item->product->current_stock . ' pcs)' : 'Stok Habis' }}
                </div>

                <div class="wish-footer">
                    @php
                        $originalPrice = $item->product->prices->where('price_level', 1)->first()?->price ?? $item->product->base_price ?? 0;
                        $displayPrice = $item->product->prices->where('price_level', $tierPriceLevel)->first()?->price ?? $originalPrice;
                    @endphp
                    <span class="wish-price">Rp {{ number_format($displayPrice, 0, ',', '.') }}</span>
                    @if($tierPriceLevel > 1 && $displayPrice < $originalPrice)
                    <div style="font-size: 0.65rem; color: var(--gray-mid); text-decoration: line-through; margin-top: 0.1rem;">Rp {{ number_format($originalPrice, 0, ',', '.') }}</div>
                    @endif
                    @if($isInStock)
                    <button class="wish-add-cart-btn" onclick="addWishlistToCart({{ $item->product_id }})" title="Masukkan Keranjang">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
</div>
@endsection

@push('scripts')
<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

function showToast(type, title, message) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast-notif ${type}`;
    toast.innerHTML = `
        <div class="toast-icon">
            ${type === 'success' ? '<svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>' : '<svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'}
        </div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">×</button>
    `;
    
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

function showConfirm(message, callback) {
    const backdrop = document.getElementById('confirmBackdrop');
    const msgEl = document.getElementById('confirmMessage');
    const confirmBtn = document.getElementById('confirmBtn');
    
    msgEl.textContent = message;
    backdrop.classList.add('active');
    
    const handleConfirm = () => {
        backdrop.classList.remove('active');
        callback(true);
        confirmBtn.removeEventListener('click', handleConfirm);
    };
    
    const handleCancel = () => {
        backdrop.classList.remove('active');
        callback(false);
    };
    
    confirmBtn.addEventListener('click', handleConfirm);
    document.getElementById('cancelBtn').onclick = handleCancel;
}

function addWishlistToCart(productId) {
    fetch(`/customer/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ qty: 1 }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Berhasil!', data.message || 'Produk ditambahkan ke keranjang.');
        } else {
            showToast('error', 'Gagal', data.message || 'Gagal menambahkan ke keranjang.');
        }
    })
    .catch(err => {
        console.error(err);
        showToast('error', 'Error', 'Terjadi kesalahan jaringan.');
    });
}

function removeFromWishlist(wishlistId) {
    showConfirm('Hapus produk ini dari wishlist?', (confirmed) => {
        if (!confirmed) return;

        fetch(`/customer/wishlist/${wishlistId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById(`wish-${wishlistId}`);
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        card.remove();
                        if (document.querySelectorAll('.wish-card').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
                showToast('success', 'Dihapus!', 'Produk berhasil dihapus dari wishlist.');
            } else {
                showToast('error', 'Gagal', data.message || 'Gagal menghapus produk.');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('error', 'Error', 'Terjadi kesalahan jaringan.');
        });
    });
}
</script>

<div class="toast-container" id="toastContainer"></div>
<div class="confirm-backdrop" id="confirmBackdrop">
    <div class="confirm-modal">
        <div class="confirm-title">Konfirmasi</div>
        <div class="confirm-message" id="confirmMessage"></div>
        <div class="confirm-actions">
            <button class="btn btn-outline btn-sm" id="cancelBtn">Batal</button>
            <button class="btn btn-primary btn-sm" id="confirmBtn">Ya, Hapus</button>
        </div>
    </div>
</div>
@endpush