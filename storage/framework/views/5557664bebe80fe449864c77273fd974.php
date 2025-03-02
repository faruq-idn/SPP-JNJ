<?php $__env->startSection('title', 'Tagihan & Riwayat SPP'); ?>

<?php echo $__env->make('layouts.partials.dropdown-santri', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-2 p-md-4 mb-5">
    <div class="row g-2 g-md-3">
        <div class="col-12">
            <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <!-- Riwayat Pembayaran per Tahun -->
            <?php $__currentLoopData = $pembayaranPerTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun => $pembayaranBulanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Tahun <?php echo e($tahun); ?></h5>
                </div>
                <!-- Mobile View -->
                <div class="d-block d-md-none">
                    <div class="vstack gap-2">
                        <?php $__currentLoopData = $pembayaranBulanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembayaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card border-0 bg-light shadow-sm"
                             style="cursor: pointer"
                             onclick="showDetailPembayaran(<?php echo e($pembayaran->id); ?>, '<?php echo e($pembayaran->nama_bulan); ?>', <?php echo e($pembayaran->nominal); ?>, '<?php echo e($pembayaran->status); ?>', '<?php echo e($pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-'); ?>', '<?php echo e($pembayaran->metode_pembayaran ? $pembayaran->metode_pembayaran->nama : '-'); ?>', '<?php echo e($pembayaran->tahun); ?>')">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="vstack gap-1">
                                        <span class="fw-bold fs-6"><?php echo e($pembayaran->nama_bulan); ?></span>
                                        <span class="badge bg-<?php echo e($pembayaran->status == 'success' ? 'success' : ($pembayaran->status == 'pending' ? 'warning' : 'danger')); ?> fs-7">
                                            <?php if($pembayaran->status == 'success'): ?>
                                                Lunas
                                            <?php elseif($pembayaran->status == 'pending'): ?>
                                                Pending
                                            <?php else: ?>
                                                Belum Lunas
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-primary fs-7">
                                            Rp <?php echo e(number_format($pembayaran->nominal, 0, ',', '.')); ?>

                                        </div>
                                        <?php if($pembayaran->tanggal_bayar): ?>
                                        <small class="text-muted fs-7">
                                            <?php echo e($pembayaran->tanggal_bayar->format('d/m/Y H:i')); ?>

                                        </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Desktop View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Bulan</th>
                                <th>Status</th>
                                <th>Nominal</th>
                                <th>Tanggal Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pembayaranBulanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembayaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr style="cursor: pointer" onclick="showDetailPembayaran(<?php echo e($pembayaran->id); ?>, '<?php echo e($pembayaran->nama_bulan); ?>', <?php echo e($pembayaran->nominal); ?>, '<?php echo e($pembayaran->status); ?>', '<?php echo e($pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-'); ?>', '<?php echo e($pembayaran->metode_pembayaran ? $pembayaran->metode_pembayaran->nama : '-'); ?>', '<?php echo e($pembayaran->tahun); ?>')">
                                <td><?php echo e($pembayaran->nama_bulan); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($pembayaran->status == 'success' ? 'success' : ($pembayaran->status == 'pending' ? 'warning' : 'danger')); ?>">
                                        <?php if($pembayaran->status == 'success'): ?>
                                            Lunas
                                        <?php elseif($pembayaran->status == 'pending'): ?>
                                            Pending
                                        <?php else: ?>
                                            Belum Lunas
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>Rp <?php echo e(number_format($pembayaran->nominal, 0, ',', '.')); ?></td>
                                <td><?php echo e($pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php echo $__env->make('layouts.partials.modal-detail-pembayaran', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.wali', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views/wali/tagihan/index.blade.php ENDPATH**/ ?>