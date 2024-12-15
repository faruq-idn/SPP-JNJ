@extends('layouts.admin')

@section('title', 'Data Santri')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    /* Select2 custom styles */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
    }
    .select2-container--bootstrap-5 .select2-selection--single {
        padding: 0.375rem 0.75rem;
    }
    .select2-container--bootstrap-5 .select2-search__field:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    /* Efek hover pada baris tabel */
    .table tbody tr:hover {
        background-color: rgba(0,0,0,.075);
        cursor: pointer;
    }

    /* Efek hover pada link */
    .table tbody tr td a:hover {
        color: #0056b3 !important;
        text-decoration: underline !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Santri</h1>
        <a href="{{ route('admin.santri.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Santri
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Pencarian</h6>
        </div>
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Cari Santri</label>
                    <select class="form-control" id="searchSantri" style="width: 100%"></select>
                    <div class="form-text">Cari berdasarkan nama atau NISN santri</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenjang</label>
                    <select class="form-select" name="jenjang">
                        <option value="">Semua</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select class="form-select" name="kelas">
                        <option value="">Semua</option>
                        @foreach(['7A','7B','8A','8B','9A','9B','10A','10B','11A','11B','12A','12B'] as $kelas)
                            <option value="{{ $kelas }}">{{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="kategori_id">
                        <option value="">Semua</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua</option>
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-aktif</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Wali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($santri as $s)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.santri.show', $s) }}"
                                       class="text-decoration-none text-dark">
                                        {{ $s->nisn }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.santri.show', $s) }}"
                                       class="text-decoration-none text-dark">
                                        {{ $s->nama }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.santri.show', $s) }}"
                                       class="text-decoration-none text-dark">
                                        {{ $s->kelas }}
                                    </a>
                                </td>
                                <td>{{ $s->wali->name }}</td>
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
                                        <a href="{{ route('admin.santri.edit', $s) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.santri.destroy', $s) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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

            <div class="mt-4">
                {{ $santri->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('#searchSantri').select2({
        theme: 'bootstrap-5',
        placeholder: 'Ketik nama atau NISN santri...',
        allowClear: true,
        minimumInputLength: 2,
        width: '100%',
        language: {
            inputTooShort: function() {
                return 'Ketik minimal 2 karakter';
            },
            noResults: function() {
                return 'Data tidak ditemukan';
            },
            searching: function() {
                return 'Mencari...';
            }
        },
        ajax: {
            url: '{{ route("admin.santri.search") }}',
            dataType: 'json',
            delay: 500,
            data: function(params) {
                return {
                    q: params.term || '',
                    jenjang: $('select[name=jenjang]').val(),
                    kelas: $('select[name=kelas]').val(),
                    kategori_id: $('select[name=kategori_id]').val(),
                    status: $('select[name=status]').val(),
                    page: params.page || 1
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: data.length === 10
                    }
                };
            },
            cache: true
        },
        templateResult: formatSantri,
        templateSelection: formatSantriSelection,
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // Auto focus search input when dropdown opens
    $('#searchSantri').on('select2:open', function() {
        setTimeout(function() {
            $('.select2-search__field').focus();
        }, 100);
    });

    // Handle selection
    $('#searchSantri').on('select2:select', function(e) {
        if (e.params.data.url) {
            window.location.href = e.params.data.url;
        }
    });

    // Clear selection when filters change
    $('select[name]').change(function() {
        $('#searchSantri').val(null).trigger('change');
        $('#filterForm').submit();
    });

    // Format hasil pencarian
    function formatSantri(santri) {
        if (!santri.id) return santri.text;
        return `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold">${santri.nama}</div>
                    <div class="text-muted small">
                        <span class="me-2">NISN: ${santri.nisn}</span>
                        <span>Wali: ${santri.wali}</span>
                    </div>
                </div>
                <div class="text-end">
                    <div>${santri.kelas}</div>
                    <div class="small">
                        <span class="badge bg-${santri.status === 'Aktif' ? 'success' : 'secondary'}">
                            ${santri.status}
                        </span>
                    </div>
                </div>
            </div>
        `;
    }

    function formatSantriSelection(santri) {
        return santri.id ? santri.nama + ' - ' + santri.nisn : santri.text;
    }

    // Klik pada baris tabel
    $('.table tbody tr').click(function(e) {
        // Jika yang diklik bukan tombol atau form
        if (!$(e.target).closest('button, .btn-group, form').length) {
            // Ambil URL dari link detail di baris tersebut
            var url = $(this).find('td:first-child a').attr('href');
            if (url) {
                window.location.href = url;
            }
        }
    });
});
</script>
@endpush
