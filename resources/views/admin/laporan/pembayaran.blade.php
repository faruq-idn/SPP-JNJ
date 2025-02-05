@extends('layouts.admin')

@section('title', 'Laporan Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Pembayaran</h1>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.laporan.export.pembayaran', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => request('kategori')]) }}" 
               class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('admin.laporan.pembayaran.pdf', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => request('kategori')]) }}" 
               class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ sprintf('%02d', $i) }}" 
                                {{ $bulan == sprintf('%02d', $i) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        @php $currentYear = date('Y'); @endphp
                        @for($year = $currentYear - 1; $year <= $currentYear + 1; $year++)
                            <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori_list as $k)
                            <option value="{{ $k->id }}" 
                                {{ request('kategori') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>NISN</th>
                            <th>Nama Santri</th>
                            <th>Kategori</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                            <td>{{ $p->santri->nisn }}</td>
                            <td>{{ $p->santri->nama }}</td>
                            <td>{{ $p->santri->kategori->nama }}</td>
                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td>{{ $p->metode }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total:</th>
                            <th colspan="2">Rp {{ number_format($total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
