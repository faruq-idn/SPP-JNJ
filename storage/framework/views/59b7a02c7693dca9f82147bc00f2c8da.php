<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pembayaran Berhasil</div>

                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            Pembayaran SPP Anda berhasil diproses. Terima kasih!
                        </div>
                        <a href="<?php echo e(route('wali.tagihan')); ?>" class="btn btn-primary">Kembali ke Tagihan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.wali', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\wali\pembayaran\success.blade.php ENDPATH**/ ?>