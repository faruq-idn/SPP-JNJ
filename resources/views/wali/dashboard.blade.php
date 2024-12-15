@extends('layouts.admin')

@section('title', 'Dashboard Wali Santri')

@section('content')
<div class="container-fluid">
    <!-- Data Santri -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Santri</h6>
                </div>
                <div class="card-body">
                    @if($santri)
                        <table class="table">
                            <tr>
                                <th width="30%">NISN</th>
                                <td>{{ $santri->nisn }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $santri->nama }}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>{{ $santri->jenjang }} - {{ $santri->kelas }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $santri->status === 'aktif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($santri->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    @else
                        <p class="text-center text-muted">Data santri tidak ditemukan</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tagihan SPP</h6>
                </div>
                <div class="card-body">
                    @if($santri)
                        <div class="text-center mb-4">
                            <h3 class="text-danger mb-1">Rp {{ number_format(500000, 0, ',', '.') }}</h3>
                            <p class="text-muted">Tagihan Bulan {{ now()->format('F Y') }}</p>
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i> Bayar Sekarang
                            </a>
                        </div>
                    @else
                        <p class="text-center text-muted">Tidak ada tagihan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Bulan/Tahun</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $p)
                            <tr>
                                <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                                <td>{{ $p->bulan }}/{{ $p->tahun }}</td>
                                <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $p->status == 'success' ? 'success' : 'warning' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada riwayat pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
