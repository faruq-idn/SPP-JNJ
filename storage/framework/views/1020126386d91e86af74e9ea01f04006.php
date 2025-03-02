<?php $__env->startSection('title', 'Hubungkan Santri'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-link me-2"></i>Hubungkan Santri
            </h2>

            <!-- Card Santri Belum Terhubung -->
            <?php if($unlinked_santri->isNotEmpty()): ?>
                <div class="card border-warning border-2 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Santri Belum Terhubung</h5>
                                <p class="card-text text-muted mb-0">
                                    Ditemukan <?php echo e($unlinked_santri->count()); ?> santri yang belum terhubung dengan akun Anda
                                </p>
                            </div>
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>NISN</th>
                                        <th>Nama Santri</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $unlinked_santri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $us): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($us->nisn); ?></td>
                                            <td><?php echo e($us->nama); ?></td>
                                            <td><?php echo e($us->jenjang); ?> <?php echo e($us->kelas); ?></td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>Menunggu Verifikasi
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Card Santri Tersedia -->
            <div class="card border-primary border-2 shadow-sm mb-4">
                <div class="card-body">
                    <!-- ... kode card santri tersedia sama seperti sebelumnya ... -->
                </div>
            </div>

            <!-- Card Kontak Admin -->
            <div class="card border-info border-2 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-phone-alt text-info fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">Kontak Admin</h5>
                            <p class="card-text text-muted mb-0">
                                Silakan hubungi admin untuk verifikasi data
                            </p>
                        </div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <ul class="mb-0">
                            <li>Telepon/WA: <strong>(024) 7471 xxxx</strong></li>
                            <li>Email: <strong>admin@pesantren.sch.id</strong></li>
                            <li>Jam Kerja: <strong>Senin-Jumat, 08.00-15.00 WIB</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.wali', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views/wali/hubungkan.blade.php ENDPATH**/ ?>