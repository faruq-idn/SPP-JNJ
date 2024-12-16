@extends('layouts.wali')

@section('title', 'Dashboard Wali Santri')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-home me-2"></i>Dashboard
            </h2>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($santri)
                <!-- Info Cards -->
                <div class="row g-3 mb-4">
                    <!-- Status SPP Card -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-file-invoice-dollar text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="card-subtitle text-muted mb-1">Status SPP</h6>
                                        <h4 class="card-title mb-0">
                                            <span class="badge bg-{{ $santri->status_spp == 'Lunas' ? 'success' : 'warning' }}">
                                                {{ $santri->status_spp ?? 'Belum ada data' }}
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                                <a href="{{ route('wali.tagihan') }}" class="btn btn-light btn-sm w-100">
                                    <i class="fas fa-arrow-right me-1"></i>Lihat Tagihan
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Data Santri Card -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-user-graduate text-success fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="card-subtitle text-muted mb-1">Nama Santri</h6>
                                        <h4 class="card-title mb-0">{{ $santri->nama }}</h4>
                                    </div>
                                </div>
                                <div class="small">
                                    <p class="mb-1"><strong>NIS:</strong> {{ $santri->nis }}</p>
                                    <p class="mb-1"><strong>Kelas:</strong> {{ $santri->kelas }}</p>
                                    <p class="mb-0"><strong>Kategori:</strong> {{ $santri->kategori->nama ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Pembayaran Card -->
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0 bg-info bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-history text-info fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="card-subtitle text-muted mb-1">Pembayaran Terakhir</h6>
                                        <h4 class="card-title mb-0">
                                            {{ $pembayaran->first() ? $pembayaran->first()->created_at->format('d M Y') : '-' }}
                                        </h4>
                                    </div>
                                </div>
                                <a href="{{ route('wali.pembayaran') }}" class="btn btn-light btn-sm w-100">
                                    <i class="fas fa-arrow-right me-1"></i>Lihat Riwayat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pembayaran Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-history me-2"></i>Riwayat Pembayaran
                            </h5>
                            <a href="{{ route('wali.pembayaran') }}" class="btn btn-sm btn-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Bulan</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembayaran as $p)
                                        <tr>
                                            <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $p->bulan }}</td>
                                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $p->status == 'success' ? 'success' : 'warning' }}">
                                                    {{ $p->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3">
                                                <i class="fas fa-info-circle me-2"></i>Belum ada riwayat pembayaran
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        Data santri tidak ditemukan. Silakan hubungi admin untuk informasi lebih lanjut.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
