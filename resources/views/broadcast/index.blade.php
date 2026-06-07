@extends('layouts.app')
@section('title', 'Notifikasi')

@push('styles')
<style>
    .broadcast-container { margin-top: 1rem; }
    .broadcast-header {
        border-bottom: 1px solid rgba(242, 239, 230, 0.06);
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .broadcast-title {
        font-family: var(--font-display);
        font-size: 2.2rem;
        letter-spacing: 0.03em;
        color: var(--off-white);
    }
    .broadcast-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .broadcast-card {
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 4px;
        padding: 1.5rem;
        transition: border-color 0.2s;
    }
    .broadcast-card.unread { border-left: 3px solid var(--orange); }
    .broadcast-card.read { border-left: 3px solid transparent; }
    .bc-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    .bc-type {
        font-family: var(--font-mono);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 0.2rem 0.5rem;
        border-radius: 2px;
    }
    .bc-type.promo { background: rgba(232, 82, 26, 0.15); color: var(--orange); }
    .bc-type.system { background: rgba(52, 152, 219, 0.15); color: #3498DB; }
    .bc-type.info { background: rgba(46, 204, 113, 0.15); color: var(--green); }
    .bc-type.cart { background: rgba(155, 89, 182, 0.15); color: #9B59B6; }
    .bc-type.wishlist { background: rgba(241, 196, 15, 0.15); color: #F1C40F; }

    .bc-time {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        color: var(--gray-mid);
    }
    .bc-title { font-size: 1rem; font-weight: 600; color: var(--off-white); margin-bottom: 0.5rem; }
    .bc-message { font-size: 0.88rem; color: var(--gray-light); line-height: 1.6; }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--gray-mid);
    }
    .empty-state svg {
        width: 80px;
        height: 80px;
        opacity: 0.3;
        margin-bottom: 1.5rem;
    }

    .mark-read-form {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
        <a href="{{ route('produk.index') }}" class="btn btn-outline btn-sm" style="background: var(--orange); color: var(--off-white); border: none; font-size: 0.85rem; padding: 0.5rem 1rem;">
            ← KEMBALI KE PRODUK
        </a>
    </div>
    <div class="broadcast-container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">HOME</a> <span>/</span> NOTIFIKASI
        </div>

    <div class="broadcast-header">
        <h1 class="broadcast-title">NOTIFIKASI</h1>
@if($broadcasts->count() > 0)
         <form method="POST" action="{{ route('notifications.read.all') }}" class="mark-read-form">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm">Tandai Semua Dibaca</button>
        </form>
        @endif
    </div>

    <div class="broadcast-list">
        @forelse($broadcasts as $broadcast)
        <div class="broadcast-card {{ $broadcast->is_read ? 'read' : 'unread' }}">
            <div class="bc-meta">
                <span class="bc-type {{ $broadcast->type }}">{{ strtoupper($broadcast->type) }}</span>
                <span class="bc-time">{{ $broadcast->created_at->format('d M Y, H:i') }}</span>
            </div>
            <h3 class="bc-title">{{ $broadcast->title }}</h3>
            <div class="bc-message">{{ $broadcast->message }}</div>
        </div>
        @empty
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <h3 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 0.5rem;">TIDAK ADA NOTIFIKASI</h3>
            <p>Anda belum memiliki notifikasi.</p>
        </div>
        @endforelse
    </div>

    <div style="display: flex; justify-content: center; margin-top: 2rem;">
        {{ $broadcasts->links() }}
    </div>
</div>
</div>
@endsection
