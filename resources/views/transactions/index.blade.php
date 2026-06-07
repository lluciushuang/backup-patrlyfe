@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@push('styles')
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
@endpush

@section('content')
<div class="page-wrapper">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
        <a href="{{ route('produk.index') }}" class="btn btn-outline btn-sm" style="background: var(--orange); color: var(--off-white); border: none; font-size: 0.85rem; padding: 0.5rem 1rem;">
            ← KEMBALI KE PRODUK
        </a>
    </div>
    <div class="tx-container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">HOME</a> <span>/</span> TRANSAKSI
        </div>

    <div class="tx-header">
        <h1 class="tx-title">RIWAYAT TRANSAKSI</h1>
    </div>

    <div class="filter-tabs">
        <a href="{{ route('transactions.index') }}" class="filter-tab {{ !$statusFilter ? 'active' : '' }}">Semua</a>
        <a href="{{ route('transactions.index', ['status' => 'menunggu']) }}" class="filter-tab {{ $statusFilter == 'menunggu' ? 'active' : '' }}">Menunggu</a>
        <a href="{{ route('transactions.index', ['status' => 'diproses']) }}" class="filter-tab {{ $statusFilter == 'diproses' ? 'active' : '' }}">Diproses</a>
        <a href="{{ route('transactions.index', ['status' => 'gagal']) }}" class="filter-tab {{ $statusFilter == 'gagal' ? 'active' : '' }}">Gagal/Dibatalkan</a>
    </div>

    @if($transactions->isEmpty())
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <h3 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 0.5rem;">BELUM ADA TRANSAKSI</h3>
        <p>Anda belum memiliki transaksi apapun.</p>
        <a href="{{ route('produk.index') }}" class="btn btn-primary btn-sm" style="margin-top: 1rem;">Mulai Belanja</a>
    </div>
    @else

    <div class="tx-list">
        @foreach($transactions as $tx)
        <a href="{{ route('transactions.invoice', $tx->invoice_number) }}" class="tx-card" style="text-decoration: none; color: inherit;">
            <div>
                <div class="tx-id">{{ $tx->invoice_number }}</div>
                <div class="tx-date">{{ $tx->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div>
                <div class="tx-name">{{ $tx->details->count() }} Produk</div>
                <div style="font-size: 0.72rem; color: var(--gray-mid);">{{ $tx->details->sum('qty') }} unit total</div>
            </div>
            <div>
                <span class="tx-status {{ $tx->status }}">{{ strtoupper(str_replace('_', ' ', $tx->status)) }}</span>
            </div>
            <div>
                <div class="tx-total">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</div>
            </div>
        </a>
        @endforeach
    </div>

    <div style="display: flex; justify-content: center; margin-top: 2rem;">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
</div>
@endsection
