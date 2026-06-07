<li class="nav-icon-wrapper">
    <a href="<?php echo e(route('notifications.index')); ?>" class="nav-icon-btn" title="Notifikasi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <?php if($unreadCount > 0): ?>
        <span class="nav-badge"><?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?></span>
        <?php endif; ?>
    </a>
    <div class="notif-dropdown" id="notifDropdown">
        <div class="notif-drop-header">
            <span>NOTIFIKASI</span>
            <a href="<?php echo e(route('notifications.index')); ?>" style="font-size: 0.7rem; color: var(--orange); text-decoration: none; font-family: var(--font-mono);">LIHAT SEMUA</a>
        </div>
        <div class="notif-drop-list" id="notifList">
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e($notification->is_read ? route('notifications.index') : '#'); ?>" class="notif-drop-item <?php echo e($notification->is_read ? '' : 'unread'); ?>" onclick="<?php echo e($notification->is_read ? '' : 'markAsRead(' . $notification->id . ')'); ?>">
                <div class="notif-drop-icon">
                    <?php if($notification->type === 'promo'): ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    <?php elseif($notification->type === 'system'): ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <?php else: ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <?php endif; ?>
                </div>
                <div class="notif-drop-body">
                    <div class="notif-drop-title"><?php echo e($notification->title); ?></div>
                    <div style="font-size: 0.72rem; color: var(--gray-mid); margin-bottom: 0.2rem; line-height: 1.4;"><?php echo e(mb_substr($notification->message, 0, 50)); ?></div>
                    <div class="notif-drop-time"><?php echo e($notification->created_at->diffForHumans()); ?></div>
                </div>
                <?php if(!$notification->is_read): ?>
                <span class="unread-dot"></span>
                <?php endif; ?>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="padding: 2rem; text-align: center; color: var(--gray-mid); font-size: 0.85rem;">
                Belum ada notifikasi
            </div>
            <?php endif; ?>
        </div>
    </div>
</li>
<?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/components/notif-dropdown.blade.php ENDPATH**/ ?>