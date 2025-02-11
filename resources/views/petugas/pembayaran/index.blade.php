@extends('layouts.admin')

@section('title', 'Data Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pembayaran</h1>
        <div class="d-flex gap-2">
            <!-- Tombol untuk admin dihilangkan -->
        </div>
    </div>

    <!-- Belum Lunas Card -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-primary">Belum Lunas</h6>
            <span class="badge bg-danger">{{ $totalBelumLunas }} tagihan</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Santri</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaranPending as $p)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $p->santri->nama }}</span>
                                    <span class="small text-muted">{{ $p->santri->nisn }}</span>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::createFromDate(null, $p->bulan, 1)->translatedFormat('F') }} {{ $p->tahun }}</td>
                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $p->status == 'unpaid' ? 'danger' : 'warning' }}">
                                    {{ $p->status == 'unpaid' ? 'Belum Lunas' : 'Pending' }}
                                </span>
                            </td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('petugas.santri.show', $p->santri_id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">Tidak ada tagihan yang belum lunas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $pembayaranPending->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Lunas Card -->
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-success">Sudah Lunas</h6>
            <span class="badge bg-success">{{ $totalLunas }} tagihan</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Santri</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Tanggal Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaranLunas as $p)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $p->santri->nama }}</span>
                                    <span class="small text-muted">{{ $p->santri->nisn }}</span>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::createFromDate(null, $p->bulan, 1)->translatedFormat('F') }} {{ $p->tahun }}</td>
                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td>
                                @if($p->metode_pembayaran)
                                    <span class="badge bg-info">{{ $p->metode_pembayaran->nama }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('petugas.santri.show', $p->santri_id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">Belum ada pembayaran yang lunas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $pembayaranLunas->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
