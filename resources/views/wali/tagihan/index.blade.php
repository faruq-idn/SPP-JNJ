@extends('layouts.wali')

@section('title', 'Tagihan &amp; Riwayat SPP')

@section('content')
<div class="container-fluid p-3 p-md-4">
    <div class="row gy-3">
        <div class="col-12">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <h2 class="mb-3">
                <i class="fas fa-file-invoice-dollar me-2"></i>Tagihan &amp; Riwayat SPP
            </h2>

            @if($santri_list->count() > 1)
            <div class="card shadow-sm rounded-3 border-0 mb-4">
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
            @endif

            <-- Info Santri -->
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body p-3 p-md-4">
                    <h5 class="card-title fw-bold text-primary mb-3">Informasi Santri</h5>
                    <div class="vstack gap-3">
                        <-- Identitas Santri -->
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

                        <-- Info Kategori dan Pembayaran -->
                        <div class="bg-light rounded-3 p-3">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="vstack gap-1">
                                        <small class="text-muted">Kategori Santri</small>
                                        <div class="fw-bold">{{ $santri->kategori->nama }}</div>
                                        <small class="text-muted">Tarif Bulanan</small>
                                        <div class="fw-bold text-primary">
                                            Rp {{ number_format($santri->kategori->tarifTerbaru()->nominal ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="vstack gap-1">
                                        <small class="text-muted">Status Pembayaran</small>
                                        <div>
                                            <span class="badge bg-{{ $santri->status_spp == 'Lunas' ? 'success' : 'warning' }} badge-pill px-3 py-2">
                                                <i class="fas fa-{{ $santri->status_spp == 'Lunas' ? 'check-circle' : 'exclamation-circle' }} me-1"></i>
                                                {{ $santri->status_spp }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <-- Riwayat Pembayaran per Tahun -->
            @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Tahun {{ $tahun }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Bulan</th>
                                <th>Status</th>
                                <th>Nominal</th>
                                <th>Tanggal Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembayaranBulanan as $pembayaran)
                            <tr>
                                <td>{{ $pembayaran->bulan }}</td>
                                <td>
                                    <span class="badge bg-{{ $pembayaran->status == 'success' ? 'success' : ($pembayaran->status == 'pending' ? 'warning' : 'danger') }}">
                                        @if($pembayaran->status == 'success')
                                            Lunas
                                        @elseif($pembayaran->status == 'pending')
                                            Pending
                                        @else
                                            Belum Lunas
                                        @endif
                                    </span>
                                </td>
                                <td>Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                                <td>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    @if($pembayaran->status == 'success')
                                        <button class="btn btn-success btn-sm" disabled>
                                            <i class="fas fa-check-circle me-1"></i>Lunas
                                        </button>
                                    @elseif($pembayaran->status == 'unpaid')
                                    <button class="btn btn-primary btn-sm" onclick="bayarSPP({{ $pembayaran->id }})">
                                        <i class="fas fa-money-bill me-1"></i>Bayar Online
                                    </button>
                                    @elseif($pembayaran->status == 'pending')
                                    <button class="btn btn-warning btn-sm" onclick="bayarSPP({{ $pembayaran->id }})">
                                        <i class="fas fa-clock me-1"></i>Lanjutkan Pembayaran
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.bayarSPP = function(id) {
        // Cek nomor HP wali terlebih dahulu
        @if(!auth()->user()->no_hp)
            Swal.fire({
                title: 'Nomor HP Belum Terdaftar',
                text: 'Anda harus menambahkan nomor HP terlebih dahulu untuk melakukan pembayaran online',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Tambahkan Sekarang',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan modal profil
                    const profileModal = document.getElementById('profileModal');
                    const modal = new bootstrap.Modal(profileModal);
                    modal.show();
                }
            });
            return;
        @endif

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

                    if (response.status === 400) {
                        Swal.close();
                        if (data.redirect_url) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                text: data.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = data.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                text: data.message,
                                confirmButtonText: 'OK'
                            });
                        }
                        return;
                    }

                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }

                    Swal.close();
                    if (data.snap_token) {
                        snap.pay(data.snap_token, {
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
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Gagal terhubung ke server. Silakan coba lagi.'
                    });
                });
            }
        });
    };
</script>
@endpush
@endsection
