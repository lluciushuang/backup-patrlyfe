<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Partlyfe'); ?></title>
    <style>
        :root {
            --orange: #E8521A;
            --bg-dark: #0A0A08;
            --surface: #111110;
            --surface-2: #1A1A16;
            --text-main: #F2EFE6;
            --text-muted: #888880;
            --off-white: #F2EFE6;
            --gray-mid: #888880;
            --border: rgba(242, 239, 230, 0.08);
            --font-display: 'Bebas Neue', sans-serif;
            --font-mono: 'Space Mono', monospace;
        }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
            background: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        /* ── FONDASI UI ADMIN PARTLYFE ── */
        .admin-page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(242, 239, 230, 0.08);
        }
        .admin-page-title {
            font-size: 1.8rem;
            font-family: var(--font-display, sans-serif);
            color: var(--off-white, #fff);
            margin: 0;
        }

        /* 2. Kartu (Card) untuk membungkus Form & Konten */
        .admin-card {
            background: #151513;
            border: 1px solid rgba(242, 239, 230, 0.08);
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }
        .admin-card-title {
            font-size: 1.1rem;
            color: #E8521A;
            margin-top: 0;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed rgba(242, 239, 230, 0.1);
        }

        /* 3. Tabel yang lebih berdimensi */
        .admin-table-wrapper {
            background: #111110;
            border: 1px solid rgba(242, 239, 230, 0.08);
            border-radius: 8px;
            overflow: hidden;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th {
            background: #1a1a18;
            color: var(--gray-mid, #aaa);
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid rgba(242, 239, 230, 0.1);
        }
        .admin-table td {
            padding: 1rem 1.5rem;
            color: var(--off-white, #eee);
            border-bottom: 1px solid rgba(242, 239, 230, 0.04);
            vertical-align: middle;
        }
        .admin-table tbody tr:hover {
            background: rgba(232, 82, 26, 0.03);
        }

        .admin-sidebar {
            width: 250px;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }
        .admin-brand {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--orange);
            border-bottom: 1px solid var(--border);
        }
        .admin-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }
        .nav-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
        }
        .nav-item:hover, .nav-item.active {
            background: rgba(232, 82, 26, 0.1);
            color: var(--orange);
            border-right: 3px solid var(--orange);
        }
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .admin-header {
            padding: 1rem 2rem;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-content {
            padding: 2rem;
            flex: 1;
        }
        .btn {
            background: var(--orange);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-sm {
            padding: 0.35rem 0.7rem;
            font-size: 0.8rem;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            color: var(--text-main);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 2px rgba(232, 82, 26, 0.15);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: #111110;
            border: 1px solid rgba(242, 239, 230, 0.1);
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .modal-header-custom {
            border-bottom: 1px solid rgba(242, 239, 230, 0.08);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .modal-header-custom h3 {
            margin: 0;
            color: var(--off-white);
            font-family: var(--font-display);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .btn-action {
            padding: 0.4rem 0.8rem;
            font-size: 0.75rem;
            border-radius: 4px;
            margin-right: 0.5rem;
            transition: opacity 0.2s;
        }
        .btn-action:hover { opacity: 0.8; }
        .admin-table td { padding: 1.25rem 1rem; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <aside class="admin-sidebar">
        <div class="admin-brand">PARTLYFE ADMIN</div>
        <nav class="admin-nav">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">Analytics Dashboard</a>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.products.*') ? 'active' : ''); ?>">Manajemen Produk</a>
            <a href="<?php echo e(route('admin.pos.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.pos.*') ? 'active' : ''); ?>">POS Kasir</a>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.orders.*') ? 'active' : ''); ?>">Manajemen Pesanan</a>
            <a href="<?php echo e(route('admin.broadcasts.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.broadcasts.*') ? 'active' : ''); ?>">Kirim Broadcast</a>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">Manajemen Pengguna</a>
            <a href="<?php echo e(route('admin.reports.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.reports.*') ? 'active' : ''); ?>">Laporan</a>
        </nav>
    </aside>
    <main class="admin-main">
        <header class="admin-header">
            <div style="font-weight: bold; letter-spacing: 0.05em;">Admin Control Panel</div>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn" style="background:transparent; border:1px solid var(--border); color:var(--text-main);">Logout Akun</button>
            </form>
        </header>
        <div class="admin-content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if(config('midtrans.client_key')): ?>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
    <?php endif; ?>
    <script>
        window.adminToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#111110',
            color: '#F2EFE6',
            iconColor: '#E8521A'
        });
    </script>
</body>
</html><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/layouts/admin.blade.php ENDPATH**/ ?>