<div class="modal fade" id="modalHapusTagihan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Tagihan Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Perhatian! Tindakan ini akan menghapus semua tagihan pada bulan yang dipilih.
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Bulan</label>
                            <select class="form-select" id="bulanHapus" required>
                                <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bulan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                                        $namaBulan = \Carbon\Carbon::create(null, $bulan)->translatedFormat('F');
                                        $selected = $bulan == date('n') ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo e($bulanPadded); ?>" <?php echo e($selected); ?>>
                                        <?php echo e($namaBulan); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <select class="form-select" id="tahunHapus" required>
                                <?php $__currentLoopData = range(date('Y')-1, date('Y')+1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $selected = $tahun == date('Y') ? 'selected' : ''; ?>
                                    <option value="<?php echo e($tahun); ?>" <?php echo e($selected); ?>><?php echo e($tahun); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="prosesHapusTagihan()">
                    <i class="fas fa-trash me-1"></i>Hapus Tagihan
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\pembayaran\partials\modal-hapus.blade.php ENDPATH**/ ?>