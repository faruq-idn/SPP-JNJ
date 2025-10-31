@extends('layouts.admin')

@section('title', 'Data Santri')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- DataTables -->
<link href="{{ asset('vendor/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<!-- Select2 -->
<link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/select2/css/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet">
<style>
    /* DataTable specific styles */
    #dataTable tbody tr td:not(:last-child) {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Santri</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-success" onclick="kenaikanKelas()">
                <i class="fas fa-graduation-cap fa-sm me-1"></i>
                Kenaikan Kelas
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import fa-sm me-1"></i>
                Import Data
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#santriFormModal" data-mode="create">
                <i class="fas fa-plus fa-sm me-1"></i>
                Tambah Santri
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @include('admin.santri.partials._table')
</div>

@endsection

@section('modals')
@include('admin.santri.partials.modal-import')
@include('admin.santri.partials.modal-kenaikan-kelas')
@include('admin.santri._form_modal')
@endsection

@push('scripts')
<!-- DataTables -->
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
function initializeDataTable() {
    if (typeof jQuery === 'undefined' || typeof jQuery.fn.DataTable === 'undefined') {
        setTimeout(initializeDataTable, 100);
        return;
    }

    if (!$.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable({
            language: {
                url: "{{ asset('vendor/datatables/i18n/id.json') }}"
            },
            pageLength: 10,
            lengthMenu: [[3, 10, 25, 50, -1], [3, 10, 25, 50, "Semua"]],
            order: [[1, 'asc']], // Urutkan berdasarkan nama
            columnDefs: [{
                targets: -1, // Kolom terakhir (aksi)
                orderable: false,
                searchable: false
            }],
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            processing: true,
            searching: true,
            info: true
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initializeDataTable);

</script>
<script>
function hapusSantri(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-danger ms-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus data santri ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim request ajax untuk hapus data
            fetch(`{{ url('admin/santri') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    swalWithBootstrapButtons.fire({
                        icon: 'success',
                        title: 'Berhasil Dihapus!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Refresh halaman
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                swalWithBootstrapButtons.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan saat menghapus data'
                });
            });
        }
    });
}
</script>
@endpush
