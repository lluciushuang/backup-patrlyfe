<?php $__env->startSection('title', 'POS - Point of Sale'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .pos-layout {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 1.5rem;
        height: calc(100vh - 120px);
    }
    .pos-products {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1rem;
        overflow-y: auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        align-content: start;
    }
    .pos-item {
        border: 1px solid var(--border);
        padding: 1rem;
        text-align: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: #151513;
    }
    .pos-item:hover { border-color: var(--orange); background: rgba(232,82,26,0.05); }
    .pos-cart {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
    }
    .cart-items { flex: 1; overflow-y: auto; padding: 1rem; }
    .cart-row { display: flex; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; }
    .cart-footer { padding: 1rem; border-top: 1px solid var(--border); }
    .modal-pos {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-pos.active { display: flex; }
    .modal-content-pos {
        background: #111110;
        border: 1px solid rgba(242,239,230,0.1);
        padding: 1.5rem;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
    }
    #searchResultsPos { max-height: 200px; overflow-y: auto; margin-top: 0.5rem; }
    .search-item-pos { padding: 0.5rem; cursor: pointer; border-bottom: 1px solid var(--border); }
    .search-item-pos:hover { background: rgba(232,82,26,0.1); }
    .payment-method {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .payment-option {
        flex: 1;
        padding: 0.75rem;
        text-align: center;
        border: 1px solid var(--border);
        border-radius: 4px;
        cursor: pointer;
        background: #151513;
    }
    .payment-option.selected {
        border-color: var(--orange);
        background: rgba(232,82,26,0.1);
        color: var(--orange);
    }
    #customerSearchResults {
        max-height: 150px;
        overflow-y: auto;
    }
    .customer-result {
        padding: 0.5rem;
        cursor: pointer;
        border-bottom: 1px solid var(--border);
    }
    .customer-result:hover {
        background: rgba(232,82,26,0.1);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header">
    <h2 class="admin-page-title">POS - Point of Sale</h2>
    <div style="display:flex; gap:1rem; align-items:center;">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari produk..." style="width:250px;" onkeyup="searchAndLoad()">
        <button class="btn" onclick="openProductSearch()">🔍 Cari Produk</button>
    </div>
</div>

<div class="pos-layout">
    <div class="pos-products" id="productsGrid">
        <div style="padding:2rem; color:var(--gray-mid);">Memuat produk...</div>
    </div>
    
    <div class="pos-cart">
        <div style="padding:1rem; border-bottom:1px solid var(--border); font-weight:bold; color:var(--orange);">🛒 Keranjang POS</div>
        
<div style="padding:1rem; border-bottom:1px solid var(--border);">
             <input type="text" id="customerSearch" class="form-control" placeholder="Cari nama pelanggan..." onkeyup="searchCustomers()">
             <div id="customerSearchResults"></div>
             <input type="hidden" id="selectedUserId" value="">
             <input type="text" id="walkInName" class="form-control" placeholder="Nama Walk-in Customer" style="margin-top:0.5rem; display:none;">
             <div id="selectedCustomer" style="margin-top:0.5rem; font-size:0.9rem; color:var(--gray-mid);"></div>
         </div>
        
        <div class="cart-items" id="cartItems"></div>
        <div class="cart-footer">
            <div style="display:flex; justify-content:space-between; margin-bottom:1rem; font-size:1.2rem;">
                <span>Total:</span>
                <span id="cartTotal" style="color:var(--orange); font-weight:bold;">Rp 0</span>
            </div>
            <div class="payment-method">
                <div class="payment-option selected" onclick="selectPayment('cash')" id="paymentCash">💵 Cash</div>
                <div class="payment-option" onclick="selectPayment('midtrans')" id="paymentMidtrans">💳 Midtrans</div>
            </div>
            <button class="btn" style="width:100%; padding:1rem; font-size:1.1rem;" onclick="checkout()">Bayar Sekarang</button>
        </div>
    </div>
</div>

<!-- Modal Cari Produk -->
<div id="productSearchModal" class="modal-pos">
    <div class="modal-content-pos">
        <div class="modal-header-custom" style="margin-bottom:1rem;">
            <h3 style="margin:0;">🔍 Cari Produk</h3>
            <button onclick="closeProductSearch()" style="background:none; border:none; color:var(--gray-mid); font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <input type="text" id="searchBoxPos" class="form-control" placeholder="Nama / Kode / Brand produk..." onkeyup="searchProducts()">
        <div id="searchResultsPos"></div>
    </div>
</div>

<!-- Modal Pembayaran Cash -->
<div id="cashPaymentModal" class="modal-pos">
    <div class="modal-content-pos">
        <div class="modal-header-custom" style="margin-bottom:1rem;">
            <h3 style="margin:0;">💵 Pembayaran Cash</h3>
            <button onclick="closeCashPayment()" style="background:none; border:none; color:var(--gray-mid); font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <div style="margin-bottom:1rem;">
            <label style="display:block; margin-bottom:0.5rem; color:var(--gray-mid);">Total Pembayaran</label>
            <div id="cashTotal" style="font-size:1.5rem; font-weight:bold; color:var(--orange);"></div>
        </div>
        <div style="margin-bottom:1rem;">
            <label style="display:block; margin-bottom:0.5rem; color:var(--gray-mid);">Uang Diterima</label>
            <input type="number" id="cashReceived" class="form-control" min="0" onchange="calculateChange()">
        </div>
        <div style="margin-bottom:1rem;">
            <label style="display:block; margin-bottom:0.5rem; color:var(--gray-mid);">Kembalian</label>
            <input type="text" id="cashChange" class="form-control" readonly>
        </div>
        <button class="btn" style="width:100%;" onclick="confirmCashPayment()">Konfirmasi Pembayaran</button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let cart = [];
    let total = 0;
    let currentPage = 1;
    let isLoading = false;
    let selectedPayment = 'cash';
    let selectedUserId = null;
    let walkInCustomerName = '';

    function selectPayment(method) {
        selectedPayment = method;
        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
        document.getElementById('payment' + method.charAt(0).toUpperCase() + method.slice(1)).classList.add('selected');
    }

    function searchCustomers() {
        const query = document.getElementById('customerSearch').value;
        if (query.length < 2) {
            document.getElementById('customerSearchResults').innerHTML = '';
            return;
        }
        fetch('<?php echo e(route('admin.users.search')); ?>?q=' + encodeURIComponent(query))
            .then(r => r.json())
            .then(data => {
                document.getElementById('customerSearchResults').innerHTML = 
                    '<div class="customer-result" onclick="selectCustomer(null, \'Walk-in Customer\', \'-\')">+ Tambah Walk-in Customer</div>' +
                    data.users.map(u => `
                        <div class="customer-result" onclick="selectCustomer(${u.id}, '${u.name}', '${u.email}')">${u.name} - ${u.email}</div>
                    `).join('');
            });
    }

    function selectCustomer(id, name, phone) {
        selectedUserId = id;
        walkInCustomerName = name;
        document.getElementById('selectedUserId').value = id || '';
        document.getElementById('selectedCustomer').textContent = 'Pelanggan: ' + name;
        document.getElementById('customerSearchResults').innerHTML = '';
        document.getElementById('customerSearch').value = '';
        const walkInInput = document.getElementById('walkInName');
        if (id === null) {
            walkInInput.style.display = 'block';
            walkInInput.value = name === 'Walk-in Customer' ? '' : name;
            walkInInput.placeholder = 'Nama Walk-in Customer';
        } else {
            walkInInput.style.display = 'none';
        }
    }

    function renderProducts(products) {
        const container = document.getElementById('productsGrid');
        container.innerHTML = products.map(product => `
            <div class="pos-item" onclick="addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.base_price})">
                <div style="font-weight:bold; margin-bottom:0.5rem; color:var(--off-white);">${product.name}</div>
                <div style="color:var(--orange); font-family:var(--font-mono);">Rp ${product.base_price.toLocaleString('id-ID')}</div>
                <div style="color:var(--gray-mid); font-size:0.8rem; margin-top:0.5rem;">Stok: ${product.current_stock}</div>
            </div>
        `).join('');
    }

    function loadMoreProducts() {
        if (isLoading) return;
        isLoading = true;
        const search = document.getElementById('searchInput').value;
        
        fetch('<?php echo e(route('admin.pos.load-products')); ?>?page=' + (currentPage + 1) + '&search=' + encodeURIComponent(search))
            .then(r => r.json())
            .then(data => {
                if (data.products && data.products.length > 0) {
                    currentPage++;
                    const container = document.getElementById('productsGrid');
                    container.innerHTML += data.products.map(product => `
                        <div class="pos-item" onclick="addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.base_price})">
                            <div style="font-weight:bold; margin-bottom:0.5rem; color:var(--off-white);">${product.name}</div>
                            <div style="color:var(--orange); font-family:var(--font-mono);">Rp ${product.base_price.toLocaleString('id-ID')}</div>
                            <div style="color:var(--gray-mid); font-size:0.8rem; margin-top:0.5rem;">Stok: ${product.current_stock}</div>
                        </div>
                    `).join('');
                }
                isLoading = false;
            })
            .catch(() => isLoading = false);
    }

    function searchAndLoad() {
        const search = document.getElementById('searchInput').value;
        if (search.length < 2) return loadProducts();
        
        fetch('<?php echo e(route('admin.pos.load-products')); ?>?search=' + encodeURIComponent(search))
            .then(r => r.json())
            .then(data => {
                currentPage = 1;
                renderProducts(data.products);
            });
    }

    function loadProducts() {
        fetch('<?php echo e(route('admin.pos.load-products')); ?>')
            .then(r => r.json())
            .then(data => {
                currentPage = 1;
                if (data.products.length > 0) {
                    renderProducts(data.products);
                } else {
                    document.getElementById('productsGrid').innerHTML = '<div style="padding:2rem; color:var(--gray-mid);">Tidak ada produk ditemukan</div>';
                }
            });
    }

    document.getElementById('productsGrid').addEventListener('scroll', function() {
        if (this.scrollTop + this.clientHeight >= this.scrollHeight - 100) {
            loadMoreProducts();
        }
    });

    window.addEventListener('DOMContentLoaded', function() {
        currentPage = <?php echo e($products->currentPage()); ?>;
        const initialProducts = <?php echo json_encode($products->items(), 15, 512) ?>;
        if (initialProducts.length > 0) {
            document.getElementById('productsGrid').innerHTML = initialProducts.map(product => `
                <div class="pos-item" onclick="addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.base_price})">
                    <div style="font-weight:bold; margin-bottom:0.5rem; color:var(--off-white);">${product.name}</div>
                    <div style="color:var(--orange); font-family:var(--font-mono);">Rp ${product.base_price.toLocaleString('id-ID')}</div>
                    <div style="color:var(--gray-mid); font-size:0.8rem; margin-top:0.5rem;">Stok: ${product.current_stock}</div>
                </div>
            `).join('');
        }
    });

    function addToCart(id, name, price) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.qty += 1;
            existing.price = price;
        } else {
            cart.push({id, name, price, qty: 1});
        }
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        let newTotal = 0;
        container.innerHTML = cart.map((item, index) => {
            const itemTotal = item.price * item.qty;
            newTotal += itemTotal;
            return `
            <div class="cart-row">
                <div>${item.name} <span style="color:var(--gray-mid);">(x${item.qty})</span></div>
                <button onclick="removeFromCart(${index})" style="background:none; border:none; color:var(--red); cursor:pointer;">&times;</button>
            </div>
        `}).join('');
        total = newTotal;
        document.getElementById('cartTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function searchProducts() {
        const query = document.getElementById('searchBoxPos').value;
        if (query.length < 2) {
            document.getElementById('searchResultsPos').innerHTML = '';
            return;
        }
        fetch('<?php echo e(route('admin.pos.search')); ?>?q=' + encodeURIComponent(query))
            .then(r => r.json())
            .then(data => {
                document.getElementById('searchResultsPos').innerHTML = data.map(p => `
                    <div class="search-item-pos" onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.base_price})">
                        ${p.name} - Rp ${p.base_price.toLocaleString('id-ID')}
                    </div>
                `).join('');
            });
    }

    function checkout() {
        if (cart.length === 0) return;
        
        const paymentMethod = selectedPayment;
        
        if (paymentMethod === 'cash') {
            openCashPayment();
        } else {
            processCheckout({payment_method: 'midtrans'});
        }
    }

    function openCashPayment() {
        const totalStr = document.getElementById('cartTotal').textContent.replace(/[^0-9]/g, '');
        const totalAmount = parseInt(totalStr) || 0;
        
        document.getElementById('cashTotal').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
        document.getElementById('cashReceived').value = totalAmount;
        document.getElementById('cashChange').value = 0;
        document.getElementById('cashPaymentModal').classList.add('active');
    }

    function calculateChange() {
        const totalStr = document.getElementById('cartTotal').textContent.replace(/[^0-9]/g, '');
        const totalAmount = parseInt(totalStr) || 0;
        const received = parseInt(document.getElementById('cashReceived').value) || 0;
        const change = received - totalAmount;
        document.getElementById('cashChange').value = change >= 0 ? change.toLocaleString('id-ID') : 0;
    }

    function closeCashPayment() {
        document.getElementById('cashPaymentModal').classList.remove('active');
    }

    function confirmCashPayment() {
        processCheckout({payment_method: 'cash'});
        closeCashPayment();
    }

    function processCheckout(extraData) {
        const walkInNameInput = document.getElementById('walkInName');
        const customerName = selectedUserId ? null : (walkInNameInput.value || 'Walk-in Customer');
        const data = {
            items: cart,
            user_id: selectedUserId,
            customer_name: customerName,
            ...extraData
        };
        
        fetch('<?php echo e(route('admin.pos.store')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            adminToast.fire({icon:'success', title:'Pembayaran berhasil!'});
                            cart = [];
                            renderCart();
                            resetCustomer();
                        },
                        onPending: function(result) {
                            adminToast.fire({icon:'info', title:'Menunggu pembayaran...'});
                        },
                        onError: function(result) {
                            adminToast.fire({icon:'error', title:'Pembayaran gagal!'});
                        }
                    });
                } else {
                    adminToast.fire({icon:'success', title:'Transaksi berhasil!'});
                    cart = [];
                    renderCart();
                    resetCustomer();
                }
            } else {
                adminToast.fire({icon:'error', title:data.message});
            }
        });
    }

    function resetCustomer() {
        selectedUserId = null;
        walkInCustomerName = '';
        document.getElementById('selectedUserId').value = '';
        document.getElementById('selectedCustomer').textContent = '';
        document.getElementById('customerSearch').value = '';
    }

    function openProductSearch() {
        document.getElementById('productSearchModal').classList.add('active');
    }

    function closeProductSearch() {
        document.getElementById('productSearchModal').classList.remove('active');
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/admin/pos/index.blade.php ENDPATH**/ ?>