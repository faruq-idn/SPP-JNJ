<?php $__env->startSection('title', 'Detail Santri'); ?>

<?php $__env->startPush('styles'); ?>
<!-- Select2 -->
<link href="<?php echo e(asset('vendor/select2/css/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('vendor/select2/css/select2-bootstrap-5-theme.min.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Santri</h1>
        <div class="d-flex gap-2">
            <?php if(auth()->user()->role === 'admin'): ?>
                <button type="button"
                        class="btn btn-warning d-flex align-items-center gap-2"
                        data-bs-toggle="modal"
                        data-bs-target="#santriFormModal"
                        data-mode="edit"
                        data-id="<?php echo e($santri->id); ?>">
                    <i class="fas fa-edit"></i> Edit Data
                </button>
            <?php endif; ?>
            <a href="<?php echo e(url()->previous()); ?>"
               class="btn btn-secondary d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Data Santri & Wali-->
        <div class="col-md-6 mb-4">
            <?php echo $__env->make('shared.santri._data_santri', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php echo $__env->make('shared.santri._wali_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <!-- Kategori -->
        <div class="col-md-6 mb-4">
            <?php echo $__env->make('shared.santri._kategori_tarif', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="col-12">
            <div class="card shadow-sm">
                <?php echo $__env->make('shared.santri._riwayat_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php echo $__env->make('shared.santri._show_table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
    <?php echo $__env->make('shared.santri._show_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('admin.santri._form_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views/admin/santri/show.blade.php ENDPATH**/ ?>