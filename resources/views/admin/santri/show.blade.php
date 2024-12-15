@extends('layouts.admin')

@section('title', 'Detail Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Santri</h1>
        <div>
            <a href="{{ route('admin.santri.edit', $santri) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Data
            </a>
            <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Data Santri -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Santri</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">NISN</th>
                            <td>{{ $santri->nisn }}</td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $santri->nama }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>{{ $santri->tanggal_lahir->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $santri->alamat }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <td>{{ $santri->tanggal_masuk->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jenjang & Kelas</th>
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
                </div>
            </div>
        </div>

        <!-- Data Wali & Kategori -->
        <div class="col-md-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Wali Santri</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Nama Wali</th>
                            <td>{{ $santri->wali->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $santri->wali->email }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori & Tarif SPP</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Kategori</th>
                            <td>{{ $santri->kategori->nama }}</td>
                        </tr>
                        <tr>
                            <th>Tarif SPP</th>
                            <td>
                                @if($santri->kategori->tarifTerbaru)
                                    Rp {{ number_format($santri->kategori->tarifTerbaru->nominal, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">Belum diatur</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Total Tunggakan</th>
                            <td class="text-danger">
                                Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran Terakhir</h6>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fas fa-history"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Bulan/Tahun</th>
                                    <th>Nominal</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($santri->pembayaran as $p)
                                    <tr>
                                        <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                                        <td>{{ $p->bulan }}/{{ $p->tahun }}</td>
                                        <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                        <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $p->status == 'success' ? 'success' : 'warning' }}">
                                                {{ $p->status }}
                                            </span>
                                        </td>
                                        <td>{{ $p->keterangan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada riwayat pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
