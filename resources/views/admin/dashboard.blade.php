@extends('layouts.admin')
@section('title', 'Analytics Dashboard')

@push('styles')
<style>
    /* ── 1. FONDASI GRID & CARDS (TAB ATAS) ── */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: #151513;
        border: 1px solid rgba(242, 239, 230, 0.08);
        border-radius: 6px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); border-color: rgba(232, 82, 26, 0.4); }
    .stat-card.active { border-color: #E8521A; background: linear-gradient(145deg, #1a1512 0%, #151513 100%); }
    .stat-card.active::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: #E8521A; }

    .stat-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
    .stat-title { color: var(--gray-mid); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; font-family: var(--font-mono); }
    .stat-icon { width: 40px; height: 40px; border-radius: 4px; display: flex; align-items: center; justify-content: center; }
    .stat-value { font-size: 2.5rem; font-family: var(--font-display); color: var(--off-white); line-height: 1; margin-bottom: 0.5rem; }
    .stat-desc { font-size: 0.8rem; color: var(--gray-mid); }
    .stat-desc.danger { color: #ef4444; }
    .stat-desc.success { color: #22c55e; }

    /* ── 2. DETAIL SECTIONS (TABEL TENGAH) ── */
    .dashboard-detail-section {
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.08);
        border-radius: 6px;
        padding: 1.5rem;
        animation: fadeIn 0.4s ease forwards;
        display: none; 
        margin-bottom: 3rem;
    }
    .dashboard-detail-section.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px dashed rgba(242, 239, 230, 0.1); }
    .section-title { font-size: 1.25rem; color: #E8521A; font-family: var(--font-display); letter-spacing: 0.02em; }
    .dash-table { width: 100%; border-collapse: collapse; }
    .dash-table th, .dash-table td { padding: 1rem; text-align: left; border-bottom: 1px solid rgba(242,239,230,0.05); }
    .dash-table th { color: var(--gray-mid); font-size: 0.8rem; text-transform: uppercase; background: #151513; }
    .dash-table td { font-size: 0.9rem; color: var(--off-white); }

    /* ── 3. WIDGETS BAWAH (DRAG & DROP) ── */
    .widget-card {
        background: #151513;
        border: 1px solid rgba(242, 239, 230, 0.08);
        border-radius: 8px;
        padding: 1.5rem;
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        transition: border-color 0.2s ease;
    }
    .widget-card:hover { border-color: rgba(242, 239, 230, 0.15); }
    .sortable-ghost { opacity: 0.4; background: rgba(232, 82, 26, 0.05); border: 2px dashed #E8521A; }
    .widget-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px dashed rgba(242, 239, 230, 0.1); padding-bottom: 0.75rem; }
    .widget-title { color: var(--off-white); font-size: 1rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .drag-handle { color: var(--gray-mid); cursor: grab; padding: 0.2rem; border-radius: 4px; transition: color 0.2s; }
    .drag-handle:hover { color: #E8521A; background: rgba(232, 82, 26, 0.1); }
    .drag-handle:active { cursor: grabbing; }
    .widget-hidden { display: none !important; }
    .chart-container { position: relative; height: 250px; width: 100%; margin-top: 1rem; }

    /* Toggle Switch */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #333; transition: .3s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; }
    input:checked + .slider { background-color: #E8521A; }
    input:checked + .slider:before { transform: translateX(20px); }
</style>
@endpush

@section('content')
<div class="admin-page-header">
    <h2 class="admin-page-title">Analytics Dashboard</h2>
    <p style="color: var(--gray-mid); font-size: 0.9rem; margin-top: 0.5rem;">Geser ikon titik (<span style="color:#fff;">⋮⋮</span>) untuk mengatur tata letak widget.</p>
</div>

<!-- ==========================================
     BAGIAN 1: STAT CARDS (TABS UTAMA) 
     ========================================== -->
<div class="dashboard-grid">
    <!-- Card 1: Pesanan Baru -->
    <div class="stat-card active" data-target="section-pesanan">
        <div class="stat-header">
            <span class="stat-title">Pesanan Baru</span>
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">📦</div>
        </div>
        <div class="stat-value">{{ $pendingOrders->count() }}</div>
        <div class="stat-desc">Pesanan perlu diproses hari ini.</div>
    </div>
    <!-- Card 2: Pendapatan -->
    <div class="stat-card" data-target="section-pendapatan">
        <div class="stat-header">
            <span class="stat-title">Pendapatan Hari Ini</span>
            <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">💰</div>
        </div>
        <div class="stat-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
        <div class="stat-desc success">↑ 15% dari kemarin</div>
    </div>
    <!-- Card 3: Stok Kritis -->
    <div class="stat-card" data-target="section-stok">
        <div class="stat-header">
            <span class="stat-title">Stok Menipis</span>
            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">⚠️</div>
        </div>
        <div class="stat-value">{{ $lowStockProducts->count() + $outOfStockProducts->count() }}</div>
        <div class="stat-desc danger">Produk butuh re-stock segera!</div>
    </div>
    <!-- Card 4: Pelanggan -->
    <div class="stat-card" data-target="section-pelanggan">
        <div class="stat-header">
            <span class="stat-title">Pelanggan Baru</span>
            <div class="stat-icon" style="background: rgba(232, 82, 26, 0.1); color: #E8521A;">👥</div>
        </div>
        <div class="stat-value">{{ $newCustomers->count() }}</div>
        <div class="stat-desc">Bergabung minggu ini.</div>
    </div>
</div>

<!-- ==========================================
     BAGIAN 2: TABEL DETAIL (BERUBAH SESUAI TAB)
     ========================================== -->
<!-- Tabel Pesanan -->
<div id="section-pesanan" class="dashboard-detail-section active">
    <div class="section-header">
        <h3 class="section-title">Pesanan Terbaru yang Menunggu Proses</h3>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Lihat Semua Pesanan &rarr;</a>
    </div>
    <table class="dash-table">
        <thead><tr><th>ID Pesanan</th><th>Pelanggan</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($pendingOrders as $order)
            <tr>
                <td style="font-family: var(--font-mono); color: #E8521A;">ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td><span style="background: rgba(245,158,11,0.1); color: #f59e0b; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">{{ ucfirst($order->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center; color:var(--text-muted);">Tidak ada pesanan pending</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Tabel Pendapatan -->
<div id="section-pendapatan" class="dashboard-detail-section">
    <div class="section-header">
        <h3 class="section-title">Rincian Transaksi Selesai Hari Ini</h3>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline btn-sm">Laporan Keuangan &rarr;</a>
    </div>
    <table class="dash-table">
        <thead><tr><th>Bulan</th><th>Total Pendapatan</th></tr></thead>
        <tbody>
            @forelse($monthlyRevenue as $rev)
            <tr><td>{{ date('F Y', mktime(0, 0, 0, $rev->month, 1, $rev->year)) }}</td><td style="color: #22c55e;">Rp {{ number_format($rev->total, 0, ',', '.') }}</td></tr>
            @empty
            <tr><td colspan="2" style="text-align:center; color:var(--text-muted);">Belum ada data penjualan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Tabel Stok Kritis -->
<div id="section-stok" class="dashboard-detail-section">
    <div class="section-header">
        <h3 class="section-title">Peringatan: Stok Sparepart Sekarat</h3>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">Manajemen Produk &rarr;</a>
    </div>
    <table class="dash-table">
        <thead><tr><th>Kode SKU</th><th>Nama Produk</th><th>Sisa Stok</th><th>Aksi Cepat</th></tr></thead>
        <tbody>
            @forelse($lowStockProducts as $product)
            <tr>
                <td style="font-family: var(--font-mono);">{{ $product->item_code }}</td>
                <td>{{ $product->name }}</td>
                <td style="color: #ef4444; font-weight: bold;">{{ $product->current_stock }} Pcs</td>
                <td><button class="btn btn-sm" style="background: #151513; border: 1px solid #333;" onclick="openQuickStockModal({{ $product->id }}, '{{ addslashes($product->name) }}')">+ Tambah</button></td>
            </tr>
            @empty
            @endforelse
            @forelse($outOfStockProducts as $product)
            <tr>
                <td style="font-family: var(--font-mono);">{{ $product->item_code }}</td>
                <td>{{ $product->name }}</td>
                <td style="color: #ef4444; font-weight: bold;">0 Pcs (Habis)</td>
                <td><button class="btn btn-sm" style="background: #151513; border: 1px solid #333;" onclick="openQuickStockModal({{ $product->id }}, '{{ addslashes($product->name) }}')">+ Tambah</button></td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center; color:var(--text-muted);">Tidak ada stok menipis</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Tabel Pelanggan -->
<div id="section-pelanggan" class="dashboard-detail-section">
    <div class="section-header">
        <h3 class="section-title">Pendaftaran Pelanggan Baru</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">Lihat Semua Data &rarr;</a>
    </div>
    <table class="dash-table">
        <thead><tr><th>Tanggal Daftar</th><th>Nama</th><th>Email</th><th>Total Belanja</th></tr></thead>
        <tbody>
            @forelse($newCustomers as $customer)
            <tr>
                <td>{{ $customer->created_at->diffForHumans() }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>Rp 0</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center; color:var(--text-muted);">Belum ada pelanggan baru</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ==========================================
     BAGIAN 3: ANALITIK WIDGETS (DRAG & DROP)
     ========================================== -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-top: 1px solid rgba(242,239,230,0.08); padding-top: 2rem;">
    <div>
        <h3 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--off-white); margin: 0;">Analitik Tambahan</h3>
        <p style="color: var(--gray-mid); font-size: 0.85rem; margin-top: 0.2rem;">Tahan ikon (<span style="color:#fff;">⋮⋮</span>) untuk menggeser susunan kartu.</p>
    </div>
    <button class="btn btn-outline btn-sm" style="border-color: #E8521A; color: #E8521A;" onclick="openWidgetModal()">⚙️ Atur Widget</button>
</div>

<div class="dashboard-grid" id="analytics-grid">
    <!-- WIDGET 1: GRAFIK PENDAPATAN -->
    <div class="widget-card" id="widget-revenue" data-id="widget-revenue">
        <div class="widget-header">
            <h3 class="widget-title"><span style="color: #22c55e;">📈</span> Grafik Arus Kas</h3>
            <div class="drag-handle" title="Tahan dan geser">⋮⋮</div>
        </div>
        <div class="chart-container">
            <canvas id="cashflowChart"></canvas>
        </div>
    </div>

    <!-- WIDGET 2: KATEGORI TERLARIS -->
    <div class="widget-card" id="widget-category" data-id="widget-category">
        <div class="widget-header">
            <h3 class="widget-title"><span style="color: #E8521A;">🎯</span> Penjualan per Kategori</h3>
            <div class="drag-handle" title="Tahan dan geser">⋮⋮</div>
        </div>
        <div class="chart-container">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <!-- WIDGET 3: INFO CEPAT -->
    <div class="widget-card" id="widget-info" data-id="widget-info">
        <div class="widget-header">
            <h3 class="widget-title"><span style="color: #f59e0b;">💡</span> Info Server & Sistem</h3>
            <div class="drag-handle" title="Tahan dan geser">⋮⋮</div>
        </div>
        <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #333; padding-bottom: 0.5rem;">
                <span style="color: var(--gray-mid);">Total Produk Katalog</span>
                <span style="color: var(--off-white); font-family: var(--font-mono);">{{ \App\Models\Product::count() }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #333; padding-bottom: 0.5rem;">
                <span style="color: var(--gray-mid);">Total Customer</span>
                <span style="color: var(--off-white); font-family: var(--font-mono);">{{ \App\Models\User::where('role', 'customer')->count() }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--gray-mid);">Total Pesanan</span>
                <span style="color: var(--off-white); font-family: var(--font-mono);">{{ \App\Models\Transaction::where('status', '!=', 'cancelled')->count() }}</span>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PENGATURAN WIDGET -->
<div id="widgetSettingsModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header-custom">
            <h3>⚙️ Sembunyikan Widget</h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 1rem;" id="widget-toggles"></div>
        <div style="display:flex; justify-content: flex-end; margin-top: 2rem; border-top: 1px solid rgba(242,239,230,0.08); padding-top: 1rem;">
            <button class="btn" style="background: #E8521A; color: #fff; width: 100%; border: none;" onclick="closeWidgetModal()">Tutup & Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Tambah Stok Cepat -->
<div id="quickStockModal" class="modal">
    <div class="modal-content">
        <div class="modal-header-custom">
            <h3 id="stockModalTitle">Tambah Stok</h3>
        </div>
        <form id="quickStockForm">
            <input type="hidden" id="quickStockProductId" name="product_id">
            <div style="margin-bottom: 1rem;">
                <label style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Jumlah Stok yang Ditambahkan *</label>
                <input type="number" id="quickStockAmount" name="stock" class="form-control" min="1" required placeholder="Contoh: 10">
            </div>
            <div style="display:flex; justify-content: flex-end; gap:1rem; margin-top:2rem; border-top: 1px solid rgba(242, 239, 230, 0.08); padding-top: 1.5rem;">
                <button type="button" class="btn" style="background:transparent; border:1px solid var(--border); color: #888880;" onclick="closeQuickStockModal()">Batal</button>
                <button type="submit" class="btn" style="background: #E8521A; color: white; border: none; padding: 0.5rem 1.5rem;">Simpan Stok</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. LOGIKA TABS UNTUK KARTU ATAS ---
    const statCards = document.querySelectorAll('.stat-card');
    const detailSections = document.querySelectorAll('.dashboard-detail-section');

    statCards.forEach(card => {
        card.addEventListener('click', function() {
            statCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const targetId = this.getAttribute('data-target');
            
            detailSections.forEach(section => section.classList.remove('active'));
            const targetSection = document.getElementById(targetId);
            if (targetSection) targetSection.classList.add('active');
        });
    });

    // --- 2. LOGIKA DRAG & DROP WIDGETS BAWAH ---
    const grid = document.getElementById('analytics-grid');
    if(grid) {
        new Sortable(grid, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function () {
                const order = Array.from(grid.children).map(el => el.getAttribute('data-id'));
                localStorage.setItem('partlyfe_dashboard_layout', JSON.stringify(order));
            }
        });

        // Load urutan saat halaman dimuat
        const savedOrder = JSON.parse(localStorage.getItem('partlyfe_dashboard_layout'));
        if (savedOrder) {
            savedOrder.forEach(id => {
                const element = document.getElementById(id);
                if (element) grid.appendChild(element);
            });
        }
    }

    // --- 3. LOGIKA VISIBILITAS WIDGET ---
    const widgets = [
        { id: 'widget-revenue', name: 'Grafik Arus Kas' },
        { id: 'widget-category', name: 'Grafik Penjualan Kategori' },
        { id: 'widget-info', name: 'Info Server & Sistem' }
    ];

    const togglesContainer = document.getElementById('widget-toggles');
    let savedVisibility = JSON.parse(localStorage.getItem('partlyfe_dashboard_visibility')) || {};

    widgets.forEach(widget => {
        const isVisible = savedVisibility[widget.id] !== false; 
        const cardEl = document.getElementById(widget.id);
        if (!isVisible && cardEl) cardEl.classList.add('widget-hidden');

        const toggleHtml = `
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: var(--off-white); font-size: 0.9rem;">${widget.name}</span>
                <label class="switch">
                    <input type="checkbox" id="toggle-${widget.id}" ${isVisible ? 'checked' : ''} onchange="toggleWidget('${widget.id}')">
                    <span class="slider"></span>
                </label>
            </div>
        `;
        togglesContainer.insertAdjacentHTML('beforeend', toggleHtml);
    });

// --- 4. RENDER GRAFIK CHART.JS ---
    Chart.defaults.color = '#888880';
    
    // Data bulanan dari database
    const monthlyLabels = @json($monthlyRevenueLabels);
    
    const ctxCash = document.getElementById('cashflowChart');
    if(ctxCash && monthlyLabels.length > 0) {
        new Chart(ctxCash.getContext('2d'), {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [
                    { label: 'Pendapatan', data: monthlyData, backgroundColor: '#22c55e', borderRadius: 4 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { grid: { color: 'rgba(242, 239, 230, 0.05)' } }, x: { grid: { display: false } } },
                plugins: { legend: { position: 'top' } }
            }
        });
    }

    // Data penjualan kategori dari database
    const categoryLabels = @json($salesByCategoryLabels);
    const categoryData = @json($salesByCategoryData);

    const ctxCat = document.getElementById('categoryChart');
    if(ctxCat && categoryLabels.length > 0) {
        new Chart(ctxCat.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{ data: categoryData, backgroundColor: ['#E8521A', '#f59e0b', '#3b82f6', '#22c55e', '#a855f7'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom' } } }
        });
    }
});

// --- FUNGSI GLOBAL MODAL ---
function openWidgetModal() { document.getElementById('widgetSettingsModal').classList.add('active'); }
function closeWidgetModal() { document.getElementById('widgetSettingsModal').classList.remove('active'); }

function toggleWidget(widgetId) {
    const isChecked = document.getElementById(`toggle-${widgetId}`).checked;
    const cardEl = document.getElementById(widgetId);
    
    if (isChecked) { cardEl.classList.remove('widget-hidden'); } 
    else { cardEl.classList.add('widget-hidden'); }

    let savedVisibility = JSON.parse(localStorage.getItem('partlyfe_dashboard_visibility')) || {};
    savedVisibility[widgetId] = isChecked;
    localStorage.setItem('partlyfe_dashboard_visibility', JSON.stringify(savedVisibility));
}

function openQuickStockModal(productId, productName) {
    document.getElementById('quickStockModal').classList.add('active');
    document.getElementById('quickStockProductId').value = productId;
    document.getElementById('quickStockAmount').value = '';
    document.getElementById('quickStockAmount').focus();
    document.getElementById('stockModalTitle').textContent = 'Tambah Stok: ' + productName;
}

function closeQuickStockModal() {
    document.getElementById('quickStockModal').classList.remove('active');
}

document.getElementById('quickStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const productId = document.getElementById('quickStockProductId').value;
    const amount = document.getElementById('quickStockAmount').value;
    if (!amount || parseInt(amount) < 1) return;
    
    const url = '{{ route('admin.products.add-stock', ':id') }}'.replace(':id', productId);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ stock: parseInt(amount) })
    })
    .then(response => response.json().catch(() => ({})))
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal menambah stok: ' + (data.message || 'Terjadi kesalahan pada server.'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Terjadi kesalahan jaringan.');
    });
});
</script>
@endpush        

