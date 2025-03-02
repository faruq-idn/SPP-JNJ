<div class="modal fade" id="modalGenerateTagihan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Tagihan Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Tagihan akan dibuat untuk semua santri aktif pada bulan yang dipilih.
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Bulan</label>
                            <select class="form-select" id="bulanGenerate" required>
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
                            <select class="form-select" id="tahunGenerate" required>
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
                <button type="button" class="btn btn-success" onclick="prosesGenerateTagihan()">
                    <i class="fas fa-sync me-1"></i>Generate Tagihan
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function generateTagihan() {
    $('#modalGenerateTagihan').modal('show');
}

function prosesGenerateTagihan() {
    const bulan = $('#bulanGenerate').val();
    const tahun = $('#tahunGenerate').val();
    const periode = `${tahun}-${bulan}`;

    Swal.fire({
        title: 'Konfirmasi Generate Tagihan',
        html: `Anda akan generate tagihan untuk:<br>
              <b>${$('#bulanGenerate option:selected').text()} ${tahun}</b><br><br>
              Apakah Anda yakin?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Generate',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                html: 'Sedang generate tagihan',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Send request
            $.post('<?php echo e(route('admin.pembayaran.generate-tagihan')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>',
                period: periode
            })
            .done(response => {
                $('#modalGenerateTagihan').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    confirmButtonColor: '#28a745'
                }).then(() => window.location.reload());
            })
            .fail(xhr => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat generate tagihan',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\pembayaran\partials\modal-generate.blade.php ENDPATH**/ ?>