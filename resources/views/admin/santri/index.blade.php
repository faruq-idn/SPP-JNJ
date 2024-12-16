@extends('layouts.admin')

@section('title', 'Data Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title ?? 'Data Santri' }}</h1>
        <div>
            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-excel"></i> Import Excel
            </button>
            <a href="{{ route('admin.santri.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Santri
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-md-6 mx-auto">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Cari nama atau NISN santri...">
                    </div>
                </div>
            </div>

            <!-- Tabel Santri -->
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead class="bg-light">
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($santri as $s)
                            <tr>
                                <td>{{ $s->nisn }}</td>
                                <td>{{ $s->nama }}</td>
                                <td>{{ $s->jenjang }} {{ $s->kelas }}</td>
                                <td>{{ $s->kategori->nama }}</td>
                                <td>
                                    <span class="badge bg-{{ $s->status === 'aktif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.santri.show', $s) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.santri.edit', $s) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.santri.destroy', $s) }}"
                                                method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data santri</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
                            <a href="{{ route('admin.santri.template') }}">
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

@push('scripts')
<script>
$(document).ready(function() {
    const table = $('#dataTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
        },
        processing: true,
        order: [[1, 'asc']],
        pageLength: 10,
        responsive: true
    });

    // Custom search box
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Hide default search box
    $('.dataTables_filter').hide();

    // Handler untuk konfirmasi hapus
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;

        // Konfirmasi pertama
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus data santri ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Konfirmasi kedua
                Swal.fire({
                    title: 'Konfirmasi Terakhir',
                    text: "Menghapus data santri akan menghapus semua data terkait dan tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus Sekarang!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });

    // Handle form submission
    $('#importForm').on('submit', function(e) {
        e.preventDefault();

        // Validasi file
        const fileInput = $('#importFile')[0];
        if (!fileInput.files || fileInput.files.length === 0) {
            $('#alertContainer').html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Pilih file Excel/CSV terlebih dahulu
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            return;
        }

        // Validasi ukuran file (max 5MB)
        const maxSize = 5 * 1024 * 1024; // 5MB dalam bytes
        if (fileInput.files[0].size > maxSize) {
            $('#alertContainer').html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Ukuran file terlalu besar. Maksimal 5MB
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            return;
        }

        // Create FormData
        const formData = new FormData(this);

        // Disable form elements
        $('#uploadButton').prop('disabled', true);
        $('#importFile').prop('disabled', true);
        $('#removeFile').prop('disabled', true);

        // Send AJAX request
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Hide loading spinner
                $('#loadingSpinner').addClass('d-none');
                $('#uploadStatus').text('Upload selesai!');
                $('#uploadProgress').removeClass('progress-bar-animated');

                // Show success message
                $('#alertContainer').html(`
                    <div class="alert alert-${response.status} alert-dismissible fade show" role="alert">
                        ${response.message || 'Data berhasil diimport'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                // Reload table after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
                // Hide loading spinner
                $('#loadingSpinner').addClass('d-none');
                $('#uploadStatus').text('Upload gagal!');
                $('#uploadProgress')
                    .removeClass('progress-bar-animated bg-primary')
                    .addClass('bg-danger');

                // Enable form elements
                $('#uploadButton').prop('disabled', false);
                $('#importFile').prop('disabled', false);
                $('#removeFile').prop('disabled', false);

                // Show error message
                const errors = xhr.responseJSON;
                let errorMessage = 'Terjadi kesalahan saat mengimport data';

                if (errors && errors.message) {
                    errorMessage = errors.message;
                    if (errors.detail) {
                        errorMessage += '<br><br><strong>Detail error:</strong><br>' + errors.detail;
                    }
                } else if (errors && errors.errors) {
                    errorMessage = Object.values(errors.errors).flat().join('<br>');
                }

                // Tampilkan pesan error yang lebih detail
                if (xhr.status === 422) {
                    errorMessage = 'Validasi gagal: ' + errorMessage;
                } else if (xhr.status === 413) {
                    errorMessage = 'File terlalu besar untuk diupload';
                } else if (xhr.status === 0) {
                    errorMessage = 'Koneksi terputus. Periksa koneksi internet Anda';
                }

                $('#alertContainer').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div>${errorMessage}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            }
        });
    });

    // Reset form when modal is closed
    $('#importModal').on('hidden.bs.modal', function() {
        $('#importForm')[0].reset();
        $('#alertContainer').empty();
        $('#loadingSpinner').addClass('d-none');
        $('#uploadButton').prop('disabled', false);
        $('#importFile').prop('disabled', false);
        // Reset progress bar
        $('#uploadProgress')
            .css('width', '0%')
            .text('0%')
            .addClass('progress-bar-animated bg-primary')
            .removeClass('bg-danger');
        $('#uploadStatus').text('Mempersiapkan upload...');
    });

    // File Upload Handling
    let fileUpload = $('#importFile');
    const wrapper = $('.file-upload-wrapper');
    const preview = $('.file-upload-preview');
    const message = $('.file-upload-message');

    // Drag & Drop events
    wrapper.on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });

    wrapper.on('dragleave dragend drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });

    // File selection handling
    $(document).on('change', '#importFile', function(e) {
        const file = this.files[0];
        if (file) {
            message.hide();
            preview.removeClass('d-none');
            preview.find('.file-name').text(file.name);
        }
    });

    // Remove file
    $('#removeFile').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        message.show();
        preview.addClass('d-none');
        // Reset input file
        $('#importFile').val('');
    });
});
</script>
@endpush

<style>
    .file-upload-wrapper {
        position: relative;
        width: 100%;
        height: 150px;
        border: 2px dashed #ddd;
        border-radius: 8px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .file-upload-wrapper:hover,
    .file-upload-wrapper.dragover {
        background: #fff;
        border-color: #0d6efd;
    }

    .file-upload-message {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 15px;
        text-align: center;
    }

    .file-upload-message i {
        font-size: 2.5rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .file-upload {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 1;
    }

    .file-upload-preview {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #fff;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .file-upload-preview i {
        font-size: 1.5rem;
    }
</style>
@endsection
