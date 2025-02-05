<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="importForm" action="{{ route('admin.santri.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2" id="uploadStatus">Mempersiapkan upload...</p>
                        <div class="progress mt-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 style="width: 0%"
                                 id="uploadProgress">0%</div>
                        </div>
                    </div>

                    <!-- Alert Container -->
                    <div id="alertContainer"></div>

                    @if($errors->has('import_errors'))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->get('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">File Excel</label>
                        <div class="file-upload-wrapper">
                            <div class="file-upload-message">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Drag & drop file di sini atau klik untuk memilih</p>
                                <small class="text-muted">Format: .xlsx atau .csv</small>
                            </div>
                            <input type="file" class="file-upload @error('file') is-invalid @enderror"
                                name="file" accept=".xlsx,.csv" id="importFile">
                            <div class="file-upload-preview d-none">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-excel me-2 text-success"></i>
                                    <span class="file-name"></span>
                                    <button type="button" class="btn-close ms-auto" id="removeFile"></button>
                                </div>
                            </div>
                        </div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <a href="{{ route('admin.santri.template.download') }}">
                                Download template di sini
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="uploadButton">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
