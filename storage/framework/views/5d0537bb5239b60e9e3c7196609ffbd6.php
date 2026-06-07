<?php $__env->startSection('title', 'Invoice ' . ($transaction->invoice_number ?? '')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="breadcrumb">
        <a href="<?php echo e(route('home')); ?>">HOME</a> <span>/</span> <a href="<?php echo e(route('transactions.index')); ?>">TRANSAKSI</a> <span>/</span> INVOICE
    </div>

    <div class="co-section" style="background: var(--surface-2); max-width: 800px; margin: 2rem auto; padding: 2.5rem;">
        <?php
            $isPaid = in_array($transaction->status, ['processing', 'shipped', 'delivered', 'done']);
            $statusText = $isPaid ? 'LUNAS' : ($transaction->status == 'cancelled' ? 'DIBATALKAN' : 'PENDING');
            $statusColor = $isPaid ? 'var(--green)' : ($transaction->status == 'cancelled' ? 'var(--red)' : 'var(--orange)');
        ?>
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <h1 style="font-family: var(--font-display); font-size: 2rem; letter-spacing: 0.05em; color: var(--off-white); margin-bottom: 0.25rem;">PARTLYFE</h1>
                <p style="color: var(--gray-mid); font-size: 0.85rem;">Distributor Sparepart Motor Terpercaya</p>
            </div>
            <div style="text-align: right;">
                <h2 style="font-family: var(--font-display); font-size: 1.5rem; letter-spacing: 0.05em; color: var(--off-white); margin-bottom: 0.5rem;">INVOICE</h2>
                <p style="font-family: var(--font-mono); color: var(--gray-mid); margin-bottom: 0.5rem;"><?php echo e($transaction->invoice_number); ?></p>
                <span style="display: inline-block; padding: 0.35rem 0.85rem; background: <?php echo e($statusColor); ?>20; color: <?php echo e($statusColor); ?>; border-radius: 4px; font-size: 0.75rem; font-weight: 600;"><?php echo e($statusText); ?></span>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(242,239,230,0.08);">
            <div>
                <p style="font-size: 0.7rem; color: var(--gray-mid); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.75rem;">Ditagihkan Kepada:</p>
                <p style="font-weight: 600; color: var(--off-white); margin-bottom: 0.25rem;"><?php echo e(Auth::user()->name); ?></p>
                <p style="color: var(--gray-mid); font-size: 0.85rem; margin-bottom: 0.15rem;"><?php echo e(Auth::user()->phone ?? '-'); ?></p>
                <p style="color: var(--gray-mid); font-size: 0.85rem;"><?php echo e(Auth::user()->email); ?></p>
            </div>
            <div style="text-align: right;">
                <p style="font-size: 0.7rem; color: var(--gray-mid); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.75rem;">Tanggal Transaksi:</p>
                <p style="color: var(--off-white); font-size: 0.85rem;"><?php echo e($transaction->created_at->timezone('Asia/Jakarta')->format('d F Y H:i')); ?> WIB</p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2.5rem;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(242,239,230,0.08);">
                    <th style="text-align: left; padding: 1rem 0.5rem; font-size: 0.8rem; color: var(--gray-mid); text-transform: uppercase; font-weight: 600;">Nama Barang</th>
                    <th style="text-align: center; padding: 1rem 0.5rem; font-size: 0.8rem; color: var(--gray-mid); text-transform: uppercase; font-weight: 600;">Qty</th>
                    <th style="text-align: right; padding: 1rem 0.5rem; font-size: 0.8rem; color: var(--gray-mid); text-transform: uppercase; font-weight: 600;">Harga</th>
                    <th style="text-align: right; padding: 1rem 0.5rem; font-size: 0.8rem; color: var(--gray-mid); text-transform: uppercase; font-weight: 600;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $transaction->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr style="border-bottom: 1px solid rgba(242,239,230,0.05);">
                    <td style="padding: 1rem 0.5rem; color: var(--off-white);"><?php echo e($detail->product->name ?? 'Produk tidak tersedia'); ?></td>
                    <td style="padding: 1rem 0.5rem; text-align: center; color: var(--gray-mid);"><?php echo e($detail->qty); ?></td>
                    <td style="padding: 1rem 0.5rem; text-align: right; font-family: var(--font-mono); color: var(--gray-mid);">Rp <?php echo e(number_format($detail->price, 0, ',', '.')); ?></td>
                    <td style="padding: 1rem 0.5rem; text-align: right; font-family: var(--font-mono); color: var(--off-white);">Rp <?php echo e(number_format($detail->qty * $detail->price, 0, ',', '.')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div style="display: flex; justify-content: flex-end;">
            <div style="width: 280px;">
                <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(242,239,230,0.08);">
                    <span style="color: var(--gray-mid);">Subtotal</span>
                    <span style="font-family: var(--font-mono);">Rp <?php echo e(number_format($transaction->total_amount, 0, ',', '.')); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 1.25rem 0; margin-top: 0.5rem; border-top: 2px solid var(--orange);">
                    <span style="font-family: var(--font-display); font-size: 1.2rem; color: var(--off-white);">TOTAL</span>
                    <span style="font-family: var(--font-mono); font-size: 1.3rem; color: var(--orange);">Rp <?php echo e(number_format($transaction->total_amount, 0, ',', '.')); ?></span>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(242,239,230,0.08); color: var(--gray-mid); font-size: 0.85rem; line-height: 1.8;">
            <p style="margin-bottom: 0.25rem;">Terima kasih telah berbelanja di Partlyfe.</p>
            <p>Simpan nota ini sebagai bukti transaksi Anda yang sah.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/invoice.blade.php ENDPATH**/ ?>