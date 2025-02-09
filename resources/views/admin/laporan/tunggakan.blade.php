@extends('layouts.admin')

@section('title', 'Laporan Tunggakan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Tunggakan</h1>
        <div class="btn-group">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('admin.laporan.tunggakan', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i>PDF
            </a>
            <a href="{{ route('admin.laporan.tunggakan', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i>Excel
            </a>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="card-title mb-0">
                        Filter Aktif:
                        <span class="text-primary">
                            {{ request('jenjang') ? 'Jenjang ' . request('jenjang') : 'Semua Jenjang' }}
                            {{ request('kelas') ? ', Kelas ' . request('kelas') : '' }}
                            @if(request('min_tunggakan'))
                                , Min. {{ request('min_tunggakan') }} Bulan
                            @endif
                        </span>
                    </h4>
                </div>
                <div class="col-md-4 text-md-end">
                    <h5 class="mb-0">
                        Total Tunggakan: <span class="text-danger">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Santri Nunggak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($santri) }} Santri</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rata-rata Tunggakan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalTunggakan / max(count($santri), 1), 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tunggakan Tertinggi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $santri->max('tunggakan_count') }} Bulan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Lama Tunggakan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($santri->avg('tunggakan_count'), 1) }} Bulan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Santri</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Wali Santri</th>
                            <th>No HP</th>
                            <th>Jumlah Bulan</th>
                            <th>Total Tunggakan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($santri as $index => $s)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ str_pad($s->nisn, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->jenjang }} {{ $s->kelas }}</td>
                            <td>{{ $s->kategori->nama }}</td>
                            <td>{{ $s->wali->name ?? '-' }}</td>
                            <td>{{ $s->wali->no_hp ?? '-' }}</td>
                            <td>{{ $s->tunggakan_count }} bulan</td>
                            <td>Rp {{ number_format($s->pembayaran->sum('nominal'), 0, ',', '.') }}</td>
                            <td>
                                <small class="d-block text-danger">
                                    {{ implode(', ', $s->pembayaran->pluck('bulan')->map(function($bulan) {
                                        return Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F');
                                    })->toArray()) }}
                                </small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        },
        order: [[7, 'desc']] // Sort by jumlah bulan descending
    });
});

</script>
@endpush
@endsection
