<?php $__env->startSection('title', 'Detail Pesanan - Admin'); ?>

<?php $__env->startPush('styles'); ?>
<style>
     :root {
         --orange: #E8521A;
         --off-white: #F2EFE6;
         --gray-mid: #888880;
         --gray-light: #aaa;
         --green: #2ecc71;
         --red: #e74c4c;
     }
    .order-detail-container {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 2rem;
        margin-top: 1rem;
    }
    @media (max-width: 992px) {
        .order-detail-container { grid-template-columns: 1fr; }
    }

    .order-card {
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.07);
        border-radius: 4px;
        padding: 1.75rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .section-header {
        font-family: system-ui, -apple-system, sans-serif;
        font-size: 1.1rem;
        letter-spacing: 0.04em;
        color: #F2EFE6;
        border-bottom: 1px solid rgba(242, 239, 230, 0.08);
        padding-bottom: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.6rem 0;
        border-bottom: 1px dashed rgba(242, 239, 230, 0.04);
        font-size: 0.88rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #888880; }
    .info-value { color: #F2EFE6; font-weight: 500; }

    .product-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(242, 239, 230, 0.05);
    }
    .product-item:last-child { border-bottom: none; }
    .product-img-sm {
        width: 60px;
        height: 60px;
        aspect-ratio: 1;
        background: #151513;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 0.25rem;
    }
    .product-img-sm img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .order-status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-pending { background: rgba(255,193,7,0.2); color: #ffc107; }
    .status-processing { background: rgba(0,123,255,0.2); color: #007bff; }
    .status-shipped { background: rgba(40,167,69,0.2); color: #28a745; }
    .status-delivered { background: rgba(46,204,113,0.2); color: #2ecc71; }
    .status-done { background: rgba(108,117,125,0.2); color: #6c757d; }
    .status-cancelled { background: rgba(220,53,69,0.2); color: #dc3545; }

    .back-btn {
        margin-bottom: 1rem;
    }
    .summary-box {
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-left: 3px solid var(--orange);
        padding: 1.25rem;
        border-radius: 4px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-outline btn-sm back-btn">
        ← KEMBALI KE PESANAN
    </a>

    <div class="order-detail-container">
        <div>
            <div class="order-card">
                <h2 class="section-header">Informasi Pelanggan</h2>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-value"><?php echo e($order->user->name ?? 'N/A'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?php echo e($order->user->email ?? '-'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telepon</span>
                    <span class="info-value"><?php echo e($order->user->phone ?? '-'); ?></span>
                </div>
            </div>

            <div class="order-card" style="margin-top: 1.5rem;">
                <h2 class="section-header">Alamat Pengiriman</h2>
                <div style="font-size: 0.9rem; color: #F2EFE6; line-height: 1.6;">
                    <?php echo e($order->shipping_address ?? 'Alamat belum diisi'); ?>

                </div>
            </div>

            <div class="order-card" style="margin-top: 1.5rem;">
                <h2 class="section-header">Detail Produk</h2>
                <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $price = $detail->product->prices->where('price_level', 1)->first()?->price ?? $detail->product->base_price ?? 0;
                    ?>
                    <div class="product-item">
                        <div class="product-img-sm">
                            <?php if($detail->product->images->first()): ?>
                                <img src="<?php echo e(asset('storage/' . $detail->product->images->first()->image_path)); ?>" alt="<?php echo e($detail->product->name); ?>">
                            <?php else: ?>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#888880" stroke-width="1.2" opacity="0.3"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                            <?php endif; ?>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #F2EFE6; margin-bottom: 0.25rem;"><?php echo e($detail->product->name ?? 'Produk tidak ditemukan'); ?></div>
                            <div style="font-size: 0.75rem; color: #888880; margin-bottom: 0.5rem;"><?php echo e($detail->product->brand ?? '-'); ?></div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                                <span style="color: #888880;">Qty: <?php echo e($detail->qty); ?> x Rp <?php echo e(number_format($price, 0, ',', '.')); ?></span>
                                <span style="color: #E8521A; font-weight: 600;">Rp <?php echo e(number_format($detail->price * $detail->qty, 0, ',', '.')); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <aside>
            <div class="order-card">
                <h2 class="section-header">Ringkasan Pesanan</h2>
                <div class="info-row">
                    <span class="info-label">No. Invoice</span>
                    <span class="info-value" style="font-family: monospace;"><?php echo e($order->invoice_number); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span>
                        <span class="order-status-badge status-<?php echo e($order->status); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </span>
                </div>
                <div class="info-row" style="border-top: 1px solid rgba(242, 239, 230, 0.1); margin-top: 0.5rem; padding-top: 1rem;">
                    <span class="info-label">Total</span>
                    <span class="info-value" style="font-size: 1.2rem; color: #E8521A;">Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></span>
                </div>
            </div>

            <div class="order-card" style="margin-top: 1.5rem;">
                <h2 class="section-header">Ubah Status</h2>
                <select onchange="updateOrderStatus(<?php echo e($order->id); ?>, this.value)" style="width: 100%; padding: 0.75rem; background: #161614; border: 1px solid rgba(242, 239, 230, 0.1); color: #F2EFE6; border-radius: 2px;">
                    <option value="pending" <?php echo e($order->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="processing" <?php echo e($order->status == 'processing' ? 'selected' : ''); ?>>Processing</option>
                    <option value="shipped" <?php echo e($order->status == 'shipped' ? 'selected' : ''); ?>>Shipped</option>
                    <option value="delivered" <?php echo e($order->status == 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                    <option value="done" <?php echo e($order->status == 'done' ? 'selected' : ''); ?>>Done</option>
                    <option value="cancelled" <?php echo e($order->status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
            </div>
        </aside>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function updateOrderStatus(orderId, status) {
    const url = '<?php echo e(route('admin.orders.update-status', ':id')); ?>'.replace(':id', orderId);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            adminToast.fire({ icon: 'error', title: data.message || 'Gagal memperbarui status' });
        }
    })
    .catch(err => {
        console.error('Error:', err);
        adminToast.fire({ icon: 'error', title: 'Terjadi kesalahan jaringan' });
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>