<?php $__env->startPush('styles'); ?>
<!-- DataTables -->
<link href="<?php echo e(asset('vendor/datatables/css/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', 'Riwayat Kenaikan Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Kenaikan Kelas</h1>
    </div>

    <div class="card">
        <div class="card-body px-0">
            <div class="table-responsive px-3">
                <table class="table table-bordered table-striped w-100" id="dataTable">
                    <thead>
                        <tr>
                            <th>Nama Santri</th>
                            <th>Kelas Sebelum</th>
                            <th>Kelas Sesudah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Diproses Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($r->santri->nama); ?></td>
                            <td><?php echo e($r->santri->jenjang); ?> <?php echo e($r->kelas_sebelum); ?></td>
                            <td><?php echo e($r->kelas_sesudah ? $r->santri->jenjang . ' ' . $r->kelas_sesudah : 'Lulus'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($r->status === 'aktif' ? 'success' : ($r->status === 'lulus' ? 'info' : 'secondary')); ?>">
                                    <?php echo e(ucfirst($r->status)); ?>

                                </span>
                            </td>
                            <td><?php echo e($r->created_at->format('d/m/Y H:i')); ?></td>
                            <td><?php echo e($r->creator->name); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada riwayat kenaikan kelas</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- DataTables -->
<script src="<?php echo e(asset('vendor/datatables/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/datatables/js/dataTables.bootstrap5.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/datatable-init.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\santri\riwayat.blade.php ENDPATH**/ ?>