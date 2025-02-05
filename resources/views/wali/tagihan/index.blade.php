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
                <i class="fas fa-file-invoice-dollar me-2"></i>Tagihan & Riwayat SPP
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

            <!-- Ringkasan Pembayaran -->
            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-body p-3 p-md-4">
                    <h5 class="card-title fw-bold text-primary mb-3">Ringkasan Pembayaran</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded">
                                        <i class="fas fa-user-graduate text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Santri</small>
                                        <span class="fw-bold">{{ $santri->nama }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-info bg-opacity-10 p-2 rounded">
                                        <i class="fas fa-tags text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kategori</small>
                                        <span class="fw-bold">{{ $santri->kategori->nama }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success bg-opacity-10 p-2 rounded">
                                        <i class="fas fa-money-bill text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tarif Bulanan</small>
                                        <span class="fw-bold">Rp {{ number_format($tarif->nominal ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status SPP -->
            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded-3 {{ $statusSpp == 'Lunas' ? 'bg-success bg-opacity-10' : 'bg-warning bg-opacity-10' }}" style="border: 1px solid {{ $statusSpp == 'Lunas' ? '#19875414' : '#ffc10714' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <i class="fas fa-chart-pie fa-2x {{ $statusSpp == 'Lunas' ? 'text-success' : 'text-warning' }}"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Status SPP</div>
                                <div class="fw-bold fs-5">{{ $statusSpp }}</div>
                            </div>
                        </div>
                        @if($statusSpp != 'Lunas')
                        <div>
                            <div class="text-muted small">Total Tunggakan</div>
                            <div class="fw-bold fs-5 text-danger">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Riwayat Pembayaran per Tahun -->
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
