

<?php $__env->startSection('title', 'Katalog Produk'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.shop-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 2rem;
}

.sidebar {
    flex-shrink: 0;
}

.sidebar-card {
    background: var(--surface);
    border: 1px solid rgba(242, 239, 230, 0.07);
    border-radius: 3px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.sidebar-title {
    font-family: var(--font-mono);
    font-size: 0.65rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--orange);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sidebar-title::before {
    content: '';
    width: 14px;
    height: 1px;
    background: var(--orange);
}

.filter-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.filter-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0.6rem;
    border-radius: 2px;
    cursor: pointer;
    font-size: 0.85rem;
    color: var(--gray-light);
    transition: all 0.15s;
}

.filter-item:hover {
    background: rgba(242, 239, 230, 0.05);
    color: var(--off-white);
}

.filter-item.active {
    background: rgba(232, 82, 26, 0.12);
    color: var(--orange);
}

.filter-item .count {
    font-family: var(--font-mono);
    font-size: 0.65rem;
    color: var(--gray-mid);
    padding: 0.1rem 0.4rem;
    background: rgba(242, 239, 230, 0.06);
    border-radius: 2px;
}

.filter-check {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.45rem 0;
    cursor: pointer;
    font-size: 0.85rem;
    color: var(--gray-light);
}

.filter-check input[type=checkbox] {
    display: none;
}

.check-box {
    width: 16px;
    height: 16px;
    border: 1px solid rgba(242, 239, 230, 0.2);
    border-radius: 2px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filter-check.checked .check-box {
    background: var(--orange);
    border-color: var(--orange);
}

.filter-check.checked .check-box::after {
    content: '✓';
    font-size: 10px;
    color: white;
}

.star-ico.empty {
    color: rgba(242, 239, 230, 0.15);
}

.price-range-container {
    margin-top: 0.75rem;
}

.price-range-container input[type=range] {
    -webkit-appearance: none;
    width: 100%;
    height: 4px;
    background: rgba(242, 239, 230, 0.15);
    border-radius: 2px;
    outline: none;
}

.price-range-container input[type=range]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 16px;
    height: 16px;
    background: var(--orange);
    border-radius: 50%;
    cursor: pointer;
}

.price-range-container .range-values {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.75rem;
    color: var(--gray-mid);
}

.shop-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.sort-select {
    padding: 0.5rem 2.2rem 0.5rem 0.9rem;
    background: var(--surface);
    border: 1px solid rgba(242, 239, 230, 0.1);
    border-radius: 2px;
    color: var(--off-white);
    font-family: var(--font-body);
    font-size: 0.82rem;
    outline: none;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23888880' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.7rem center;
}

.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.7rem;
    background: rgba(232, 82, 26, 0.1);
    border: 1px solid rgba(232, 82, 26, 0.2);
    border-radius: 2px;
    font-size: 0.75rem;
    color: var(--orange);
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    justify-content: flex-start;
}

.prod-card {
    background: var(--surface);
    position: relative;
    overflow: hidden;
    transition: background 0.2s;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    border-radius: 4px;
    border: 1px solid rgba(242, 239, 230, 0.03);
    height: 100%;
}

.prod-card:hover {
    background: var(--surface-2);
}

.prod-img {
    aspect-ratio: 1 !important;
    width: 100% !important;
    height: auto !important;
    max-height: 250px;
    background: #1A1A16;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 0.5rem;
    flex-shrink: 0;
}

.prod-img img {
    width: 100% !important;
    height: 100% !important;
    object-fit: contain !important;
}

.prod-img-ph {
    opacity: 0.15;
}

.prod-body {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.prod-brand {
    font-family: var(--font-mono);
    font-size: 0.6rem;
    color: var(--orange);
    margin-bottom: 0.3rem;
    text-transform: uppercase;
}

.prod-name {
    font-size: 0.87rem;
    font-weight: 500;
    line-height: 1.45;
    margin-bottom: 0.4rem;
}

.prod-spec {
    font-size: 0.75rem;
    color: var(--gray-mid);
    margin-bottom: 0.75rem;
}

.prod-rating {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.75rem;
    font-size: 0.72rem;
    color: var(--gray-mid);
}

.prod-rating .s {
    color: var(--orange);
}

.prod-footer-price {
    margin-top: auto;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
}

.prod-price {
    font-family: var(--font-display);
    font-size: 1.35rem;
    color: var(--off-white);
}

.prod-price-old {
    font-size: 0.72rem;
    color: var(--gray-mid);
    text-decoration: line-through;
}

.prod-add-btn {
    width: 34px;
    height: 34px;
    background: transparent;
    border: 1px solid rgba(242, 239, 230, 0.2);
    border-radius: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--off-white);
}

/* Tambahkan atau pastikan baris media query ini ada di file catalog.blade.php kamu */
@media (max-width: 1024px) {
    .shop-layout {
        grid-template-columns: 1fr !important;
    }

    .sidebar {
        display: none;
    }

    /* Sembunyikan filter sidebar besar di HP */
    .product-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.75rem !important;
    }
}

@media (max-width: 480px) {
    .product-grid {
        grid-template-columns: 1fr !important;
    }

    /* 1 Kolom penuh di HP berlayar kecil */
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper"> 
    <div class="breadcrumb">
        <a href="<?php echo e(route('home')); ?>">HOME</a> <span>/</span> KATALOG
    </div>
<div class="shop-layout">
    <aside class="sidebar">
        <div class="sidebar-card">
            <div class="sidebar-title">Kategori</div>
            <ul class="filter-list">
                <li>
                    <a href="<?php echo e(route('produk.index')); ?>" class="filter-item <?php echo e(!($selectedCategory ?? false) ? 'active' : ''); ?>">
                        Semua Produk <span class="count"><?php echo e(number_format(\App\Models\Product::count())); ?></span>
                    </a>
                </li>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="<?php echo e(route('produk.index', array_merge(request()->except(['category', 'page']), ['category' => $cat->id]))); ?>" class="filter-item <?php echo e(($selectedCategory ?? false) == $cat->id ? 'active' : ''); ?>">
                        <?php echo e($cat->name); ?> <span class="count"><?php echo e(number_format($cat->products_count)); ?></span>
                    </a>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div class="sidebar-card">
            <div class="sidebar-title">Rentang Harga</div>
            <div class="price-range-container">
                <div class="range-values">
                    <span>Rp <span id="minPriceLabel"><?php echo e(number_format($minPrice ?? $priceStats->min_price ?? 0, 0, ',', '.')); ?></span></span>
                    <span>Rp <span id="maxPriceLabel"><?php echo e(number_format($maxPrice ?? $priceStats->max_price ?? 1000000, 0, ',', '.')); ?></span></span>
                </div>
                <div class="double-range-wrapper" style="position: relative; height: 30px; margin: 1rem 0;">
                    <div style="position: absolute; top: 50%; left: 0; right: 0; height: 4px; background: rgba(242,239,230,0.15); border-radius: 2px; transform: translateY(-50%);"></div>
                    <input type="range" id="priceRangeMin" min="<?php echo e($priceStats->min_price ?? 0); ?>" max="<?php echo e($priceStats->max_price ?? 1000000); ?>" value="<?php echo e($minPrice ?? $priceStats->min_price ?? 0); ?>" step="1000" style="position: absolute; width: 100%; height: 30px; background: transparent; pointer-events: none; -webkit-appearance: none; margin: 0;">
                    <input type="range" id="priceRangeMax" min="<?php echo e($priceStats->min_price ?? 0); ?>" max="<?php echo e($priceStats->max_price ?? 1000000); ?>" value="<?php echo e($maxPrice ?? $priceStats->max_price ?? 1000000); ?>" step="1000" style="position: absolute; width: 100%; height: 30px; background: transparent; pointer-events: none; -webkit-appearance: none; margin: 0;">
                </div>
                <style>
                    .double-range-wrapper input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; width: 16px; height: 16px; background: var(--orange); border-radius: 50%; cursor: pointer; pointer-events: auto; }
                    .double-range-wrapper input[type=range] { background: none; }
                </style>
            </div>
            <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                <button type="button" onclick="applyPriceFilter()" class="btn btn-primary btn-sm" style="flex: 1; justify-content: center;">Terapkan</button>
                <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline btn-sm" style="text-align: center; padding: 0.45rem 0.8rem; font-size: 0.75rem;">Reset</a>
            </div>
            <form id="priceFilterForm" action="<?php echo e(route('produk.index')); ?>" method="GET" style="display: none;">
                <?php if($search ?? false): ?>
                <input type="hidden" name="search" value="<?php echo e($search); ?>">
                <?php endif; ?>
                <?php if($selectedCategory ?? false): ?>
                <input type="hidden" name="category" value="<?php echo e($selectedCategory); ?>">
                <?php endif; ?>
                <input type="hidden" name="min_price" id="hiddenMinPrice" value="<?php echo e($minPrice ?? $priceStats->min_price ?? 0); ?>">
                <input type="hidden" name="max_price" id="hiddenMaxPrice" value="<?php echo e($maxPrice ?? $priceStats->max_price ?? 1000000); ?>">
            </form>
            <script>
                const sliderMin = document.getElementById('priceRangeMin');
                const sliderMax = document.getElementById('priceRangeMax');
                const minLabel = document.getElementById('minPriceLabel');
                const maxLabel = document.getElementById('maxPriceLabel');
                const minInput = document.getElementById('hiddenMinPrice');
                const maxInput = document.getElementById('hiddenMaxPrice');
                
                function updateLabels() {
                    if (minLabel && maxLabel) {
                        minLabel.textContent = new Intl.NumberFormat('id-ID').format(sliderMin.value);
                        maxLabel.textContent = new Intl.NumberFormat('id-ID').format(sliderMax.value);
                    }
                    if (minInput && maxInput) {
                        minInput.value = sliderMin.value;
                        maxInput.value = sliderMax.value;
                    }
                }
                
                if (sliderMin && sliderMax) {
                    sliderMin.addEventListener('input', function() {
                        if (parseInt(sliderMin.value) > parseInt(sliderMax.value)) {
                            sliderMin.value = sliderMax.value;
                        }
                        updateLabels();
                    });
                    sliderMax.addEventListener('input', function() {
                        if (parseInt(sliderMax.value) < parseInt(sliderMin.value)) {
                            sliderMax.value = sliderMin.value;
                        }
                        updateLabels();
                    });
                }
                
                function applyPriceFilter() {
                    document.getElementById('priceFilterForm').submit();
                }
            </script>
        </div>
    </aside>

    <main class="shop-main">
        <div class="shop-topbar">
            <div style="font-size:0.82rem; color:var(--gray-mid);">Menampilkan <strong><?php echo e($products->firstItem() ?? 0); ?>–<?php echo e($products->lastItem() ?? 0); ?></strong> dari
                <strong><?php echo e($products->total()); ?></strong> produk</div>
            <select class="sort-select">
                <option>Terlaris</option>
                <option>Harga Terendah</option>
            </select>
        </div>

        <div class="product-grid">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('produk.show', $p->id)); ?>" class="prod-card">
                <div class="prod-img">
                    <?php if($p->images->count() > 0): ?>
                        <img src="<?php echo e(asset('storage/' . $p->images->first()->image_path)); ?>" alt="<?php echo e($p->name); ?>">
                    <?php else: ?>
                        <svg width="80" height="80" viewBox="0 0 100 100" class="prod-img-ph"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="15" y="15" width="70" height="70" rx="4" fill="none" stroke="#F2EFE6"
                                stroke-width="1.5" opacity="0.3" />
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="prod-body">
                    <div class="prod-brand"><?php echo e($p->brand); ?></div>
                    <div class="prod-name"><?php echo e($p->name); ?></div>
                    <div class="prod-spec">Stok: <?php echo e($p->current_stock); ?> <?php echo e($p->unit); ?></div>
                    <div class="prod-rating"><span class="s">★</span> 4.8</div>
<div class="prod-footer-price">
                      <div>
                          <?php 
                              $tierPrice = $p->prices->where('price_level', $tierPriceLevel)->first()?->price ?? $p->base_price ?? 0;
                              $level1Price = $p->prices->where('price_level', 1)->first()?->price ?? $p->base_price ?? 0;
                          ?>
                          <?php if($tierPriceLevel > 1): ?>
                               <div class="prod-price-old">Rp <?php echo e(number_format($level1Price, 0, ',', '.')); ?></div>
                           <?php endif; ?>
                           <div class="prod-price">Rp <?php echo e(number_format($tierPrice, 0, ',', '.')); ?></div>
                      </div>
                      <button class="prod-add-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                  stroke-width="2.5">
                                  <path d="M12 5v14M5 12h14" />
                              </svg></button>
                      </div>
                 </div>
             </a>
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="pagination">
            <?php echo e($products->links('pagination::default')); ?>

        </div>
    </main>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/catalog.blade.php ENDPATH**/ ?>