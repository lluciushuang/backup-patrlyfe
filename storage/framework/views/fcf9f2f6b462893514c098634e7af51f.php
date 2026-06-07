<?php $__env->startSection('title', 'Riwayat Transaksi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .tx-container { margin-top: 1rem; }
    .tx-header {
        border-bottom: 1px solid rgba(242, 239, 230, 0.06);
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .tx-title {
        font-family: var(--font-display);
        font-size: 2.2rem;
        letter-spacing: 0.03em;
        color: var(--off-white);
    }
    .tx-list { display: flex; flex-direction: column; gap: 1rem; }
    .tx-card {
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 4px;
        padding: 1.25rem;
        display: grid;
        grid-template-columns: 1fr auto auto auto;
        gap: 1.5rem;
        align-items: center;
    }
    @media (max-width: 1024px) {
        .tx-card { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 640px) {
        .tx-card { grid-template-columns: 1fr; }
    }
    .tx-id {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--orange);
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }
    .tx-name { font-weight: 600; font-size: 0.95rem; color: var(--off-white); }
    .tx-status {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.3rem 0.7rem;
        border-radius: 2px;
        text-align: center;
    }
    .tx-status.pending { background: rgba(241, 196, 15, 0.15); color: #F1C40F; }
    .tx-status.processing { background: rgba(52, 152, 219, 0.15); color: #3498DB; }
    .tx-status.shipped { background: rgba(155, 89, 182, 0.15); color: #9B59B6; }
    .tx-status.delivered { background: rgba(46, 204, 113, 0.15); color: var(--green); }
    .tx-status.done { background: rgba(108, 117, 125, 0.15); color: #6c757d; }
    .tx-status.cancelled { background: rgba(231, 76, 60, 0.15); color: var(--red); }
    .tx-total { font-family: var(--font-mono); font-size: 1rem; font-weight: 700; color: var(--off-white); text-align: right; }
    .tx-date { font-family: var(--font-mono); font-size: 0.75rem; color: var(--gray-mid); text-align: right; }

    .filter-tabs { display: flex; gap: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .filter-tab {
        padding: 0.5rem 1rem; border-radius: 2px; font-size: 0.82rem;
        border: 1px solid rgba(242,239,230,0.1); background: var(--surface);
        color: var(--gray-light); text-decoration: none; transition: all 0.2s;
    }
    .filter-tab:hover { border-color: var(--orange); color: var(--off-white); }
    .filter-tab.active { background: var(--orange); border-color: var(--orange); color: white; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
        <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline btn-sm" style="background: var(--orange); color: var(--off-white); border: none; font-size: 0.85rem; padding: 0.5rem 1rem;">
            ← KEMBALI KE PRODUK
        </a>
    </div>
    <div class="tx-container">
        <div class="breadcrumb">
            <a href="<?php echo e(route('home')); ?>">HOME</a> <span>/</span> TRANSAKSI
        </div>

    <div class="tx-header">
        <h1 class="tx-title">RIWAYAT TRANSAKSI</h1>
    </div>

    <div class="filter-tabs">
        <a href="<?php echo e(route('transactions.index')); ?>" class="filter-tab <?php echo e(!$statusFilter ? 'active' : ''); ?>">Semua</a>
        <a href="<?php echo e(route('transactions.index', ['status' => 'menunggu'])); ?>" class="filter-tab <?php echo e($statusFilter == 'menunggu' ? 'active' : ''); ?>">Menunggu</a>
        <a href="<?php echo e(route('transactions.index', ['status' => 'diproses'])); ?>" class="filter-tab <?php echo e($statusFilter == 'diproses' ? 'active' : ''); ?>">Diproses</a>
        <a href="<?php echo e(route('transactions.index', ['status' => 'gagal'])); ?>" class="filter-tab <?php echo e($statusFilter == 'gagal' ? 'active' : ''); ?>">Gagal/Dibatalkan</a>
    </div>

    <?php if($transactions->isEmpty()): ?>
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <h3 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 0.5rem;">BELUM ADA TRANSAKSI</h3>
        <p>Anda belum memiliki transaksi apapun.</p>
        <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-primary btn-sm" style="margin-top: 1rem;">Mulai Belanja</a>
    </div>
    <?php else: ?>

    <div class="tx-list">
        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('transactions.invoice', $tx->invoice_number)); ?>" class="tx-card" style="text-decoration: none; color: inherit;">
            <div>
                <div class="tx-id"><?php echo e($tx->invoice_number); ?></div>
                <div class="tx-date"><?php echo e($tx->created_at->format('d M Y, H:i')); ?></div>
            </div>
            <div>
                <div class="tx-name"><?php echo e($tx->details->count()); ?> Produk</div>
                <div style="font-size: 0.72rem; color: var(--gray-mid);"><?php echo e($tx->details->sum('qty')); ?> unit total</div>
            </div>
            <div>
                <span class="tx-status <?php echo e($tx->status); ?>"><?php echo e(strtoupper(str_replace('_', ' ', $tx->status))); ?></span>
            </div>
            <div>
                <div class="tx-total">Rp <?php echo e(number_format($tx->total_amount, 0, ',', '.')); ?></div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div style="display: flex; justify-content: center; margin-top: 2rem;">
        <?php echo e($transactions->links()); ?>

    </div>
    <?php endif; ?>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/transactions/index.blade.php ENDPATH**/ ?>