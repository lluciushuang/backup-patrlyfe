<?php $__env->startSection('title', 'Daftar - Partlyfe'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.auth-wrapper {
    min-height: calc(100vh - 64px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}
.auth-card {
    background: var(--surface);
    border: 1px solid rgba(242, 239, 230, 0.08);
    border-radius: 4px;
    padding: 3rem;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
.auth-title {
    font-family: var(--font-display);
    font-size: 2rem;
    letter-spacing: 0.05em;
    color: var(--off-white);
    text-align: center;
    margin-bottom: 2rem;
}
.auth-title span { color: var(--orange); }
.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: var(--gray-mid); }
.auth-input {
    width: 100%; padding: 0.75rem 1rem; box-sizing: border-box;
    background: #1A1A16; border: 1px solid rgba(242, 239, 230, 0.1);
    color: var(--off-white); border-radius: 2px; font-family: var(--font-body);
    font-size: 0.88rem; outline: none; transition: border-color 0.2s;
}
.auth-input:focus { border-color: var(--orange); }
.auth-btn { width: 100%; padding: 1rem; background: var(--orange); color: white; border: none; border-radius: 2px; font-size: 0.95rem; font-weight: 600; cursor: pointer; }
.auth-link { display: block; text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: var(--gray-mid); }
.auth-link a { color: var(--orange); text-decoration: none; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">DAFTAR <span>PARTLYFE</span></h1>
        
        <form method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="auth-input" placeholder="Masukkan nama lengkap" required value="<?php echo e(old('name')); ?>">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color: var(--red); font-size: 0.75rem; margin-top: 0.25rem;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="auth-input" placeholder="contoh@email.com" required value="<?php echo e(old('email')); ?>">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color: var(--red); font-size: 0.75rem; margin-top: 0.25rem;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="auth-input" placeholder="••••••••" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="auth-input" placeholder="••••••••" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">No. HP (opsional)</label>
                <input type="text" name="phone" class="auth-input" placeholder="08xx xxxx xxxx" value="<?php echo e(old('phone')); ?>">
            </div>
            
            <button type="submit" class="auth-btn">BUAT AKUN</button>
            
            <div class="auth-link">
                Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Masuk Sekarang</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenna\Herd\partlyfe_satu\resources\views/auth/register.blade.php ENDPATH**/ ?>