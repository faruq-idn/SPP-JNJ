<div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Kenaikan Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <span id="totalSantri"></span> santri akan diproses kenaikan kelasnya.
                </div>
                <div class="alert alert-warning" id="lulusAlert" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="totalLulus"></span> santri akan lulus karena telah mencapai kelas akhir.
                </div>
                <div id="notificationMessage" class="mt-3">
                    <!-- Detail pesan akan diisi oleh JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmButton">
                    <i class="fas fa-check me-1"></i> Proses Kenaikan
                </button>
            </div>
        </div>
    </div>
</div>
