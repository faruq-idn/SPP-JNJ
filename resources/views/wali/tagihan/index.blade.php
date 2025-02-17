@extends('layouts.wali')

@section('title', 'Tagihan & Riwayat SPP')

@section('content')
<div class="container-fluid p-2 p-md-4 mb-5">
    <div class="row g-2 g-md-3">
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
            <div class="card shadow-sm rounded-3 border-0 mb-3 mb-md-4">
                <div class="card-body p-2 p-md-3">
                    <form action="{{ route('wali.change-santri') }}" method="POST">
                        @csrf
                        <div class="vstack gap-2">
                            <label class="fw-bold fs-7 fs-md-6">Pilih Santri:</label>
                            <select name="santri_id" class="form-select fs-7 fs-md-6" onchange="this.form.submit()">
                                @foreach($santri_list as $s)
                                <option value="{{ $s->id }}" {{ $santri->id == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }} ({{ str_pad($s->nisn, 5, '0', STR_PAD_LEFT) }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Riwayat Pembayaran per Tahun -->
            @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Tahun {{ $tahun }}</h5>
                </div>
                <!-- Mobile View -->
                <div class="d-block d-md-none">
                    <div class="vstack gap-2">
                        @foreach($pembayaranBulanan as $pembayaran)
                        <div class="card border-0 bg-light shadow-sm"
                             style="cursor: pointer"
                             onclick="showDetailPembayaran({{ $pembayaran->id }}, '{{ $pembayaran->nama_bulan }}', {{ $pembayaran->nominal }}, '{{ $pembayaran->status }}', '{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-' }}', '{{ $pembayaran->metode_pembayaran ? $pembayaran->metode_pembayaran->nama : '-' }}', '{{ $pembayaran->tahun }}')">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="vstack gap-1">
                                        <span class="fw-bold fs-6">{{ $pembayaran->nama_bulan }}</span>
                                        <span class="badge bg-{{ $pembayaran->status == 'success' ? 'success' : ($pembayaran->status == 'pending' ? 'warning' : 'danger') }} fs-7">
                                            @if($pembayaran->status == 'success')
                                                Lunas
                                            @elseif($pembayaran->status == 'pending')
                                                Pending
                                            @else
                                                Belum Lunas
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-primary fs-7">
                                            Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}
                                        </div>
                                        @if($pembayaran->tanggal_bayar)
                                        <small class="text-muted fs-7">
                                            {{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Desktop View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Bulan</th>
                                <th>Status</th>
                                <th>Nominal</th>
                                <th>Tanggal Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembayaranBulanan as $pembayaran)
                            <tr style="cursor: pointer" onclick="showDetailPembayaran({{ $pembayaran->id }}, '{{ $pembayaran->nama_bulan }}', {{ $pembayaran->nominal }}, '{{ $pembayaran->status }}', '{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-' }}', '{{ $pembayaran->metode_pembayaran ? $pembayaran->metode_pembayaran->nama : '-' }}', '{{ $pembayaran->tahun }}')">
                                <td>{{ $pembayaran->nama_bulan }}</td>
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

<!-- Modal Detail Pembayaran -->
<div class="modal fade" id="modalDetailPembayaran" tabindex="-1" inert>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pembayaran SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2 p-md-3">
                <div class="mb-3 mb-md-4">
                    <h6 class="fs-7 fs-md-6 mb-2 mb-md-3 fw-bold text-primary">Informasi Tagihan</h6>
                    <div class="vstack gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Periode</span>
                            <div class="text-end">
                                <span id="detail-bulan" class="fw-bold fs-7 fs-md-6"></span>
                                <span id="detail-tahun" class="fs-7 fs-md-6"></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Status</span>
                            <span id="detail-status"></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Nominal</span>
                            <div class="fw-bold text-primary fs-7 fs-md-6">
                                Rp <span id="detail-nominal"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 mb-md-4" id="detail-pembayaran-info">
                    <h6 class="fs-7 fs-md-6 mb-2 mb-md-3 fw-bold text-success">Informasi Pembayaran</h6>
                    <div class="vstack gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Tanggal</span>
                            <span id="detail-tanggal" class="fs-7 fs-md-6"></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Metode</span>
                            <span id="detail-metode" class="badge bg-info fs-7 fs-md-6"></span>
                        </div>
                    </div>
                </div>
                
                @php
                    $metode_manual = App\Models\MetodePembayaran::where('kode', 'like', 'MANUAL_%')->get();
                    $metode_online = App\Models\MetodePembayaran::where('kode', 'MIDTRANS')->first();
                @endphp
                <div id="pembayaran-options" class="mt-3">
                    <h6 class="fs-7 fs-md-6 mb-2 mb-md-3 fw-bold">Pilih Metode Pembayaran:</h6>
                    <div class="vstack gap-2">
                        @foreach($metode_manual as $metode)
                            <button class="btn {{ $metode->kode == 'MANUAL_TUNAI' ? 'btn-outline-primary' : 'btn-outline-info' }} fs-7 fs-md-6 py-2"
                                    onclick="bayarManual('{{ $metode->kode }}', '{{ $metode->nama }}')">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas {{ $metode->kode == 'MANUAL_TUNAI' ? 'fa-money-bill' : 'fa-exchange-alt' }}"></i>
                                    <span>{{ $metode->nama }}</span>
                                </div>
                            </button>
                        @endforeach
                        @if($metode_online)
                            <button class="btn btn-primary fs-7 fs-md-6 py-2" onclick="bayarOnline()">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-globe"></i>
                                    <span>{{ $metode_online->nama }}</span>
                                </div>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedPembayaranId = null;
    
    function showDetailPembayaran(id, bulan, nominal, status, tanggal, metode, tahun) {
        // Cek nomor HP dulu
        @if(!auth()->user()->no_hp)
            Swal.fire({
                title: 'Nomor HP Belum Terdaftar',
                text: 'Anda harus menambahkan nomor HP terlebih dahulu',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Tambahkan Sekarang',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    const profileModal = document.getElementById('profileModal');
                    const modal = new bootstrap.Modal(profileModal);
                    modal.show();
                }
            });
            return;
        @endif

        selectedPembayaranId = id;
        
        // Update konten modal
        document.getElementById('detail-bulan').textContent = bulan;
        document.getElementById('detail-nominal').textContent = nominal.toLocaleString('id-ID');
        document.getElementById('detail-tahun').textContent = tahun;
        
        // Update status dengan badge
        const statusBadge = document.createElement('span');
        if (status === 'success') {
            statusBadge.className = 'badge bg-success';
            statusBadge.textContent = 'Lunas';
            document.getElementById('pembayaran-options').style.display = 'none';
        } else if (status === 'pending') {
            statusBadge.className = 'badge bg-warning';
            statusBadge.textContent = 'Pending';
            document.getElementById('pembayaran-options').style.display = 'block';
        } else {
            statusBadge.className = 'badge bg-danger';
            statusBadge.textContent = 'Belum Lunas';
            document.getElementById('pembayaran-options').style.display = 'block';
        }
        document.getElementById('detail-status').innerHTML = '';
        document.getElementById('detail-status').appendChild(statusBadge);
        
        // Info pembayaran hanya ditampilkan jika sudah lunas
        const infoPembayaran = document.getElementById('detail-pembayaran-info');
        if (status === 'success') {
            infoPembayaran.style.display = 'block';
            document.getElementById('detail-tanggal').textContent = tanggal;
            document.getElementById('detail-metode').textContent = metode;
        } else {
            infoPembayaran.style.display = 'none';
        }
        
        // Tampilkan modal
        const modalElement = document.getElementById('modalDetailPembayaran');
        const modal = new bootstrap.Modal(modalElement);
        
        // Tangani atribut inert
        modalElement.addEventListener('shown.bs.modal', function () {
            modalElement.removeAttribute('inert');
        });
        
        modalElement.addEventListener('hidden.bs.modal', function () {
            modalElement.setAttribute('inert', '');
        });
        
        modal.show();
    }
    
    function bayarManual(kode, nama) {
        let pesan = '';
        if (kode === 'MANUAL_TUNAI') {
            pesan = 'Silakan lakukan pembayaran langsung ke bagian administrasi pondok.';
        } else if (kode === 'MANUAL_TRANSFER') {
            pesan = `Silakan transfer ke rekening berikut:<br><br>
                <b>Bank BRI</b><br>
                No. Rek: 1234-5678-9012-3456<br>
                A.n: Yayasan Pondok<br><br>
                Setelah transfer, harap konfirmasi dengan mengirimkan bukti transfer ke administrasi.`;
        }

        Swal.fire({
            title: `Pembayaran ${nama}`,
            html: pesan,
            icon: 'info',
            confirmButtonText: 'Mengerti'
        });
    }
    
    function bayarOnline() {
        if (selectedPembayaranId) {
            bayarSPP(selectedPembayaranId);
        }
    }
</script>

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
