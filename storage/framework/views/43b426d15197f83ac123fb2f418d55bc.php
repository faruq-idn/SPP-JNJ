<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Data Santri -->
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Data Santri</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td style="width: 100px">Nama</td>
                                <td>: <span id="detail-santri-nama"></span></td>
                            </tr>
                            <tr>
                                <td>NISN</td>
                                <td>: <span id="detail-santri-nisn"></span></td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>: <span id="detail-santri-kelas"></span></td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>: <span id="detail-santri-kategori"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Data Pembayaran -->
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Data Pembayaran</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td style="width: 100px">Tanggal</td>
                                <td>: <span id="detail-pembayaran-tanggal"></span></td>
                            </tr>
                            <tr>
                                <td>Tagihan Bulan</td>
                                <td>: <span id="detail-pembayaran-bulan"></span></td>
                            </tr>
                            <tr>
                                <td>Nominal</td>
                                <td>: Rp <span id="detail-pembayaran-nominal"></span></td>
                            </tr>
                            <tr>
                                <td>Metode</td>
                                <td>: <span id="detail-pembayaran-metode"></span></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: <span id="detail-pembayaran-status"></span></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>: <span id="detail-pembayaran-keterangan"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function showDetail(id) {
    // Reset modal content
    $('#detailModal .modal-body span[id^="detail-"]').text('-');

    // Show modal with loading state
    $('#detailModal').modal('show');

    // Fetch detail data
    $.get(`<?php echo e(url('admin/pembayaran')); ?>/${id}`)
        .done(function(response) {
            // Update Santri Info
            $('#detail-santri-nama').text(response.santri.nama);
            $('#detail-santri-nisn').text(response.santri.nisn);
            $('#detail-santri-kelas').text(response.santri.kelas);
            $('#detail-santri-kategori').text(response.santri.kategori);

            // Update Pembayaran Info
            $('#detail-pembayaran-tanggal').text(response.pembayaran.tanggal);
            $('#detail-pembayaran-bulan').text(response.pembayaran.bulan + ' ' + response.pembayaran.tahun);
            $('#detail-pembayaran-nominal').text(response.pembayaran.nominal);
            $('#detail-pembayaran-metode').text(response.pembayaran.metode);
            $('#detail-pembayaran-status').text(response.pembayaran.status);
            $('#detail-pembayaran-keterangan').text(response.pembayaran.keterangan);
        })
        .fail(function() {
            Swal.fire('Error', 'Gagal memuat detail pembayaran', 'error');
            $('#detailModal').modal('hide');
        });
}
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\pembayaran\partials\modal-detail.blade.php ENDPATH**/ ?>