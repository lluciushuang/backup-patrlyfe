<?php if($paginator->hasPages()): ?>
    <nav aria-label="Pagination">
        <ul class="admin-pagination">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="pagination-item disabled">
                    <span class="pagination-link">&lsaquo; Sebelumnya</span>
                </li>
            <?php else: ?>
                <li class="pagination-item">
                    <a class="pagination-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">&lsaquo; Sebelumnya</a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_string($element)): ?>
                    <li class="pagination-item disabled"><span class="pagination-link"><?php echo e($element); ?></span></li>
                <?php endif; ?>

                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="pagination-item active"><span class="pagination-link"><?php echo e($page); ?></span></li>
                        <?php else: ?>
                            <li class="pagination-item"><a class="pagination-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="pagination-item">
                    <a class="pagination-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">Selanjutnya &rsaquo;</a>
                </li>
            <?php else: ?>
                <li class="pagination-item disabled">
                    <span class="pagination-link">Selanjutnya &rsaquo;</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>

<style>
.admin-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
    padding: 0;
    margin: 1.5rem 0 0;
}
.pagination-item {
    display: inline-flex;
    list-style: none;
}
.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 0.75rem;
    border: 1px solid rgba(242, 239, 230, 0.12);
    background: var(--surface);
    color: var(--gray-light);
    font-family: var(--font-mono);
    font-size: 0.85rem;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.pagination-link:hover {
    background: rgba(242, 239, 230, 0.08);
    color: var(--off-white);
    border-color: rgba(242, 239, 230, 0.2);
}
.pagination-item.active .pagination-link {
    background: var(--orange);
    border-color: var(--orange);
    color: white;
}
.pagination-item.disabled .pagination-link {
    opacity: 0.4;
    cursor: not-allowed;
}
</style><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/vendor/pagination/admin.blade.php ENDPATH**/ ?>