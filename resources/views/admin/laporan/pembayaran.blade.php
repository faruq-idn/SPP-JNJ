
@extends('layouts.admin')

@section('title', 'Laporan Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Pembayaran</h1>
        <div class="btn-group">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <button type="button" class="btn btn-danger" onclick="printPDF()">
                <i class="fas fa-file-pdf me-1"></i>PDF
            </button>
            <button type="button" class="btn btn-success" onclick="exportExcel()">
                <i class="fas fa-file-excel me-1"></i>Excel
            </button>
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
                            {{ request('tanggal_awal') && request('tanggal_akhir') 
                                ? Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y') . ' - ' . Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y')
                                : 'Semua Periode' }}
                        </span>
                        <span class="mx-2">|</span>
                        Status: 
                        <span class="text-primary">
                            {{ request('status') ? ucfirst(request('status')) : 'Semua' }}
                        </span>
                    </h4>
                </div>
                <div class="col-md-4 text-md-end">
                    <h5 class="mb-0">
                        Total: <span class="text-primary">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                    </h5>
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
                            <th>Tanggal</th>
                            <th>NIS</th>
                            <th>Nama Santri</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Bulan</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayaran as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                            <td>{{ str_pad($p->santri->nisn, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $p->santri->nama }}</td>
                            <td>{{ $p->santri->jenjang }} {{ $p->santri->kelas }}</td>
                            <td>{{ $p->santri->kategori->nama }}</td>
                            <td>{{ Carbon\Carbon::createFromFormat('m', $p->bulan)->translatedFormat('F Y') }}</td>
                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td>{{ $p->metode_pembayaran->nama ?? 'Manual' }}</td>
                            <td>
                                <span class="badge bg-{{ $p->status == 'success' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                    @if($p->status == 'success')
                                        Lunas
                                    @elseif($p->status == 'pending')
                                        Pending
                                    @else
                                        Belum Lunas
                                    @endif
                                </span>
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
        order: [[1, 'desc']]
    });
});

function printPDF() {
    let url = new URL(window.location.href);
    url.searchParams.set('export', 'pdf');
    window.location.href = url.toString();
}

function exportExcel() {
    let url = new URL(window.location.href);
    url.searchParams.set('export', 'excel');
    window.location.href = url.toString();
}
</script>
@endpush
@endsection
