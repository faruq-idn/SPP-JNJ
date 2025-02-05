@extends('layouts.admin')

@section('title', $title ?? 'Data Pembayaran SPP')

@push('styles')
<style>
/* Perbaikan tampilan tab */
.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 0.75rem 1rem;
}
.nav-tabs .nav-link.active {
    color: #435ebe;
    border-bottom: 2px solid #435ebe;
    background: transparent;
}
.tab-content { padding-top: 1rem; }

/* Perbaikan tampilan pagination */
.pagination {
    margin-bottom: 0;
}
.page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
    margin: 0 2px;
}
.page-item.active .page-link {
    background-color: #435ebe;
    border-color: #435ebe;
}
.page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #435ebe;
}
.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <div class="d-flex gap-2">
            <div class="btn-group">
                <button class="btn btn-success" type="button" onclick="generateTagihan()">
                    <i class="fas fa-sync me-1"></i>Generate Tagihan
                </button>
                <button class="btn btn-danger" type="button" onclick="hapusTagihan()">
                    <i class="fas fa-trash me-1"></i>Hapus Tagihan
                </button>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPembayaran">
                <i class="fas fa-plus me-1"></i>Input Pembayaran
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#belum-lunas">
                        <i class="fas fa-clock me-1"></i>Belum Lunas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#lunas">
                        <i class="fas fa-check-circle me-1"></i>Lunas
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Tab Belum Lunas -->
                <div class="tab-pane fade show active" id="belum-lunas">
                    @include('admin.pembayaran.partials.tabel-belum-lunas')
                </div>

                <!-- Tab Lunas -->
                <div class="tab-pane fade" id="lunas">
                    @include('admin.pembayaran.partials.tabel-lunas')
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.pembayaran.partials.modal-generate')
@include('admin.pembayaran.partials.modal-detail')
@include('admin.pembayaran.partials.modal-input')
@include('admin.pembayaran.partials.modal-hapus')

@push('scripts')
<script>
function generateTagihan() {
    $('#modalGenerateTagihan').modal('show');
}

function prosesGenerateTagihan(force = false) {
    const bulan = $('#bulanGenerate').val();
    const tahun = $('#tahunGenerate').val();
    const period = `${tahun}-${bulan}`;
    const namaBulan = $('#bulanGenerate option:selected').text();

    // Tampilkan loading
    Swal.fire({
        title: 'Memproses...',
        html: 'Sedang generate tagihan',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    // Proses generate
    $.post('{{ route('admin.pembayaran.generate-tagihan') }}', {
        _token: '{{ csrf_token() }}',
        period: period,
        force: force
    })
    .done(response => {
        if (response.status === 'warning' && response.needsConfirmation) {
            Swal.fire({
                title: 'Tagihan Sudah Ada',
                text: response.message + '\n\nApakah Anda ingin melanjutkan generate tagihan hanya untuk santri yang belum memiliki tagihan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    prosesGenerateTagihan(true);
                }
            });
        } else {
            Swal.fire({
                icon: response.status,
                title: response.status === 'success' ? 'Berhasil' : 'Peringatan',
                text: response.message,
            }).then(() => {
                if (response.status === 'success') {
                    window.location.reload();
                }
            });
        }
    })
    .fail(xhr => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat generate tagihan'
        });
    });
}

function hapusTagihan() {
    $('#modalHapusTagihan').modal('show');
}

function prosesHapusTagihan() {
    const bulan = $('#bulanHapus').val();
    const tahun = $('#tahunHapus').val();
    const period = `${tahun}-${bulan}`;
    const namaBulan = $('#bulanHapus option:selected').text();

    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Anda yakin ingin menghapus semua tagihan untuk bulan ${namaBulan} ${tahun}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("admin.pembayaran.hapus-tagihan") }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    period: period
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang menghapus tagihan',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                    }).then(() => window.location.reload());
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus tagihan'
                    });
                }
            });
        }
    });
}
</script>
@endpush
@endsection
