@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: var(--surface);
        padding: 1.5rem;
        border: 1px solid var(--border);
        border-radius: 4px;
    }
    .stat-value { font-size: 2rem; font-weight: bold; color: var(--orange); margin-top: 0.5rem; }
</style>
@endpush

@section('content')
    <h2>Dashboard Overview</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <div>Total Penjualan</div>
            <div class="stat-value">Rp 45.2M</div>
        </div>
        <div class="stat-card">
            <div>Pesanan Baru</div>
            <div class="stat-value">124</div>
        </div>
        <div class="stat-card">
            <div>Total Produk</div>
            <div class="stat-value">1,240</div>
        </div>
        <div class="stat-card">
            <div>Pelanggan Aktif</div>
            <div class="stat-value">8,432</div>
        </div>
    </div>
@endsection