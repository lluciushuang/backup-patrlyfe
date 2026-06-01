@extends('layouts.admin')
@section('title', 'POS System')

@push('styles')
<style>
    .pos-layout {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 1.5rem;
        height: calc(100vh - 120px);
    }
    .pos-products {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 4px;
        padding: 1rem;
        overflow-y: auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        align-content: start;
    }
    .pos-item {
        border: 1px solid var(--border);
        padding: 1rem;
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
        transition: 0.2s;
    }
    .pos-item:hover { border-color: var(--orange); background: rgba(232,82,26,0.05); }
    .pos-cart {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 4px;
        display: flex;
        flex-direction: column;
    }
    .cart-items { flex: 1; overflow-y: auto; padding: 1rem; }
    .cart-row { display: flex; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; }
    .cart-footer { padding: 1rem; border-top: 1px solid var(--border); }
</style>
@endpush

@section('content')
    <div class="pos-layout">
        <div class="pos-products">
            <div class="pos-item" onclick="addToCart('Oli Motul 300V', 125000)">
                <div style="font-weight:bold; margin-bottom:0.5rem;">Oli Motul 300V</div>
                <div style="color:var(--orange);">Rp 125.000</div>
            </div>
            <div class="pos-item" onclick="addToCart('Kampas Rem Brembo', 350000)">
                <div style="font-weight:bold; margin-bottom:0.5rem;">Kampas Rem Brembo</div>
                <div style="color:var(--orange);">Rp 350.000</div>
            </div>
            <div class="pos-item" onclick="addToCart('Busi NGK Iridium', 95000)">
                <div style="font-weight:bold; margin-bottom:0.5rem;">Busi NGK Iridium</div>
                <div style="color:var(--orange);">Rp 95.000</div>
            </div>
        </div>
        <div class="pos-cart">
            <div style="padding:1rem; border-bottom:1px solid var(--border); font-weight:bold; color:var(--orange);">🛒 Keranjang POS</div>
            <div class="cart-items" id="cartItems">
                <!-- Daftar Item -->
            </div>
            <div class="cart-footer">
                <div style="display:flex; justify-content:space-between; margin-bottom:1rem; font-size:1.2rem;">
                    <span>Total:</span>
                    <span id="cartTotal" style="color:var(--orange); font-weight:bold;">Rp 0</span>
                </div>
                <button class="btn" style="width:100%; padding:1rem; font-size:1.1rem;" onclick="alert('Struk tercetak! Transaksi Disimpan.')">Bayar Sekarang</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let total = 0;
    function addToCart(name, price) {
        const cart = document.getElementById('cartItems');
        const row = document.createElement('div');
        row.className = 'cart-row';
        row.innerHTML = `
            <div>${name} <span style="color:#888880;">(x1)</span></div>
            <div>Rp ${price.toLocaleString('id-ID')}</div>
        `;
        cart.appendChild(row);
        total += price;
        document.getElementById('cartTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endpush