@extends('layouts.admin')
@section('title', 'Laporan')

@push('styles')
<style>
    .chart-container { background: var(--surface); padding: 1.5rem; border-radius: 4px; margin-bottom: 2rem; }
</style>
@endpush

@section('content')
    <div class="admin-page-header">
        <h2 class="admin-page-title">Laporan Penjualan</h2>
    </div>

    <div class="admin-card">
        <h3 class="admin-card-title">Pendapatan Bulanan</h3>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th style="text-align: right;">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyRevenue as $revenue)
                    <tr>
                        <td>{{ date('F Y', mktime(0, 0, 0, $revenue->month, 1, $revenue->year)) }}</td>
                        <td style="text-align: right;">Rp {{ number_format($revenue->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="text-align:center; color:var(--text-muted);">Belum ada data penjualan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-card">
        <h3 class="admin-card-title">Penjualan per Kategori</h3>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th style="text-align: right;">Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesByCategory as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td style="text-align: right;">Rp {{ number_format($category->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="text-align:center; color:var(--text-muted);">Belum ada data penjualan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection