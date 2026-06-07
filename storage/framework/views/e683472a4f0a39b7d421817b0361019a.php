<?php $__env->startSection('title', 'Manajemen Produk'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; }
    .modal.active { display: flex; align-items: center; justify-content: center; }
    .modal-content { background: var(--surface-2); padding: 2rem; border-radius: 8px; width: 90%; max-width: 900px; max-height: 90vh; overflow-y: auto; }
    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .price-tiers { margin-top: 1rem; padding: 1rem; background: var(--surface); border-radius: 4px; }
    .tier-row { display: flex; gap: 1rem; margin-bottom: 0.5rem; }
    .tier-row input { flex: 1; }
    .stock-badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; }
    .stock-low { background: rgba(255,193,7,0.2); color: #ffc107; }
    .stock-out { background: rgba(220,53,69,0.2); color: #dc3545; }
    .stock-ok { background: rgba(40,167,69,0.2); color: #28a745; }
    .product-image-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; background: var(--surface-2); }
    .product-image-placeholder { width: 50px; height: 50px; background: var(--surface-2); border-radius: 4px; display: flex; align-items: center; justify-content: center; opacity: 0.3; }

    /* -- MODAL UI ENHANCEMENTS -- */
    .modal-content {
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .modal-header-custom {
        border-bottom: 1px solid rgba(242, 239, 230, 0.08);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    .modal-header-custom h3 { margin: 0; color: var(--off-white); }

    .form-control {
        background: #181816;
        border: 1px solid rgba(242, 239, 230, 0.15);
        color: #eee;
        transition: all 0.2s;
    }
    .form-control:focus {
        border-color: #E8521A;
        box-shadow: 0 0 0 2px rgba(232, 82, 26, 0.15);
        outline: none;
    }

    .section-panel {
        background: #151513;
        border: 1px solid rgba(242, 239, 230, 0.08);
        border-radius: 6px;
        padding: 1.25rem;
        margin-top: 1.5rem;
    }
    .section-panel h4 {
        margin-top: 0;
        margin-bottom: 0.5rem;
        color: #E8521A;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px dashed rgba(242, 239, 230, 0.1);
        padding-bottom: 0.5rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-header">
        <h2 class="admin-page-title">Manajemen Katalog Produk</h2>
        <button class="btn" onclick="openProductModal()">+ Tambah Produk</button>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loop->iteration + ($products->currentPage() - 1) * $products->perPage()); ?></td>
                    <td>
                        <?php if($product->images->count() > 0): ?>
                            <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" alt="<?php echo e($product->name); ?>" class="product-image-thumb" onerror="this.style.display='none';">
                        <?php else: ?>
                            <div class="product-image-placeholder">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($product->name); ?></td>
                    <td><?php echo e($product->category->name ?? '-'); ?></td>
                    <td>
                        <span class="stock-badge <?php echo e($product->current_stock == 0 ? 'stock-out' : ($product->current_stock <= 5 ? 'stock-low' : 'stock-ok')); ?>">
                            <?php echo e($product->current_stock); ?> pcs
                        </span>
                    </td>
                    <td>
                        <?php $price = $product->prices->where('price_level', 1)->first(); ?>
                        Rp <?php echo e(number_format($price ? $price->price : 0, 0, ',', '.')); ?>

                    </td>
                    <td>
                        <button class="btn btn-sm" style="background:transparent; border:1px solid var(--orange); color:var(--orange);" onclick="editProduct(<?php echo e($product->id); ?>)">Edit</button>
                        <button class="btn btn-sm" style="background:transparent; border:1px solid #17a2b8; color:#17a2b8; margin-left:0.5rem;" onclick="addStock(<?php echo e($product->id); ?>)">+Stok</button>
                        <button class="btn btn-sm" style="background:transparent; border:1px solid #ffc107; color:#ffc107; margin-left:0.5rem;" onclick="reduceStock(<?php echo e($product->id); ?>)">-Stok</button>
                        <button class="btn btn-sm" style="background:transparent; border:1px solid #dc3545; color:#dc3545; margin-left:0.5rem;" onclick="deleteProduct(<?php echo e($product->id); ?>)">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:var(--text-muted);">Belum ada produk</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($products->hasPages()): ?>
    <div style="margin-top:1.5rem;">
        <?php echo e($products->links('vendor.pagination.default')); ?>

    </div>
    <?php endif; ?>

    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h3 id="modalTitle">Tambah Produk</h3>
            </div>

            <form id="productForm" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="productId" name="productId">
                <div class="form-grid">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Nama Produk *</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="Contoh: Kampas Rem Depan Vario 125">
                    </div>
                    <div class="form-group">
                        <label>Kode Barang (SKU)</label>
                        <input type="text" name="item_code" id="item_code" class="form-control" placeholder="Kosongkan untuk auto-generate">
                    </div>
                    <div class="form-group">
                        <label>Brand *</label>
                        <input type="text" name="brand" id="brand" class="form-control" required placeholder="Contoh: Brembo, Honda">
                    </div>
                    <div class="form-group">
                        <label>Kategori *</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Satuan *</label>
                        <input type="text" name="unit" id="unit" class="form-control" value="PCS" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Modal *</label>
                        <input type="number" name="base_price" id="base_price" class="form-control" min="0" required placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Stok Awal *</label>
                        <input type="number" name="current_stock" id="current_stock" class="form-control" min="0" required placeholder="0">
                    </div>
                </div>

                <div class="section-panel">
                    <h4>Harga Grosir (4 Level)</h4>
                    <small style="color:var(--text-muted); display:block; margin-bottom:1rem;">Atur harga jual berdasarkan kuantitas pengambilan (Level 1: Eceran - Level 4: Grosir Besar).</small>
                    <div class="form-grid">
                        <?php for($i = 1; $i <= 4; $i++): ?>
                        <div class="tier-row" style="align-items: center; margin-bottom: 0;">
                            <label style="margin: 0; min-width: 60px;">Level <?php echo e($i); ?></label>
                            <input type="number" name="prices[<?php echo e($i); ?>]" id="price_level_<?php echo e($i); ?>" class="form-control" min="0" placeholder="Rp 0">
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="section-panel">
                    <h4>Gambar Produk</h4>
                    <div class="form-group" style="margin-bottom: 0;">
                        <input type="file" name="images[]" id="images" class="form-control" style="padding: 0.5rem;" accept="image/*" multiple>
                        <small style="color:var(--text-muted); display:block; margin-top:0.5rem;">Bisa pilih lebih dari 1 gambar (format: jpg, png, max 2MB).</small>
                        <div id="image-preview" style="margin-top:1rem; display:flex; gap:0.5rem; flex-wrap:wrap;"></div>
                    </div>
                </div>

                <div style="display:flex; justify-content: flex-end; gap:1rem; margin-top:2rem; border-top: 1px solid rgba(242, 239, 230, 0.08); padding-top: 1.5rem;">
                    <button type="button" class="btn" style="background:transparent; border:1px solid var(--border);" onclick="closeProductModal()">Batal</button>
                    <button type="submit" class="btn" style="background: var(--orange); color: white; border: none; padding: 0.6rem 2rem;">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    <div id="imagePreviewModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header-custom">
                <h3 id="previewTitle">Preview Gambar</h3>
            </div>
            <div id="previewContainer" style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem;"></div>
            <button type="button" class="btn" onclick="closeImagePreviewModal()">Tutup</button>
        </div>
    </div>

    <div id="stockModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header-custom">
                <h3 id="stockModalTitle">Tambah Stok</h3>
            </div>
            <form id="stockForm" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="stockProductId" name="productId">
                <input type="hidden" id="stockAction" name="action">
                <div class="form-group">
                    <label>Jumlah *</label>
                    <input type="number" name="stock" id="stockAmount" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="note" id="stockNote" class="form-control" rows="3" placeholder="Catatan (opsional)"></textarea>
                </div>
                <div style="display:flex; gap:1rem; margin-top:1.5rem; border-top: 1px solid rgba(242, 239, 230, 0.08); padding-top: 1rem;">
                    <button type="submit" class="btn">Simpan</button>
                    <button type="button" class="btn" style="background:transparent; border:1px solid var(--border);" onclick="closeStockModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openProductModal() {
    document.getElementById('productModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Tambah Produk';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
}

function closeStockModal() {
    document.getElementById('stockModal').classList.remove('active');
}

function addStock(productId) {
    document.getElementById('stockModal').classList.add('active');
    document.getElementById('stockModalTitle').textContent = 'Tambah Stok';
    document.getElementById('stockProductId').value = productId;
    document.getElementById('stockAction').value = 'add';
    document.getElementById('stockAmount').value = '';
    document.getElementById('stockForm').action = '<?php echo e(route('admin.products.add-stock', ':id')); ?>'.replace(':id', productId);
}

function reduceStock(productId) {
    document.getElementById('stockModal').classList.add('active');
    document.getElementById('stockModalTitle').textContent = 'Kurangi Stok';
    document.getElementById('stockProductId').value = productId;
    document.getElementById('stockAction').value = 'reduce';
    document.getElementById('stockAmount').value = '';
    document.getElementById('stockForm').action = '<?php echo e(route('admin.products.reduce-stock', ':id')); ?>'.replace(':id', productId);
}

function editProduct(productId) {
    fetch('<?php echo e(route('admin.products.edit', ':id')); ?>'.replace(':id', productId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
const product = data.product;
                document.getElementById('productModal').classList.add('active');
                document.getElementById('modalTitle').textContent = 'Edit Produk';
                document.getElementById('productId').value = product.id;
                document.getElementById('item_code').value = product.item_code || '';
                document.getElementById('name').value = product.name || '';
                document.getElementById('brand').value = product.brand || '';
                document.getElementById('category_id').value = product.category_id || '';
                document.getElementById('unit').value = product.unit || 'PCS';
 document.getElementById('base_price').value = product.base_price || 0;
                 document.getElementById('current_stock').value = product.current_stock || 0;
document.getElementById('price_level_1').value = product.prices?.find(p => p.price_level == 1)?.price || '';
                 document.getElementById('price_level_2').value = product.prices?.find(p => p.price_level == 2)?.price || '';
                 document.getElementById('price_level_3').value = product.prices?.find(p => p.price_level == 3)?.price || '';
                 document.getElementById('price_level_4').value = product.prices?.find(p => p.price_level == 4)?.price || '';
                 document.getElementById('productForm').action = '<?php echo e(route('admin.products.update', ':id')); ?>'.replace(':id', productId);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            adminToast.fire({ icon: 'error', title: 'Gagal mengambil data produk' });
        });
}

document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const productId = document.getElementById('productId').value;
    const url = productId ? 
        '<?php echo e(route('admin.products.update', ':id')); ?>'.replace(':id', productId) : 
        '<?php echo e(route('admin.products.store')); ?>';

    const formData = new FormData(this);
    // Jika edit, tambahkan method spoofing
    if (productId) {
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',  // Selalu POST, Laravel handle PUT via _method
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json().catch(() => ({ success: false, message: 'Error parsing response' })))
    .then(data => {
        if (data.success) {
            closeProductModal();
            showAdminToast('success', 'Berhasil', data.message || 'Produk berhasil disimpan');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAdminToast('error', 'Gagal Menyimpan', data.message || 'Terjadi kesalahan saat menyimpan produk.');
            console.error('Product save error:', data);
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        showAdminToast('error', 'Kesalahan Jaringan', 'Periksa koneksi internet atau buka DevTools > Network untuk detail error.');
    });
});

function showAdminToast(type, title, message) {
    const toast = document.createElement('div');
    const borderColor = type === 'error' ? '#e74c4c' : '#27ae60';
    const bgColor = type === 'error' ? 'rgba(231, 76, 60, 0.1)' : 'rgba(39, 174, 96, 0.1)';
    
    toast.style.cssText = `position:fixed;top:80px;right:24px;z-index:9999;background:#111110;border:1px solid rgba(242,239,230,0.1);border-left:4px solid ${borderColor};padding:1rem 1.25rem;border-radius:4px;color:#F2EFE6;box-shadow:0 2px 8px rgba(0,0,0,0.3);`;
    
    toast.innerHTML = `<div style="font-weight:600;font-size:0.85rem;margin-bottom:0.25rem;">${title}</div><div style="font-size:0.75rem;color:#888880;">${message}</div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

document.getElementById('stockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const productId = document.getElementById('stockProductId').value;
    const action = document.getElementById('stockAction').value;
    const url = action === 'add' ? 
        '<?php echo e(route('admin.products.add-stock', ':id')); ?>'.replace(':id', productId) : 
        '<?php echo e(route('admin.products.reduce-stock', ':id')); ?>'.replace(':id', productId);

    const formData = new FormData(this);

fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
.then(response => response.json().catch(() => ({})))
     .then(data => {
         if (data.success) {
             location.reload();
         } else {
             showAdminToast('error', 'Gagal Menyimpan', data.message || 'Terjadi kesalahan saat memperbarui stok.');
         }
     })
     .catch(err => {
         console.error('Error:', err);
         showAdminToast('error', 'Kesalahan Jaringan', 'Periksa koneksi internet atau hubungi admin.');
     });
});

// Image preview functionality
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    const files = e.target.files;
    for (let i = 0; i < files.length; i++) {
        if (files[i].type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '60px';
                img.style.height = '60px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '4px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(files[i]);
        }
    }
});

function closeImagePreviewModal() {
     document.getElementById('imagePreviewModal').classList.remove('active');
 }

function deleteProduct(productId) {
      if (!confirm('Yakin ingin menghapus produk ini?')) return;
      
      const url = '<?php echo e(route('admin.products.destroy', ':id')); ?>'.replace(':id', productId);
      
      fetch(url, {
          method: 'DELETE',
          headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
      })
      .then(response => response.json().catch(() => ({})))
.then(data => {
           if (data.success) {
               location.reload();
           } else {
               adminToast.fire({ icon: 'error', title: data.message || 'Gagal menghapus produk. Cek Network tab di DevTools untuk detail.' });
           }
       })
       .catch(err => {
           console.error('Error:', err);
           adminToast.fire({ icon: 'error', title: 'Terjadi kesalahan jaringan. Periksa koneksi atau buka DevTools > Network.' });
       });
  }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/admin/products/index.blade.php ENDPATH**/ ?>