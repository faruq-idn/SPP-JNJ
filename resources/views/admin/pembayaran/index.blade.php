@extends('layouts.admin')

@section('title', 'Data Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pembayaran</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Tagihan
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generateTagihanModal">
                <i class="fas fa-file-invoice"></i> Buat Tagihan Masal
            </button>
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
                                <a href="{{ route('admin.santri.show', $p->santri_id) }}" class="btn btn-sm btn-info">
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
                                <a href="{{ route('admin.santri.show', $p->santri_id) }}" class="btn btn-sm btn-info">
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

<!-- Generate Tagihan Modal -->
<div class="modal fade" id="generateTagihanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.pembayaran.generate-tagihan') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Generate Tagihan Santri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <select class="form-select" name="tahun" required>
                            @php
                                $currentYear = date('Y');
                                $yearRange = range($currentYear - 1, $currentYear + 1);
                            @endphp
                            @foreach($yearRange as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bulan</label>
                        <select class="form-select" name="bulan" required>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ $month == date('n') ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromDate(null, $month, 1)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Tagihan akan dibuat untuk semua santri aktif yang belum memiliki tagihan pada periode yang dipilih.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Generate Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
