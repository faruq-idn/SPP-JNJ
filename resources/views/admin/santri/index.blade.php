@extends('layouts.admin')

@section('title', 'Data Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title ?? 'Data Santri' }}</h1>
        <div class="d-flex gap-2">
            <div class="btn-group">
                <button class="btn btn-success" onclick="kenaikanKelas()">
                    <i class="fas fa-graduation-cap me-1"></i>Kenaikan Kelas
                </button>
                <button class="btn btn-danger" onclick="batalKenaikanKelas()">
                    <i class="fas fa-undo me-1"></i>Batalkan Kenaikan
                </button>
            </div>
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

<!-- Modal Konfirmasi Kenaikan Kelas -->
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
                    <strong>Perhatian!</strong> Tindakan ini akan:
                    <ul class="mb-0">
                        <li>Menaikkan kelas semua santri (7A→8A, 7B→8B, dst)</li>
                        <li>Menonaktifkan santri kelas 9 SMP dan 12 SMA</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="prosesKenaikanKelas()">
                    <i class="fas fa-graduation-cap me-1"></i>Proses Kenaikan Kelas
                </button>
            </div>
        </div>
    </div>
</div>

@include('admin.santri.partials.modal-import')

@push('scripts')
<script>
$(document).ready(function() {
    const table = $('#dataTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        oLanguage: {
            sEmptyTable: "Tidak ada data yang tersedia",
            sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
            sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
            sLengthMenu: "Tampilkan _MENU_ entri",
            sLoadingRecords: "Sedang memuat...",
            sProcessing: "Sedang memproses...",
            sSearch: "Cari:",
            sZeroRecords: "Tidak ditemukan data yang sesuai",
            oPaginate: {
                sFirst: "Pertama",
                sLast: "Terakhir",
                sNext: "Selanjutnya",
                sPrevious: "Sebelumnya"
            }
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
});

function kenaikanKelas() {
    $('#modalKenaikanKelas').modal('show');
}

function prosesKenaikanKelas() {
    Swal.fire({
        title: 'Yakin Proses Kenaikan Kelas?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Proses!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.santri.kenaikan-kelas") }}', {
                _token: '{{ csrf_token() }}'
            })
            .done(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(() => window.location.reload());
            })
            .fail(xhr => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses kenaikan kelas'
                });
            });
        }
    });
}

function batalKenaikanKelas() {
    Swal.fire({
        title: 'Batalkan Kenaikan Kelas?',
        text: "Tindakan ini akan mengembalikan semua santri ke kelas sebelumnya.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.santri.batal-kenaikan-kelas") }}', {
                _token: '{{ csrf_token() }}'
            })
            .done(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(() => window.location.reload());
            })
            .fail(xhr => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat membatalkan kenaikan kelas'
                });
            });
        }
    });
}
</script>
@endpush
@endsection
