<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .checkout-layout { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; }
    .co-section { background: var(--surface); padding: 1.5rem; border-radius: 4px; border: 1px solid rgba(242,239,230,0.07); margin-bottom: 1rem; }
    .co-title { font-family: var(--font-display); font-size: 1.2rem; border-bottom: 1px solid rgba(242,239,230,0.08); padding-bottom: 0.75rem; margin-bottom: 1rem; }
    .addr-card { padding: 1rem; border: 1.5px solid var(--orange); background: rgba(232,82,26,0.03); border-radius: 3px; }
    .pay-midtrans { display: flex; align-items: center; gap: 1rem; padding: 0.85rem; border: 1px solid rgba(242,239,230,0.1); border-radius: 3px; }
    .pay-midtrans.selected { border-color: var(--orange); background: rgba(232,82,26,0.03); }
    .toast-container {
        position: fixed; top: 80px; right: 24px; z-index: 9999;
        display: flex; flex-direction: column; gap: 0.75rem; pointer-events: none;
    }
    .toast-notif {
        display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem;
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242,239,230,0.08); border-left: 3px solid var(--orange);
        border-radius: 4px; min-width: 280px; max-width: 380px;
        transform: translateX(120%); opacity: 0; transition: all 0.4s ease;
        pointer-events: all; box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .toast-notif.show { transform: translateX(0); opacity: 1; }
    .toast-notif.success { border-left-color: var(--green); }
    .toast-notif.error { border-left-color: var(--red); }
    .toast-icon { width: 20px; height: 20px; flex-shrink: 0; }
    .toast-content { flex: 1; }
    .toast-title { font-family: var(--font-mono); font-size: 0.8rem; font-weight: 600; color: var(--off-white); margin-bottom: 0.15rem; }
    .toast-message { font-size: 0.78rem; color: var(--gray-mid); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="breadcrumb">
        <a href="<?php echo e(route('home')); ?>">HOME</a> <span>/</span> CHECKOUT
    </div>
<div class="checkout-layout">
    <div>
        <h1 class="sec-title">CHECKOUT</h1>

        <div class="co-section">
            <div class="co-title">ALAMAT PENGIRIMAN</div>
            <div class="addr-card">
                <div style="font-weight:600; font-size:0.95rem;"><?php echo e(Auth::user()->name ?? 'Pelanggan'); ?></div>
                <div style="font-size:0.8rem; color:var(--gray-mid); margin-bottom:0.5rem;"><?php echo e(Auth::user()->phone ?? '+62 812-3456-7890'); ?></div>
                <div style="font-size:0.85rem; color:var(--gray-light);"><?php echo e(Auth::user()->address ?? 'Alamat belum diatur. Harap tambahkan alamat pada profil.'); ?></div>
            </div>
        </div>

        <div class="co-section">
            <div class="co-title">DETAIL PEMBELIAN</div>
            <?php if(!empty($checkoutItems)): ?>
                <?php $__currentLoopData = $checkoutItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid rgba(242,239,230,0.05);">
                    <div style="flex: 1;">
                        <div style="font-weight: 500; color: var(--off-white); font-size: 0.9rem;"><?php echo e($item->name); ?></div>
                        <div style="font-size: 0.75rem; color: var(--gray-mid); font-family: var(--font-mono);">
                            <?php if($item->original_price && $item->original_price > $item->price): ?>
                                <span style="text-decoration: line-through; color: var(--gray-mid);">Rp <?php echo e(number_format($item->original_price, 0, ',', '.')); ?></span>
                                &nbsp;<span style="color: var(--orange);">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></span>
                            <?php else: ?>
                                Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?>

                            <?php endif; ?>
                            x <?php echo e($item->qty); ?>

                        </div>
                    </div>
                    <div style="font-family: var(--font-mono); font-weight: 700; color: var(--off-white);">
                        Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <p style="color: var(--gray-mid); text-align: center; padding: 2rem 0;">Tidak ada item untuk checkout.</p>
            <?php endif; ?>
        </div>

        <?php if($tierPriceLevel > 1): ?>
        <div style="background: rgba(232, 82, 26, 0.05); border: 1px solid rgba(232, 82, 26, 0.2); border-radius: 4px; padding: 0.75rem 1rem; margin: 1rem 0; font-size: 0.85rem;">
            <span style="color: var(--orange); font-weight: 600;">Level Harga <?php echo e($tierPriceLevel); ?> berlaku</span>
            <span style="color: var(--gray-mid); margin-left: 0.5rem;">(otomatis diterapkan)</span>
        </div>
        <?php endif; ?>

        <div class="co-section">
            <div class="co-title">METODE PEMBAYARAN</div>
            <div class="pay-midtrans selected">
                <div style="width:8px; height:8px; background:var(--orange); border-radius:50%;"></div>
                <div style="font-size:0.85rem; font-weight:500;">Transfer Bank / E-Wallet (Midtrans)</div>
            </div>
        </div>
    </div>

    <div>
        <div class="co-section" style="background:var(--surface-2);">
            <div class="co-title" style="font-size:1rem;">RINGKASAN ORDER</div>
            <?php
                $originalTotal = 0;
                foreach($checkoutItems as $item) {
                    $originalTotal += ($item->original_price ?? $item->price) * $item->qty;
                }
                $discountAmount = $originalTotal - $total;
            ?>
            <?php if($tierPriceLevel > 1 && $originalTotal > $total): ?>
            <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:0.5rem;">
                <span style="color:var(--gray-mid);">Subtotal</span>
                <span style="font-family: var(--font-mono); text-decoration: line-through;">Rp <?php echo e(number_format($originalTotal, 0, ',', '.')); ?></span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:0.5rem;">
                <span style="color:var(--gray-mid);">Hemat (Level <?php echo e($tierPriceLevel); ?>)</span>
                <span style="font-family: var(--font-mono); color:var(--green);">-Rp <?php echo e(number_format($discountAmount, 0, ',', '.')); ?></span>
            </div>
            <?php endif; ?>
            <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:0.5rem;">
                <span style="color:var(--gray-mid);">Total Items (<?php echo e(is_array($checkoutItems) ? array_sum(array_column($checkoutItems, 'qty')) : ($checkoutItems->sum('qty') ?? 0)); ?> barang)</span>
                <span style="font-family: var(--font-mono);">Rp <?php echo e(number_format($total ?? 0, 0, ',', '.')); ?></span>
            </div>
            <div style="display:flex; justify-content:space-between; font-family:var(--font-display); font-size:1.5rem; border-top:1px solid rgba(242,239,230,0.08); padding-top:0.75rem; margin-top:0.75rem;">
                <span>Total</span>
                <span style="font-family: var(--font-mono); color: var(--orange);">Rp <?php echo e(number_format($total ?? 0, 0, ',', '.')); ?></span>
            </div>
            <?php if(!empty($checkoutItems)): ?>
            <button class="btn btn-primary btn-lg" onclick="initiatePayment()" style="width:100%; justify-content:center; margin-top:1.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px; margin-right: 0.5rem;"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Bayar Sekarang
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
<script>
<?php if(!config('midtrans.client_key')): ?>
console.warn('Midtrans client key tidak dikonfigurasi. Pembayaran tidak akan berfungsi.');
<?php endif; ?>
function showToast(type, title, message) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = 'toast-notif ' + type;
    toast.innerHTML = '<div class="toast-icon">' + (type === 'success' ? 
        '<svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>' :
        '<svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>') + '</div>' +
        '<div class="toast-content"><div class="toast-title">' + title + '</div><div class="toast-message">' + message + '</div></div>';
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 400); }, 3000);
}

function initiatePayment() {
    const data = {};
    const productId = "<?php echo e($productId ?? ''); ?>";
    const qty = "<?php echo e($qty ?? 1); ?>";
    if (productId) { data.product_id = productId; data.qty = qty; }

    fetch('<?php echo e(route("checkout.initiate")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        },
        body: JSON.stringify(data),
    })
.then(res => {
            if (!res.ok) {
                return res.text().then(text => { throw new Error(text || 'HTTP ' + res.status); });
            }
            return res.json();
        })
        .then(response => {
            if (response.status === 'success') {
                snap.pay(response.snap_token, {
                    onSuccess: function(result) {
                        showToast('success', 'Berhasil!', 'Pembayaran berhasil diproses.');
                        setTimeout(() => window.location.href = '<?php echo e(route("transactions.index")); ?>', 1500);
                    },
                    onPending: function(result) {
                        showToast('success', 'Pending', 'Menunggu pembayaran.');
                        setTimeout(() => window.location.href = '<?php echo e(route("transactions.index")); ?>', 1500);
                    },
                    onError: function(result) {
                        showToast('error', 'Gagal', result?.message || 'Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        console.log('Popup ditutup');
                    }
                });
            } else {
                showToast('error', 'Error', response.message || 'Gagal memulai pembayaran.');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('error', 'Error', 'Terjadi kesalahan jaringan. Periksa koneksi atau hubungi admin.');
        });
}
</script>
<div class="toast-container" id="toastContainer"></div>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/checkout.blade.php ENDPATH**/ ?>