@extends('layouts.admin')

@section('title', 'Data Santri')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- DataTables -->
<link href="{{ asset('vendor/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
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
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Santri</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead>
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
                        @foreach($santri as $s)
                        <tr>
                            <td onclick="window.location='{{ route('petugas.santri.show', $s->id) }}'">{{ $s->nisn }}</td>
                            <td onclick="window.location='{{ route('petugas.santri.show', $s->id) }}'">{{ $s->nama }}</td>
                            <td onclick="window.location='{{ route('petugas.santri.show', $s->id) }}'">{{ $s->jenjang }} {{ $s->kelas }}</td>
                            <td onclick="window.location='{{ route('petugas.santri.show', $s->id) }}'">{{ $s->kategori->nama ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $s->status_color }}">
                                    {{ ucfirst($s->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('petugas.santri.show', $s->id) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
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
@endpush