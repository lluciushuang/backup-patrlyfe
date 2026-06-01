{{-- resources/views/checkout.blade.php --}}
@extends('layouts.app')
@section('title', 'Checkout')

@push('styles')
<style>
.checkout-layout { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; }
.co-section { background: var(--surface); padding: 1.5rem; border-radius: 4px; border: 1px solid rgba(242,239,230,0.07); margin-bottom: 1rem; }
.co-title { font-family: var(--font-display); font-size: 1.2rem; border-bottom: 1px solid rgba(242,239,230,0.08); padding-bottom: 0.75rem; margin-bottom: 1rem; }
.addr-card { padding: 1rem; border: 1.5px solid var(--orange); background: rgba(232,82,26,0.03); border-radius: 3px; position: relative; }
.pay-opt { display: flex; align-items: center; gap: 1rem; padding: 0.85rem; border: 1px solid rgba(242,239,230,0.1); border-radius: 3px; margin-bottom: 0.5rem; }
.pay-opt.selected { border-color: var(--orange); background: rgba(232,82,26,0.03); }
</style>
@endpush

@section('content')
<div class="page-wrapper"> {{-- Pembungkus Pengaman Tengah Layar --}}
    <div class="breadcrumb">
        <a href="{{ route('home') }}">HOME</a> <span>/</span> CHECKOUT
    </div>
<div class="checkout-layout">
    <div>
        <h1 class="sec-title">CHECKOUT</h1>
        
        <div class="co-section">
            <div class="co-body">
                <div class="co-title">ALAMAT PENGIRIMAN</div>
                <div class="addr-card">
                    <div style="font-weight:600; font-size:0.95rem;">{{ Auth::user()->name ?? 'Guest User' }}</div>
                    <div style="font-size:0.8rem; color:var(--gray-mid); margin-bottom:0.5rem;">{{ Auth::user()->phone ?? '+62 812-3456-7890' }}</div>
                    <div style="font-size:0.85rem; color:var(--gray-light);">{{ Auth::user()->address ?? 'Alamat belum diatur. Harap tambahkan alamat pada profil.' }}</div>
                </div>
            </div>
        </div>

        <div class="co-section">
            <div class="co-title">METODE PEMBAYARAN</div>
            <div class="pay-opt selected">
                <div style="width:8px; height:8px; background:var(--orange); border-radius:50%;"></div>
                <div style="font-size:0.85rem; font-weight:500;">Transfer Bank Mandiri / BCA</div>
            </div>
            <div class="pay-opt">
                <div style="width:8px; height:8px; background:transparent; border:1px solid var(--gray-mid); border-radius:50%;"></div>
                <div style="font-size:0.85rem; font-weight:500;">E-Wallet (GoPay / DANA)</div>
            </div>
        </div>
    </div>

    <div>
        <div class="co-section" style="background:var(--surface-2);">
            <div class="co-title" style="font-size:1rem;">RINGKASAN ORDER</div>
            <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:0.5rem;">
                <span style="color:var(--gray-mid);">Subtotal</span><span>Rp {{ number_format($subtotal ?? 79000, 0, ',', '.') }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:0.5rem;">
                <span style="color:var(--gray-mid);">Ongkir</span><span style="color:var(--green);">GRATIS</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-family:var(--font-display); font-size:1.5rem; border-top:1px solid rgba(242,239,230,0.08); padding-top:0.75rem; margin-top:0.75rem;">
                <span>Total</span><span>Rp {{ number_format($totalTagihan ?? 79000, 0, ',', '.') }}</span>
            </div>
            <button class="btn btn-primary btn-lg" style="width:100%; justify-content:center; margin-top:1.5rem;">Bayar Sekarang</button>
        </div>
    </div>
</div>
</div>
@endsection