<?php $__env->startSection('title', 'Data Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pembayaran</h1>
        <div class="d-flex gap-2">
            <!-- Tombol untuk admin dihilangkan -->
        </div>
    </div>

    <!-- Belum Lunas Card -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-primary">Belum Lunas</h6>
            <span class="badge bg-danger"><?php echo e($totalBelumLunas); ?> tagihan</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Santri</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pembayaranPending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold"><?php echo e($p->santri->nama); ?></span>
                                    <span class="small text-muted"><?php echo e($p->santri->nisn); ?></span>
                                </div>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::createFromDate(null, $p->bulan, 1)->translatedFormat('F')); ?> <?php echo e($p->tahun); ?></td>
                            <td>Rp <?php echo e(number_format($p->nominal, 0, ',', '.')); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($p->status == 'unpaid' ? 'danger' : 'warning'); ?>">
                                    <?php echo e($p->status == 'unpaid' ? 'Belum Lunas' : 'Pending'); ?>

                                </span>
                            </td>
                            <td><?php echo e($p->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <a href="<?php echo e(route('petugas.santri.show', $p->santri_id)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-3">Tidak ada tagihan yang belum lunas</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php echo e($pembayaranPending->onEachSide(1)->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>

    <!-- Lunas Card -->
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-success">Sudah Lunas</h6>
            <span class="badge bg-success"><?php echo e($totalLunas); ?> tagihan</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Santri</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Tanggal Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pembayaranLunas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold"><?php echo e($p->santri->nama); ?></span>
                                    <span class="small text-muted"><?php echo e($p->santri->nisn); ?></span>
                                </div>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::createFromDate(null, $p->bulan, 1)->translatedFormat('F')); ?> <?php echo e($p->tahun); ?></td>
                            <td>Rp <?php echo e(number_format($p->nominal, 0, ',', '.')); ?></td>
                            <td>
                                <?php if($p->metode_pembayaran): ?>
                                    <span class="badge bg-info"><?php echo e($p->metode_pembayaran->nama); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y H:i') : '-'); ?></td>
                            <td>
                                <a href="<?php echo e(route('petugas.santri.show', $p->santri_id)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-3">Belum ada pembayaran yang lunas</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php echo e($pembayaranLunas->onEachSide(1)->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\petugas\pembayaran\index.blade.php ENDPATH**/ ?>