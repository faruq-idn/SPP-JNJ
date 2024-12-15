@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Widgets -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card widget-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Santri</h6>
                            <h3 class="card-title mb-0">{{ $totalSantri ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-user-graduate text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Penerimaan</h6>
                            <h3 class="card-title mb-0">Rp {{ number_format($totalPenerimaan ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-money-bill-wave text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Tunggakan</h6>
                            <h3 class="card-title mb-0">Rp {{ number_format($totalTunggakan ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="fas fa-exclamation-circle text-danger fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Pembayaran Hari Ini</h6>
                            <h3 class="card-title mb-0">{{ $pembayaranHariIni ?? 0 }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-calendar-check text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pembayaran Terbaru -->
        <div class="col-md-8">
            <div class="card widget-card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Pembayaran Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Santri</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayaranTerbaru ?? [] as $pembayaran)
                                    <tr>
                                        <td>{{ $pembayaran->tanggal_bayar }}</td>
                                        <td>{{ $pembayaran->santri->nama }}</td>
                                        <td>Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $pembayaran->status == 'success' ? 'success' : 'warning' }}">
                                                {{ $pembayaran->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi -->
        <div class="col-md-4">
            <div class="card widget-card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Notifikasi</h5>
                    @forelse($notifications ?? [] as $notification)
                        <div class="notification-item">
                            <h6 class="mb-1">{{ $notification->title }}</h6>
                            <p class="mb-1 text-muted small">{{ $notification->message }}</p>
                            <small class="text-muted">{{ $notification->created_at }}</small>
                        </div>
                    @empty
                        <p class="text-muted text-center">Tidak ada notifikasi baru</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
