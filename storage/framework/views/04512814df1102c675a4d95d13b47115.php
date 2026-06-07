<?php $__env->startSection('title', 'Manajemen Pesanan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .order-status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 600;
    }
    .status-pending { background: rgba(255,193,7,0.2); color: #ffc107; }
    .status-processing { background: rgba(0,123,255,0.2); color: #007bff; }
    .status-shipped { background: rgba(40,167,69,0.2); color: #28a745; }
    .status-delivered { background: rgba(46,204,113,0.2); color: var(--green); }
    .status-cancelled { background: rgba(220,53,69,0.2); color: #dc3545; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-header">
        <h2 class="admin-page-title">Manajemen Pesanan</h2>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loop->iteration + ($orders->currentPage() - 1) * $orders->perPage()); ?></td>
                    <td><?php echo e($order->user->name ?? 'N/A'); ?></td>
                    <td>Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></td>
                    <td>
                        <span class="order-status-badge status-<?php echo e($order->status); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </td>
                    <td><?php echo e($order->created_at->format('d/m/Y')); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" class="btn btn-sm">Detail</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:var(--text-muted);">Belum ada pesanan</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($orders->hasPages()): ?>
    <div style="margin-top:1.5rem;">
        <?php echo e($orders->links()); ?>

    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>