@extends('layouts.wali')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid p-3 p-md-4">
    <div class="row gy-3">
        
        @if($santri_list->count() > 1)
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
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

        
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body p-3 p-md-4">
                    <h5 class="card-title fw-bold text-primary mb-3">Informasi Santri</h5>
                    <div class="vstack gap-3">
                        
                        <div class="bg-light rounded-3 p-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $santri->nama }}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-secondary">NIS: {{ str_pad($santri->nisn, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="badge bg-info">Jenjang: {{ $santri->jenjang }}</span>
                                        <span class="badge bg-primary">Kelas {{ $santri->kelas }}</span>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Terdaftar: {{ $santri->tanggal_masuk->format('d F Y') }}
                                </small>
                            </div>
                        </div>

                        
                        <div class="card shadow-sm rounded-3 border-0 bg-light p-3">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="vstack gap-1">
                                        <small class="text-muted">Kategori Santri</small>
                                        <div class="fw-bold">{{ $santri->kategori->nama }}</div>
                                        <small class="text-muted">Tarif Bulanan</small>
                                        <div class="fw-bold text-primary">
                                            {{ is_numeric($santri->tarif_bulanan) ? 'Rp ' . number_format($santri->tarif_bulanan, 0, ',', '.') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded-3 {{ $santri->status_spp == 'Lunas' ? 'bg-success bg-opacity-10' : 'bg-warning bg-opacity-10' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <i class="fas fa-chart-pie fa-2x {{ $santri->status_spp == 'Lunas' ? 'text-success' : 'text-warning' }}"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Status SPP</div>
                                <div class="fw-bold fs-5">{{ $santri->status_spp }}</div>
                            </div>
                        </div>
                        @if($santri->status_spp != 'Lunas')
                        <div>
                            <div class="text-muted small">Total Tunggakan</div>
                            <div class="fw-bold fs-5 text-danger">Rp {{ number_format($total_tunggakan ?? 0, 0, ',', '.') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Daftar Tunggakan</h5>
                            @if($pembayaran_terbaru->isNotEmpty())
                            <small class="text-muted">Segera lunasi pembayaran untuk menghindari denda</small>
                            @endif
                        </div>
                        <a href="{{ route('wali.tagihan') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list me-1"></i>Lihat Semua Tagihan
                        </a>
                    </div>
                </div>
                <div class="card-body p-3 p-md-4">
                    @if($pembayaran_terbaru->isNotEmpty())
                    <div class="vstack gap-3">
                        @foreach($pembayaran_terbaru as $pembayaran)
                        <div class="bg-light rounded-3 p-3 border">
                            <div class="row g-3 align-items-center">
                                
                                <div class="col-12 col-sm-4">
                                    <div class="vstack">
                                        <span class="fw-bold text-primary fs-5">{{ $pembayaran->nama_bulan }}</span>
                                        <span class="text-muted">{{ $pembayaran->tahun }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-sm-5">
                                    <div class="vstack">
                                        <span class="fw-bold">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</span>
                                        <span class="text-danger small">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Jatuh tempo: 10 {{ $pembayaran->nama_bulan }} {{ $pembayaran->tahun }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-sm-3">
                                    <button class="btn btn-primary w-100" onclick="bayarSPP({{ $pembayaran->id }})">
                                        <i class="fas fa-money-bill me-1"></i>Bayar
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success fa-3x"></i>
                        </div>
                        <h5 class="text-success mb-2">Tidak Ada Tunggakan</h5>
                        <p class="text-muted small mb-0">Terima kasih atas ketepatan waktu Anda dalam pembayaran SPP.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function bayarSPP(id) {
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
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(response => {
                    Swal.close();
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else if (response.snap_token) {
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
                .catch(err => {
                    console.error('Error:', err);
                    Swal.close();
                    let errorMessage = err.message || 'Gagal memproses pembayaran';
                    if (err.show_profile_modal) {
                        const profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
                        profileModal.show();
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                });
            }
        });
}
</script>
@endpush
@endsection
