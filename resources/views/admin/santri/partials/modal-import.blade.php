<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.santri.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <i class="fas fa-info-circle me-2 mt-1"></i>
                            <div>
                                <strong>Petunjuk Import:</strong>
                                <ol class="ps-3 mb-0">
                                    <li>Download template Excel terlebih dahulu</li>
                                    <li>Isi data sesuai format yang ada</li>
                                    <li>Simpan file dalam format CSV</li>
                                    <li>Upload file CSV yang sudah diisi</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">File CSV</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                        <div class="form-text">
                            Format yang diterima: CSV (max. 2MB)
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <span class="text-muted">atau</span>
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('admin.santri.template.download') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i>
                            Download Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
