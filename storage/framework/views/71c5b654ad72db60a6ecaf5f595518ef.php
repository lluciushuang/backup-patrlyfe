<?php $__env->startSection('title', 'Keranjang Belanja'); ?>

<?php $__env->startPush('styles'); ?>
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
        overflow: hidden;
        padding: 0.25rem;
    }
    .cart-item-img-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
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

    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--gray-mid);
    }
    .empty-cart svg {
        width: 80px;
        height: 80px;
        opacity: 0.3;
        margin-bottom: 1.5rem;
    }
    .empty-cart-title {
        font-family: var(--font-display);
        font-size: 1.8rem;
        color: var(--off-white);
        margin-bottom: 0.5rem;
    }

    .stock-warning {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: var(--red);
        font-size: 0.75rem;
        margin-top: 0.25rem;
        font-family: var(--font-mono);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
        <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline btn-sm" style="background: var(--orange); color: var(--off-white); border: none; font-size: 0.85rem; padding: 0.5rem 1rem;">
            ← KEMBALI KE PRODUK
        </a>
    </div>
    <div class="breadcrumb">
        <a href="<?php echo e(route('home')); ?>">HOME</a> <span>/</span> KERANJANG
    </div>

<div class="cart-layout">
    <div style="display: flex; flex-direction: column;">
        <div class="cart-header-row">
            <h1 class="cart-page-title">SHOPPING CART</h1>
            <span class="cart-count-badge">
                <?php echo e($cartItems->count()); ?> ITEM<?php echo e($cartItems->count() !== 1 ? 'S' : ''); ?>

            </span>
        </div>

        <?php if($cartItems->isEmpty()): ?>
        <div class="empty-cart">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <h3 class="empty-cart-title">KERANJANG KOSONG</h3>
            <p style="margin-bottom: 2rem;">Belum ada produk di keranjang Anda.</p>
            <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-primary">Lihat Katalog Produk</a>
        </div>
        <?php else: ?>

        <div class="cart-item-card" style="background: rgba(242,239,230,0.02); border: none; padding: 0.75rem 1.25rem;">
            <label class="check-wrapper">
                <input type="checkbox" checked style="accent-color: var(--orange);">
                <span>Pilih Semua Barang</span>
            </label>
            <span></span>
        </div>

        <div class="cart-items-stack" id="cartItemsContainer">
            <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $originalPrice = $item->product->prices->where('price_level', 1)->first()?->price ?? $item->product->base_price ?? 0;
                $displayPrice = $item->product->prices->where('price_level', $tierPriceLevel)->first()?->price ?? $originalPrice;
                $itemSubtotal = $displayPrice * $item->qty;
            ?>
            <div class="cart-item-card" data-cart-id="<?php echo e($item->id); ?>" data-product-id="<?php echo e($item->product_id); ?>">
                <input type="checkbox" checked style="accent-color: var(--orange); cursor: pointer;">

                <div class="cart-item-img-box">
                    <?php if($item->product->images->count() > 0): ?>
                        <img src="<?php echo e(asset('storage/' . $item->product->images->first()->image_path)); ?>" alt="<?php echo e($item->product->name); ?>">
                    <?php else: ?>
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.4"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    <?php endif; ?>
                </div>

                <div class="cart-item-details">
                    <div>
                        <span class="ci-meta-brand"><?php echo e($item->product->brand); ?></span>
                        <h3 class="ci-meta-name"><?php echo e($item->product->name); ?></h3>
                        <div class="ci-price-row">
                            <?php if($tierPriceLevel > 1 && $displayPrice < $originalPrice): ?>
                                <span class="ci-price-old">Rp <?php echo e(number_format($originalPrice, 0, ',', '.')); ?></span>
                            <?php endif; ?>
                            <span class="ci-price" id="price-<?php echo e($item->id); ?>">Rp <?php echo e(number_format($displayPrice, 0, ',', '.')); ?></span>
                        </div>
                        <?php if($item->qty > $item->product->current_stock): ?>
                            <div class="stock-warning">
                                ⚠ Stok tersisa: <?php echo e($item->product->current_stock); ?> pcs
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="ci-controls-wrapper">
                        <div class="qty-stepper">
                            <button class="qty-btn" onclick="updateCartQty(<?php echo e($item->id); ?>, <?php echo e($item->qty - 1); ?>, this)" <?php echo e($item->qty <= 1 ? 'disabled' : ''); ?>>−</button>
                            <div class="qty-val" id="qty-<?php echo e($item->id); ?>"><?php echo e($item->qty); ?></div>
                            <button class="qty-btn" onclick="updateCartQty(<?php echo e($item->id); ?>, <?php echo e($item->qty + 1); ?>, this)" <?php echo e($item->qty >= $item->product->current_stock ? 'disabled' : ''); ?>>+</button>
                        </div>
                        <button class="ci-action-icon-btn" title="Hapus Item" onclick="removeFromCart(<?php echo e($item->id); ?>)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>

    <aside class="summary-card">
        <h2 class="summary-title">RINGKASAN BELANJA</h2>

        <div class="summary-row">
            <span>Total Harga (<?php echo e($cartItems->sum('qty')); ?> Barang)</span>
            <span style="font-family: var(--font-mono); color: var(--off-white);" id="summary-subtotal">Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
        </div>
        <div class="summary-row">
            <span>Ongkos Kirim</span>
            <span style="font-family: var(--font-mono); color: var(--green);">GRATIS</span>
        </div>

        <div class="free-shipping-pill">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <span>Aman! Belanja di atas Rp 200.000, otomatis gratis ongkir reguler.</span>
        </div>

        <div class="summary-row total-bill">
            <span>Total Harga</span>
            <span class="price-val" id="summary-total">Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
        </div>

        <a href="<?php echo e(route('checkout')); ?>" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1.5rem; justify-content: center; text-align: center;">
            Lanjut ke Checkout
        </a>
    </aside>
</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>';

function updateCartQty(cartId, newQty, btnElement) {
    if (newQty < 1) {
        removeFromCart(cartId);
        return;
    }

    fetch(`/customer/cart/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ qty: newQty }),
    })
    .then(res => res.json())
.then(data => {
                    if (data.success) {
                        document.getElementById(`qty-${cartId}`).textContent = newQty;
                        document.getElementById(`price-${cartId}`).textContent = 'Rp ' + (data.subtotal || 0).toLocaleString('id-ID');
                        if (data.total) {
                            document.getElementById('summary-subtotal').textContent = 'Rp ' + data.total.toLocaleString('id-ID');
                            document.getElementById('summary-total').textContent = 'Rp ' + data.total.toLocaleString('id-ID');
                        }
                    } else {
                        showCartToast('error', 'Gagal', data.message || 'Gagal memperbarui kuantitas');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showCartToast('error', 'Error', 'Terjadi kesalahan jaringan');
                });
}

function showCartToast(type, title, message) {
    const toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;top:80px;right:24px;z-index:9999;background:#111110;border:1px solid rgba(242,239,230,0.1);border-left:3px solid ' + (type==='error'?'#e74c4c':'#2ecc71') + ';padding:1rem 1.25rem;border-radius:4px;color:#F2EFE6;';
    toast.innerHTML = '<div style="font-weight:600;font-size:0.85rem;margin-bottom:0.25rem;">' + title + '</div><div style="font-size:0.75rem;color:#888880;">' + message + '</div>';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function removeFromCart(cartId) {
    if (!confirm('Hapus item ini dari keranjang?')) return;

    fetch(`/customer/cart/${cartId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const card = document.querySelector(`[data-cart-id="${cartId}"]`);
            if (card) {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    card.remove();
                    if (document.getElementById('cartItemsContainer').children.length === 0) {
                        location.reload();
                    }
                }, 300);
            }
        }
    })
    .catch(err => {
        console.error(err);
        showCartToast('error', 'Error', 'Terjadi kesalahan jaringan');
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/cart/index.blade.php ENDPATH**/ ?>