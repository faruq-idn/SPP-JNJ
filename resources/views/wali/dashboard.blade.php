@extends('layouts.wali')

@section('title', 'Dashboard')

@section('content')
<style>
.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}
</style>
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
                                        <div class="vstack gap-3">
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-info bg-opacity-10 text-info">
                                                        <i class="fas fa-graduation-cap"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="text-muted small">Jenjang & Kelas</div>
                                                        <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                                                            <span class="badge bg-info fs-7 fs-md-6">{{ $santri->jenjang }}</span>
                                                            <span class="badge bg-primary fs-7 fs-md-6">Kelas {{ $santri->kelas }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-success bg-opacity-10 text-success">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Tanggal Masuk</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1">{{ $santri->tanggal_masuk->format('d F Y') }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-info bg-opacity-10 text-info">
                                                        <i class="fas fa-graduation-cap"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Tahun Tamat</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1">{{ $santri->tahun_tamat ?: '-' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Jenis Kelamin</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1">{{ $santri->jenis_kelamin }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-warning bg-opacity-10 text-warning">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Alamat</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1">{{ $santri->alamat ?: '-' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="vstack gap-3">
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-success bg-opacity-10 text-success">
                                                        <i class="fas fa-layer-group"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Kategori Santri</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1">{{ $santri->kategori->nama }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-item p-3 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2 mb-3">
                                                    <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                                                        <i class="fas fa-money-bill"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Tarif SPP Bulanan</div>
                                                        <div class="fw-bold text-primary fs-6 mt-1">
                                                            @if($santri->kategori->tarifTerbaru)
                                                                Rp {{ number_format($santri->kategori->tarifTerbaru->nominal, 0, ',', '.') }}
                                                            @else
                                                                <span class="text-muted">Belum diatur</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($santri->kategori->tarifTerbaru)
                                                <div class="rounded-3 bg-white p-2">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <div class="p-2 rounded bg-success bg-opacity-10">
                                                                <div class="text-muted small">Makan</div>
                                                                <div class="fw-semibold">Rp {{ number_format($santri->kategori->tarifTerbaru->biaya_makan, 0, ',', '.') }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="p-2 rounded bg-info bg-opacity-10">
                                                                <div class="text-muted small">Asrama</div>
                                                                <div class="fw-semibold">Rp {{ number_format($santri->kategori->tarifTerbaru->biaya_asrama, 0, ',', '.') }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="p-2 rounded bg-warning bg-opacity-10">
                                                                <div class="text-muted small">Listrik</div>
                                                                <div class="fw-semibold">Rp {{ number_format($santri->kategori->tarifTerbaru->biaya_listrik, 0, ',', '.') }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="p-2 rounded bg-danger bg-opacity-10">
                                                                <div class="text-muted small">Kesehatan</div>
                                                                <div class="fw-semibold">Rp {{ number_format($santri->kategori->tarifTerbaru->biaya_kesehatan, 0, ',', '.') }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                    <button class="btn btn-primary btn-sm btn-md-lg w-100" onclick="showDetailPembayaran({{ $pembayaran->id }}, '{{ $pembayaran->nama_bulan }}', {{ $pembayaran->nominal }}, 'unpaid', '-', '-', '{{ $pembayaran->tahun }}')">
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

@include('layouts.partials.modal-detail-pembayaran')
@endsection
