<nav class="nav flex-column">
    <div class="p-3">
        <div class="d-flex align-items-center mb-3">
            <div class="flex-shrink-0">
                <i class="fas fa-user-circle fa-2x text-light"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-0 text-light"><?php echo e(Auth::user()->name); ?></h6>
                <small class="text-light opacity-75">Wali Santri</small>
            </div>
        </div>
    </div>

    <div class="nav-items">
        <a href="<?php echo e(route('wali.dashboard')); ?>"
           class="nav-link d-flex align-items-center <?php echo e(Route::is('wali.dashboard') ? 'active' : ''); ?>">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?php echo e(route('wali.tagihan')); ?>"
           class="nav-link d-flex align-items-center <?php echo e(Route::is('wali.tagihan') ? 'active' : ''); ?>">
            <i class="fas fa-file-invoice-dollar fa-fw me-3"></i>
            <span>Tagihan & Riwayat SPP</span>
        </a>
    </div>
</nav>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\layouts\partials\sidebar-wali.blade.php ENDPATH**/ ?>