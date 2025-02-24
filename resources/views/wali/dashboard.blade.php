@extends('layouts.wali')

@section('title', 'Dashboard')

@section('content')
@include('layouts.partials.dropdown-santri')

<div class="container-fluid p-2 p-md-4 mb-5 pb-5">
    <div class="row g-2 g-md-3">
        @if($santri)
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body p-3 p-md-4">
                    <h5 class="card-title fw-bold text-primary mb-3">Informasi Santri</h5>
                    <div class="vstack gap-3">
                        
                        <!-- Informasi Santri -->
                        <div class="card shadow-sm rounded-3 border-0">
                            <div class="card-body p-2 p-md-3">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                    <div class="d-flex flex-column gap-1">
                                        <h5 class="fw-bold mb-0 fs-4">{{ $santri->nama }}</h5>
                                        <div class="text-muted fs-6">NIS: {{ str_pad($santri->nisn, 5, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <span class="badge bg-{{ $santri->status_color }} px-3 py-2 fs-6">{{ ucfirst($santri->status) }}</span>
                                    </div>
                                </div>

                                <div class="row g-2 g-md-3">
                                    <div class="col-12 col-md-6">
                                        <div class="vstack gap-2 gap-md-3">
                                            <div>
                                                <div class="text-muted small mb-1">Jenjang & Kelas</div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <span class="badge bg-info fs-7 fs-md-6">{{ $santri->jenjang }}</span>
                                                    <span class="badge bg-primary fs-7 fs-md-6">Kelas {{ $santri->kelas }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-muted small mb-1">Tanggal Masuk</div>
                                                <div class="fw-semibold fs-7 fs-md-6">{{ $santri->tanggal_masuk->format('d F Y') }}</div>
                                            </div>
                                            <div>
                                                <div class="text-muted small mb-1">Jenis Kelamin</div>
                                                <div class="fw-semibold fs-7 fs-md-6">{{ $santri->jenis_kelamin }}</div>
                                            </div>
                                            <div>
                                                <div class="text-muted small mb-1">Alamat</div>
                                                <div class="fw-semibold fs-7 fs-md-6">{{ $santri->alamat ?: '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="vstack gap-2 gap-md-3">
                                            <div>
                                                <div class="text-muted small mb-1">Kategori Santri</div>
                                                <div class="fw-semibold fs-7 fs-md-6">{{ $santri->kategori->nama }}</div>
                                            </div>
                                            <div>
                                                <div class="text-muted small mb-1">Tarif SPP Bulanan</div>
                                                <div class="vstack gap-1">
                                                    <div class="fw-bold text-primary fs-6">
                                                        @if($santri->kategori->tarifTerbaru)
                                                            Rp {{ number_format($santri->kategori->tarifTerbaru->nominal, 0, ',', '.') }}
                                                        @else
                                                            <span class="text-muted">Belum diatur</span>
                                                        @endif
                                                    </div>
                                                    @if($santri->kategori->tarifTerbaru)
                                                    <table class="table table-sm small mb-0 table-borderless">
                                                        <tr class="text-muted">
                                                            <td style="width: 100px">Makan</td>
                                                            <td style="width: 20px">:</td>
                                                            <td>Rp {{ number_format($santri->kategori->tarifTerbaru->biaya_makan, 0, ',', '.') }}</td>
                                                        </tr>
                                                        <tr class="text-muted">
                                                            <td>Asrama</td>
                                                            <td>:</td>
                                                            <td>Rp {{ number_format($santri->kategori->tarifTerbaru->biaya_asrama, 0, ',', '.') }}</td>
                                                        </tr>
                                                        <tr class="text-muted">
                                                            <td>Listrik</td>
                                                            <td>:</td>
                                                            <td>Rp {{ number_format($santri->kategori->tarifTerbaru->biaya_listrik, 0, ',', '.') }}</td>
                                                        </tr>
                                                        <tr class="text-muted">
                                                            <td>Kesehatan</td>
                                                            <td>:</td>
                                                            <td>Rp {{ number_format($santri->kategori->tarifTerbaru->biaya_kesehatan, 0, ',', '.') }}</td>
                                                        </tr>
                                                    </table>
                                                    @endif
                                                </div>
                                            </div>
                                            
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
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Status SPP</h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    {{-- Status SPP per tahun --}}
                    @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
                        @php
                            $totalBulan = count($pembayaranBulanan);
                            $lunasBulan = $pembayaranBulanan->where('status', 'success')->count();
                            $isLunas = $lunasBulan === $totalBulan;
                            $presentase = ($lunasBulan / $totalBulan) * 100;
                            $statusClass = $isLunas ? 'success' : ($presentase > 50 ? 'warning' : 'danger');
                        @endphp
                        <div class="p-3 rounded-3 bg-{{ $statusClass }} bg-opacity-10 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-1">Status SPP {{ $tahun }}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ $lunasBulan }}/{{ $totalBulan }} Bulan
                                        </span>
                                        @if($isLunas)
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted mb-1">Tunggakan</div>
                                    <div class="fw-bold text-danger">
                                        Rp {{ number_format($totalTunggakanPerTahun[$tahun] ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="progress" style="height: 8px">
                                <div class="progress-bar bg-{{ $statusClass }}"
                                     role="progressbar"
                                     style="width: {{ $presentase }}%"
                                     aria-valuenow="{{ $presentase }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Total Tunggakan Keseluruhan --}}
                    <div class="d-flex justify-content-between align-items-center p-3 bg-danger bg-opacity-10 rounded-3">
                        <h6 class="mb-0">Total Tunggakan Keseluruhan</h6>
                        <div class="text-danger fw-bold fs-5">
                            Rp {{ number_format($total_tunggakan ?? 0, 0, ',', '.') }}
                        </div>
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
                        <div class="tunggakan-item bg-light rounded-3 p-2 p-md-3 border">
                            <div class="row g-2 g-md-3 align-items-center">
                                <div class="col-7 col-sm-4">
                                    <div class="vstack">
                                        <span class="fw-bold text-primary fs-6 fs-md-5">{{ $pembayaran->nama_bulan }}</span>
                                        <span class="text-muted fs-7">{{ $pembayaran->tahun }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-5 col-sm-5 text-end text-sm-start">
                                    <div class="vstack">
                                        <span class="fw-bold fs-7 fs-md-6">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</span>
                                        <span class="text-danger small d-none d-sm-inline">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Jatuh tempo: 10 {{ $pembayaran->nama_bulan }} {{ $pembayaran->tahun }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-sm-3">
                                    <button class="btn btn-primary btn-sm btn-md-lg w-100" onclick="showPembayaranOptions({{ $pembayaran->id }}, '{{ $pembayaran->tahun }}')">
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
        @endif
    </div>
</div>

<!-- Modal Detail Pembayaran -->
<div class="modal fade" id="modalDetailPembayaran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pembayaran SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h6 class="mb-3 fw-bold text-primary">Informasi Tagihan</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%">Periode</td>
                            <td><span id="detail-bulan" class="fw-bold"></span> <span id="detail-tahun"></span></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><span id="detail-status"></span></td>
                        </tr>
                        <tr>
                            <td>Nominal</td>
                            <td class="fw-bold text-primary">Rp <span id="detail-nominal"></span></td>
                        </tr>
                    </table>
                </div>
                
                @php
                    $metode_manual = App\Models\MetodePembayaran::where('kode', 'like', 'MANUAL_%')->get();
                    $metode_online = App\Models\MetodePembayaran::where('kode', 'MIDTRANS')->first();
                @endphp
                <div id="pembayaran-options" class="mt-3">
                    <h6 class="mb-3">Pilih Metode Pembayaran:</h6>
                    <div class="d-grid gap-2">
                        @foreach($metode_manual as $metode)
                            <button class="btn {{ $metode->kode == 'MANUAL_TUNAI' ? 'btn-outline-primary' : 'btn-outline-info' }} btn-block" 
                                    onclick="bayarManual('{{ $metode->kode }}', '{{ $metode->nama }}')">
                                <i class="fas {{ $metode->kode == 'MANUAL_TUNAI' ? 'fa-money-bill' : 'fa-exchange-alt' }} me-2"></i>{{ $metode->nama }}
                            </button>
                        @endforeach
                        @if($metode_online)
                            <button class="btn btn-primary btn-block" onclick="bayarOnline()">
                                <i class="fas fa-globe me-2"></i>{{ $metode_online->nama }}
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

function showPembayaranOptions(id, tahun) {
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
    
    // Update tampilan modal detail
    document.getElementById('detail-nominal').textContent = document.querySelector(`button[onclick*="${id}"]`).closest('.bg-light').querySelector('.fw-bold').textContent.replace('Rp ', '');
    document.getElementById('detail-bulan').textContent = document.querySelector(`button[onclick*="${id}"]`).closest('.bg-light').querySelector('.fw-bold.text-primary').textContent;
    document.getElementById('detail-tahun').textContent = tahun;

    // Set status badge di modal
    const statusBadge = document.createElement('span');
    statusBadge.className = 'badge bg-warning';
    statusBadge.textContent = 'Belum Lunas';
    document.getElementById('detail-status').innerHTML = '';
    document.getElementById('detail-status').appendChild(statusBadge);
    
    const modal = new bootstrap.Modal(document.getElementById('modalDetailPembayaran'));
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

    const modalPembayaran = bootstrap.Modal.getInstance(document.getElementById('modalDetailPembayaran'));
    modalPembayaran.hide();

    Swal.fire({
        title: `Pembayaran ${nama}`,
        html: pesan,
        icon: 'info',
        confirmButtonText: 'Mengerti'
    });
}

function bayarOnline() {
    if (selectedPembayaranId) {
        const modalPembayaran = bootstrap.Modal.getInstance(document.getElementById('modalDetailPembayaran'));
        modalPembayaran.hide();
        bayarSPP(selectedPembayaranId);
    }
}
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
