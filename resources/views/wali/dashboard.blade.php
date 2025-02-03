@extends('layouts.wali')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Pilih Santri (jika memiliki lebih dari 1 santri) -->
        @if($santri_list->count() > 1)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('wali.change-santri') }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        <label class="me-2 fw-bold">Pilih Santri:</label>
                        <select name="santri_id" class="form-select me-2" onchange="this.form.submit()">
                            @foreach($santri_list as $s)
                            <option value="{{ $s->id }}" {{ $santri->id == $s->id ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ str_pad($s->nisn, 5, '0', STR_PAD_LEFT) }})
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Info Santri -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informasi Santri</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Nama:</strong> {{ $santri->nama }}</p>
                            <p class="mb-1"><strong>NIS:</strong> {{ str_pad($santri->nisn, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-0"><strong>Kelas:</strong> {{ $santri->kelas }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1">
                                <strong>Status SPP:</strong>
                                <span class="badge bg-{{ $santri->status_spp == 'Lunas' ? 'success' : 'warning' }}">
                                    {{ $santri->status_spp }}
                                </span>
                            </p>
                            <p class="mb-1"><strong>Kategori:</strong> {{ $santri->kategori->nama }}</p>
                            <p class="mb-0"><strong>Tarif SPP:</strong> Rp {{ number_format($santri->kategori->tarifTerbaru->nominal ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran Terbaru -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Pembayaran Terbaru</h5>
                        <a href="{{ route('wali.tagihan') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list me-1"></i>Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($pembayaran_terbaru->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Bulan/Tahun</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembayaran_terbaru as $pembayaran)
                                    <tr>
                                        <td>{{ $pembayaran->nama_bulan }} {{ $pembayaran->tahun }}</td>
                                        <td>Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $pembayaran->status == 'success' ? 'success' : ($pembayaran->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($pembayaran->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @if($pembayaran->status == 'unpaid')
                                            <button class="btn btn-primary btn-sm" onclick="bayarSPP({{ $pembayaran->id }})">
                                                <i class="fas fa-money-bill me-1"></i>Bayar
                                            </button>
                                            @elseif($pembayaran->status == 'pending')
                                            <button class="btn btn-warning btn-sm" onclick="bayarSPP({{ $pembayaran->id }})">
                                                <i class="fas fa-clock me-1"></i>Lanjutkan
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-receipt fa-3x mb-3"></i>
                            <p class="mb-0">Belum ada data pembayaran</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.bayarSPP = function(id) {
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: 'Anda akan melanjutkan ke halaman pembayaran online?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Pembayaran',
                    text: 'Mohon tunggu...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('{{ route("wali.pembayaran.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tagihan_id: id
                    })
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                    return data;
                })
                .then(response => {
                    Swal.close();
                    if(response.snap_token) {
                        snap.pay(response.snap_token, {
                            onSuccess: function(result) {
                                Swal.fire({
                                    title: 'Pembayaran Berhasil',
                                    text: 'Halaman akan diperbarui',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            onPending: function(result) {
                                Swal.fire({
                                    title: 'Pembayaran Pending',
                                    text: 'Silakan selesaikan pembayaran Anda',
                                    icon: 'info'
                                });
                            },
                            onError: function(result) {
                                console.error('Payment Error:', result);
                                let errorMessage = 'Pembayaran gagal';
                                if (result.status_message) {
                                    errorMessage += ': ' + result.status_message;
                                }
                                Swal.fire('Error', errorMessage, 'error');
                            },
                            onClose: function() {
                                Swal.fire('Info', 'Pembayaran dibatalkan', 'info');
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', error.message, 'error');
                });
            }
        });
    }
});
</script>
@endpush
@endsection
