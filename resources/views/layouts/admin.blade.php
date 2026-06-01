<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Partlyfe')</title>
    <style>
        :root {
            --orange: #E8521A;
            --bg-dark: #0A0A08;
            --surface: #111110;
            --surface-2: #1A1A16;
            --text-main: #F2EFE6;
            --text-muted: #888880;
            --border: rgba(242, 239, 230, 0.08);
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
    </style>
    @stack('styles')
</head>
<body>
    <aside class="admin-sidebar">
        <div class="admin-brand">PARTLYFE ADMIN</div>
        <nav class="admin-nav">
            <a href="/admin/dashboard" class="nav-item">Dashboard Overview</a>
            <a href="/admin/pos" class="nav-item">Sistem POS Kasir</a>
            <a href="/admin/products" class="nav-item">Manajemen Produk</a>
            <a href="/admin/broadcast" class="nav-item">Kirim Broadcast</a>
        </nav>
    </aside>
    <main class="admin-main">
        <header class="admin-header">
            <div style="font-weight: bold; letter-spacing: 0.05em;">Admin Control Panel</div>
            <button class="btn" style="background:transparent; border:1px solid var(--border); color:var(--text-main);" onclick="window.location.href='/login-ui'">Logout Akun</button>
        </header>
        <div class="admin-content">
            @yield('content')
        </div>
    </main>
    @stack('scripts')
</body>
</html>