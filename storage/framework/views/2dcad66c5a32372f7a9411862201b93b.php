<?php $__env->startSection('title', 'Kategori Santri'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kategori Santri</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createKategoriModal">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>
    </div>

    <div id="alertContainer">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Keterangan</th>
                            <th>Biaya Makan</th>
                            <th>Biaya Asrama</th>
                            <th>Biaya Listrik</th>
                            <th>Biaya Kesehatan</th>
                            <th>Total SPP</th>
                            <th>Berlaku Mulai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($k->nama); ?></td>
                                <td><?php echo e($k->keterangan); ?></td>
                                <td>Rp <?php echo e(number_format($k->biaya_makan, 0, ',', '.')); ?></td>
                                <td>Rp <?php echo e(number_format($k->biaya_asrama, 0, ',', '.')); ?></td>
                                <td>Rp <?php echo e(number_format($k->biaya_listrik, 0, ',', '.')); ?></td>
                                <td>Rp <?php echo e(number_format($k->biaya_kesehatan, 0, ',', '.')); ?></td>
                                <td>
                                    <?php if($k->tarifTerbaru): ?>
                                        Rp <?php echo e(number_format($k->tarifTerbaru->nominal, 0, ',', '.')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">Belum diatur</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($k->tarifTerbaru): ?>
                                        <?php echo e($k->tarifTerbaru->berlaku_mulai->format('d/m/Y')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button"
                                            class="btn btn-sm btn-warning"
                                            onclick="editKategori(<?php echo e($k->id); ?>)">
                                            Edit
                                        </button>
                                        <form action="<?php echo e(route('admin.kategori.destroy', $k)); ?>"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return <?php echo e($k->nama === 'Reguler' ? 'confirmDeleteReguler(event)' : 'confirmDelete(event)'); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data kategori</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('admin.kategori.modals.create-edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('admin.kategori.modals.tarif', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/kategori.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\kategori\index.blade.php ENDPATH**/ ?>