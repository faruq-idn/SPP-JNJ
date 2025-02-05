<div class="modal fade" id="modalKenaikanKelas" tabindex="-1" aria-labelledby="modalKenaikanKelasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKenaikanKelasLabel">Kenaikan Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="kelas_tujuan" class="form-label">Kelas Tujuan</label>
                    <input type="text" class="form-control" id="kelas_tujuan" maxlength="3" placeholder="Contoh: 8A">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="prosesKenaikanKelas()">Proses Kenaikan Kelas</button>
            </div>
        </div>
    </div>
</div>
