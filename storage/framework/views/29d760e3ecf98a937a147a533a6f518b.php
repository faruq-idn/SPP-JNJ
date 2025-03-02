<nav class="nav flex-column">
    <ul class="nav flex-column">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link <?php echo e(Request::is('petugas/dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('petugas.dashboard')); ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Data Santri dengan Sub Menu -->
        <li class="nav-item">
            <div class="nav-link-wrapper">
                <a class="nav-link <?php echo e(Request::is('petugas/santri*') ? 'active' : ''); ?>"
                   href="<?php echo e(route('petugas.santri.index')); ?>">
                    <i class="fas fa-users"></i>
                    <span>Data Santri</span>
                </a>
                <button type="button" 
                        class="btn-dropdown <?php echo e(Request::is('petugas/santri*') ? '' : 'collapsed'); ?>"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseKelas"
                        aria-expanded="<?php echo e(Request::is('petugas/santri*') ? 'true' : 'false'); ?>"
                        aria-label="Toggle navigation">
                    <i class="fas fa-angle-down"></i>
                </button>
            </div>
            <div id="collapseKelas" class="collapse <?php echo e(Request::is('petugas/santri*') ? 'show' : ''); ?>">
                <div class="submenu">
                    <div class="submenu-sections">
                        <div class="submenu-section">
                            <div class="submenu-header">SMP</div>
                            <div class="submenu-items">
                                <?php
                                    $kelasSMP = ['7A', '7B', '8A', '8B', '9A', '9B'];
                                ?>
                                <?php $__currentLoopData = $kelasSMP; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $count = \App\Models\Santri::where('jenjang', 'SMP')
                                            ->where('kelas', $kelas)
                                            ->where('status', 'aktif')
                                            ->count();
                                        $isActive = isset($currentKelas) &&
                                                  $currentKelas['jenjang'] == 'SMP' &&
                                                  $currentKelas['kelas'] == $kelas;
                                    ?>
                                    <a class="submenu-item <?php echo e($isActive ? 'active' : ''); ?>"
                                       href="<?php echo e(route('petugas.santri.kelas', ['jenjang' => 'smp', 'kelas' => $kelas])); ?>"
                                       title="Kelas <?php echo e($kelas); ?>">
                                        <span>Kelas <?php echo e($kelas); ?></span>
                                        <span class="badge"><?php echo e($count); ?></span>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="submenu-section">
                            <div class="submenu-header">SMA</div>
                            <div class="submenu-items">
                                <?php
                                    $kelasSMA = ['10A', '10B', '11A', '11B', '12A', '12B'];
                                ?>
                                <?php $__currentLoopData = $kelasSMA; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $count = \App\Models\Santri::where('jenjang', 'SMA')
                                            ->where('kelas', $kelas)
                                            ->where('status', 'aktif')
                                            ->count();
                                        $isActive = isset($currentKelas) &&
                                                  $currentKelas['jenjang'] == 'SMA' &&
                                                  $currentKelas['kelas'] == $kelas;
                                    ?>
                                    <a class="submenu-item <?php echo e($isActive ? 'active' : ''); ?>"
                                       href="<?php echo e(route('petugas.santri.kelas', ['jenjang' => 'sma', 'kelas' => $kelas])); ?>"
                                       title="Kelas <?php echo e($kelas); ?>">
                                        <span>Kelas <?php echo e($kelas); ?></span>
                                        <span class="badge"><?php echo e($count); ?></span>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <!-- Pembayaran SPP -->
        <li class="nav-item">
            <a class="nav-link <?php echo e(Request::routeIs('petugas.pembayaran.*') ? 'active' : ''); ?>"
               href="<?php echo e(route('petugas.pembayaran.index')); ?>">
                <i class="fas fa-money-bill"></i>
                <span>Pembayaran SPP</span>
            </a>
        </li>

        <!-- Laporan -->
        <li class="nav-item">
            <a class="nav-link <?php echo e(Request::routeIs('petugas.laporan.*') ? 'active' : ''); ?>"
               href="<?php echo e(route('petugas.laporan.index')); ?>">
                <i class="fas fa-file-alt"></i>
                <span>Laporan</span>
            </a>
        </li>
    </ul>
</nav>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views/layouts/partials/sidebar-petugas.blade.php ENDPATH**/ ?>