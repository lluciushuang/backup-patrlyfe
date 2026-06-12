<?php $__env->startSection('title', 'Account Settings'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ── PROFILE INDUSTRIAL GRID LAYOUT ── */
    .account-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2.5rem;
        align-items: flex-start;
        margin-top: 1rem;
    }

    /* ── SIDEBAR SLICK CYBER BLOCKS ── */
    .account-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .account-card {
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 4px;
        overflow: hidden;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .profile-avatar-wrap {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
    }
    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #181815;
        border: 2px solid var(--orange);
        box-shadow: 0 0 15px rgba(232, 82, 26, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-display);
        font-size: 1.8rem;
        color: var(--orange);
        letter-spacing: 0.05em;
    }
    .avatar-verified-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 22px;
        height: 22px;
        background: var(--orange);
        border: 2px solid #0d0d0b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
    }
    .profile-meta-name {
        font-family: var(--font-display);
        font-size: 1.5rem;
        letter-spacing: 0.03em;
        text-align: center;
        color: var(--off-white);
        line-height: 1.2;
    }
    .profile-meta-sub {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--gray-mid);
        text-align: center;
        margin-top: 0.25rem;
        letter-spacing: 0.02em;
    }

    /* Sidebar Navigation Menu */
    .account-nav {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-top: 1.5rem;
    }
    .anav-link {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 1rem;
        color: var(--gray-light);
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        border-radius: 3px;
        transition: all 0.2s ease;
        border-left: 2px solid transparent;
    }
    .anav-link svg {
        width: 18px;
        height: 18px;
        opacity: 0.6;
        transition: opacity 0.2s;
    }
    .anav-link:hover {
        background: rgba(242, 239, 230, 0.03);
        color: var(--off-white);
    }
    .anav-link:hover svg {
        opacity: 1;
    }
    .anav-link.active {
        background: rgba(232, 82, 26, 0.07);
        color: var(--orange);
        font-weight: 600;
        border-left-color: var(--orange);
    }
    .anav-link.active svg {
        opacity: 1;
        color: var(--orange);
    }
    .anav-link.logout-btn {
        color: var(--red);
        margin-top: 1rem;
        border-top: 1px solid rgba(242, 239, 230, 0.05);
        padding-top: 1rem;
        border-radius: 0;
    }
    .anav-link.logout-btn:hover {
        background: rgba(231, 76, 60, 0.05);
        color: var(--red);
    }

    /* ── MAIN CONTENT WORKBENCH ── */
    .account-main {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .main-section-title {
        font-family: var(--font-display);
        font-size: 2rem;
        letter-spacing: 0.04em;
        color: var(--off-white);
        margin-bottom: 0.5rem;
    }

    /* Dashboard UI Grid Blocks */
    .profile-dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 1100px) {
        .profile-dashboard-grid { grid-template-columns: 1fr; }
    }

    /* Container Card Base */
    .profile-form-card {
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 4px;
        padding: 1.75rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .profile-form-card.full-width {
        grid-column: 1 / -1;
    }
    .pfc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(242, 239, 230, 0.06);
        padding-bottom: 0.85rem;
        margin-bottom: 1.5rem;
    }
    .pfc-title {
        font-family: var(--font-display);
        font-size: 1.3rem;
        letter-spacing: 0.05em;
        color: var(--off-white);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .pfc-title svg {
        width: 18px;
        height: 18px;
        color: var(--orange);
    }

    /* Progress Tracker Bar */
    .progress-pill-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(46, 204, 113, 0.08);
        border: 1px solid rgba(46, 204, 113, 0.2);
        padding: 0.25rem 0.6rem;
        border-radius: 2px;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        color: var(--green);
        font-weight: 700;
    }

    /* ── FORMS AND INPUT CONTROLS ── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }
    .form-group.full-span {
        grid-column: 1 / -1;
    }
    .form-label {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--gray-mid);
    }
    .input-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-icon-wrapper svg {
        position: absolute;
        left: 1rem;
        width: 16px;
        height: 16px;
        color: var(--gray-mid);
        pointer-events: none;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        background: #161614;
        border: 1px solid rgba(242, 239, 230, 0.08);
        border-radius: 3px;
        color: var(--off-white);
        font-family: var(--font-body);
        font-size: 0.88rem;
        outline: none;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: var(--orange);
        background: #1a1a17;
        box-shadow: 0 0 10px rgba(232, 82, 26, 0.1);
    }
    .form-control:disabled {
        background: #0d0d0b;
        color: var(--gray-mid);
        border-color: rgba(242, 239, 230, 0.03);
        cursor: not-allowed;
    }
    textarea.form-control {
        padding: 0.75rem 1rem;
        padding-left: 1rem;
        resize: vertical;
    }
    
    /* Small Badge Inside Input Area */
    .input-inner-badge {
        position: absolute;
        right: 1rem;
        font-family: var(--font-mono);
        font-size: 0.65rem;
        padding: 0.15rem 0.4rem;
        border-radius: 2px;
        pointer-events: none;
    }
    .input-inner-badge.success { background: rgba(46,204,113,0.15); color: var(--green); }
    .input-inner-badge.warning { background: rgba(232,82,26,0.15); color: var(--orange); }

    /* Action Footer Rows */
    .form-save-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
        padding-top: 1.2rem;
        border-top: 1px solid rgba(242, 239, 230, 0.05);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php if(auth()->guard()->check()): ?>
<div class="page-wrapper"> 
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
        <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline btn-sm" style="background: var(--orange); color: var(--off-white); border: none; font-size: 0.85rem; padding: 0.5rem 1rem;">
            ← KEMBALI KE PRODUK
        </a>
    </div>
    <div class="breadcrumb">
        <a href="<?php echo e(route('home')); ?>">HOME</a> <span>/</span> <a href="#">ACCOUNT</a> <span>/</span> PROFILE
    </div>

<div class="account-layout">
    
    
    <aside class="account-sidebar">
        <div class="account-card">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></div>
                <div class="avatar-verified-badge" title="Verified Account">✓</div>
            </div>
            <h2 class="profile-meta-name"><?php echo e(Auth::user()->name); ?></h2>
            <div class="profile-meta-sub">ID: PL-<?php echo e(Auth::user()->id); ?>-2026</div>

            <nav class="account-nav">
                <a href="#" class="anav-link active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profile Settings
                </a>
                <a href="<?php echo e(route('transactions.index')); ?>" class="anav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    My Orders
                </a>
                <a href="<?php echo e(route('wishlist.index')); ?>" class="anav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.0 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.0 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.0 0 0 0 0-7.78z"/></svg>
                    My Wishlist
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin-top: 1rem; border-top: 1px solid rgba(242, 239, 230, 0.05); padding-top: 1rem; border-radius: 0;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="anav-link logout-btn" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Log Out
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    
    <main class="account-main">
        <div>
            <h1 class="main-section-title">ACCOUNT SETTINGS</h1>
        </div>

<div class="profile-dashboard-grid">
            
            
            <div class="profile-form-card">
                <div class="pfc-header">
                    <div class="pfc-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-16V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Informasi Kontak
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-grid">
                        <div class="form-group full-span">
                            <label class="form-label">Nama Lengkap</label>
                            <div class="input-icon-wrapper">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <input type="text" name="name" class="form-control" value="<?php echo e(Auth::user()->name); ?>" placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>

                        <div class="form-group full-span">
                            <label class="form-label">Nomor Telepon</label>
                            <div class="input-icon-wrapper">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <input type="text" name="phone" class="form-control" value="<?php echo e(Auth::user()->phone); ?>" placeholder="Masukkan nomor HP">
                                <span class="input-inner-badge success">Verified</span>
                            </div>
                        </div>

                        <div class="form-group full-span">
                            <label class="form-label">Alamat Email</label>
                            <div class="input-icon-wrapper">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <input type="email" class="form-control" value="<?php echo e(Auth::user()->email); ?>" placeholder="Alamat Email" disabled>
                                <span class="input-inner-badge success">Primary</span>
                            </div>
                        </div>

                        <div class="form-group full-span">
                            <label class="form-label">Alamat Lengkap</label>
                            <div class="input-icon-wrapper">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap"><?php echo e(Auth::user()->address); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-save-row">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            
            <div class="profile-form-card">
                <div class="pfc-header">
                    <div class="pfc-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px; color: var(--orange);"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        Ringkasan Akun
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <!-- Stat 1: Total Transaksi -->
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 1rem; border-bottom: 1px dashed rgba(242, 239, 230, 0.05);">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 42px; height: 42px; border-radius: 4px; background: rgba(232, 82, 26, 0.1); display: flex; align-items: center; justify-content: center; color: var(--orange);">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 0.88rem; color: var(--off-white); font-weight: 500;">Total Transaksi</div>
                                <div style="font-size: 0.72rem; color: var(--gray-mid); font-family: var(--font-mono); margin-top: 0.2rem;">Riwayat belanja Anda</div>
                            </div>
                        </div>
                        <div style="font-family: var(--font-display); font-size: 1.4rem; color: var(--off-white);"><?php echo e($transactionCount ?? 0); ?></div>
                    </div>

                    <!-- Stat 2: Wishlist -->
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 1rem; border-bottom: 1px dashed rgba(242, 239, 230, 0.05);">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 42px; height: 42px; border-radius: 4px; background: rgba(46, 204, 113, 0.1); display: flex; align-items: center; justify-content: center; color: var(--green);">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 0.88rem; color: var(--off-white); font-weight: 500;">Produk Wishlist</div>
                                <div style="font-size: 0.72rem; color: var(--gray-mid); font-family: var(--font-mono); margin-top: 0.2rem;">Item tersimpan</div>
                            </div>
                        </div>
                        <div style="font-family: var(--font-display); font-size: 1.4rem; color: var(--off-white);"><?php echo e($wishlistCount ?? 0); ?></div>
                    </div>

                    <!-- Info Keamanan -->
                    <div style="background: #151513; border: 1px solid rgba(242, 239, 230, 0.05); padding: 1.25rem; border-radius: 4px; margin-top: 0.5rem;">
                        <div style="font-size: 0.7rem; color: var(--gray-mid); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.75rem; font-family: var(--font-mono);">Keamanan Akun</div>
                        <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem; color: var(--green); margin-bottom: 1rem;">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span style="font-weight: 500;">Password terlindungi</span>
                        </div>
<a href="<?php echo e(route('password.request')); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--orange); text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--orange)'">
                             Ubah Kata Sandi
                             <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                         </a>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/profile.blade.php ENDPATH**/ ?>