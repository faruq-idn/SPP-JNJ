<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pembayaran Gagal</div>

                    <div class="card-body">
                        <?php if(session('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo e(session('error')); ?>

                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi atau hubungi administrator.
                            </div>
                        <?php endif; ?>
                        <a href="<?php echo e(route('wali.tagihan')); ?>" class="btn btn-primary">Kembali ke Tagihan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.wali', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\wali\pembayaran\error.blade.php ENDPATH**/ ?>