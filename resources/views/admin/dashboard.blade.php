@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Statistik Cards Row 1 -->
    <div class="row g-3 mb-4">
        <!-- Total Santri -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users text-primary fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Total Santri Aktif</h6>
                            <h4 class="card-title mb-0">{{ number_format($totalSantri) }}</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>SMP: {{ $santriPerJenjang['SMP'] ?? 0 }}</span>
                        <span>SMA: {{ $santriPerJenjang['SMA'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penerimaan -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-money-bill-wave text-success fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Total Penerimaan</h6>
                            <h4 class="card-title mb-0">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="text-muted small">
                        {{ number_format($totalPembayaran) }} pembayaran berhasil
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Tunggakan -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Total Tunggakan</h6>
                            <h4 class="card-title mb-0">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <a href="{{ route('admin.laporan.tunggakan') }}" class="text-warning small">
                        <i class="fas fa-arrow-right me-1"></i>Lihat detail tunggakan
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-user-cog text-info fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Total Pengguna</h6>
                            <h4 class="card-title mb-0">{{ number_format($totalPetugas + $totalWali) }}</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Petugas: {{ $totalPetugas }}</span>
                        <span>Wali: {{ $totalWali }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Control Navigasi Bulan -->
    <div class="d-flex justify-content-center align-items-center mb-4">
        <a href="{{ route('admin.dashboard', ['bulan' => $prevMonth->format('m'), 'tahun' => $prevMonth->format('Y')]) }}"
           class="btn btn-outline-secondary"
           target="_self">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h5 class="mb-0 mx-4">{{ $selectedDate->translatedFormat('F Y') }}</h5>
        @if($canGoNext)
        <a href="{{ route('admin.dashboard', ['bulan' => $nextMonth->format('m'), 'tahun' => $nextMonth->format('Y')]) }}"
           class="btn btn-outline-secondary"
           target="_self">
            <i class="fas fa-chevron-right"></i>
        </a>
        @else
        <button class="btn btn-outline-secondary" disabled>
            <i class="fas fa-chevron-right"></i>
        </button>
        @endif
    </div>

    <!-- Statistik Cards Row 2 -->
    <div class="row g-3 mb-4">
        <!-- Total Pembayaran Bulan Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-calendar-check text-success fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Pembayaran {{ $selectedDate->translatedFormat('F Y') }}</h6>
                            <h4 class="card-title mb-0">Rp {{ number_format($pembayaranBulanIni, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tunggakan Bulan Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-danger bg-opacity-10 p-3 rounded">
                            <i class="fas fa-calendar-times text-danger fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Tunggakan {{ $selectedDate->translatedFormat('F Y') }}</h6>
                            <h4 class="card-title mb-0">Rp {{ number_format($tunggakanBulanIni, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumlah Santri Menunggak -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-user-clock text-warning fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Santri Menunggak</h6>
                            <h4 class="card-title mb-0">{{ number_format($jumlahSantriMenunggak) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Santri per Kategori -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-layer-group text-primary fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Santri per Kategori</h6>
                        </div>
                    </div>
                    <div class="small">
                        @foreach($santriPerKategori as $kategori => $jumlah)
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ $kategori }}</span>
                                <span class="fw-bold">{{ $jumlah }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pembayaran Terbaru -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Pembayaran Terbaru
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Santri</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembayaranTerbaru as $p)
                                <tr>
                                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $p->santri->nama }}</div>
                                        <small class="text-muted">{{ $p->santri->nisn }}</small>
                                    </td>
                                    <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $p->metode_pembayaran->nama ?? 'Manual' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $p->status == 'success' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        <i class="fas fa-info-circle me-2"></i>Belum ada pembayaran
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Santri dengan Tunggakan -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>Tunggakan Terbanyak
                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($santriTunggakan as $s)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $s->nama }}</h6>
                                    <small class="text-muted d-block">
                                        {{ $s->jenjang }} {{ $s->kelas }} | NISN: {{ $s->nisn }}
                                    </small>
                                    <small class="text-danger d-block">
                                        Rp {{ number_format($s->total_tunggakan, 0, ',', '.') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger rounded-pill">
                                        {{ $s->jumlah_bulan_tunggakan }} bulan
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center py-3">
                            <i class="fas fa-check-circle text-success me-2"></i>Tidak ada tunggakan
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
