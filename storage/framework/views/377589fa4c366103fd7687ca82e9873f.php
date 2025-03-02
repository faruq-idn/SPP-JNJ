<div class="modal fade" id="modalKenaikanKelas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Kenaikan Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian!</strong>
                    <p class="mb-2">Tindakan ini akan memproses kenaikan kelas untuk semua santri aktif:</p>
                    <ul class="mb-0">
                        <li>Kelas 1-8 SMP akan naik satu tingkat</li>
                        <li>Kelas 9 SMP akan berstatus LULUS</li>
                        <li>Kelas 10-11 SMA akan naik satu tingkat</li>
                        <li>Kelas 12 SMA akan berstatus LULUS</li>
                    </ul>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Santri yang lulus akan tercatat tahun kelulusannya dan status akan berubah menjadi "lulus"
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="prosesKenaikanKelas()">
                    <i class="fas fa-graduation-cap me-1"></i>
                    Proses Kenaikan Kelas
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\santri\partials\modal-kenaikan-kelas.blade.php ENDPATH**/ ?>