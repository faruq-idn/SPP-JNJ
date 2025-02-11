@extends('layouts.admin')

@section('title', 'Detail Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Santri</h1>
        <div>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.santri.edit', $santri) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Data
                </a>
            @endif
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
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
                            <td>
                                @if($santri->wali_id)
                                    {{ $santri->wali->name }}
                                @elseif($santri->nama_wali)
                                    {{ $santri->nama_wali }}
                                    <span class="badge bg-warning text-dark">Belum terhubung</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $santri->wali->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status Wali</th>
                            <td>
                                @if($santri->wali_id)
                                    <span class="badge bg-success">Terhubung</span>
                                @elseif($santri->nama_wali)
                                    <span class="badge bg-warning text-dark">Menunggu Klaim</span>
                                @else
                                    <span class="badge bg-secondary">Belum Ada Wali</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori & Tarif SPP</h6>
                </div>
                <div class="card-body">
                    @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
                        @php
                            $totalBulan = count($pembayaranBulanan);
                            $lunasBulan = $pembayaranBulanan->where('status', 'success')->count();
                            $isLunas = $lunasBulan === $totalBulan;
                            $presentase = ($lunasBulan / $totalBulan) * 100;
                            $statusClass = $isLunas ? 'success' : ($presentase > 50 ? 'warning' : 'danger');
                        @endphp
                        <div class="p-3 rounded-3 bg-{{ $statusClass }} bg-opacity-10 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="text-muted">Status SPP Tahun {{ $tahun }}</span>
                                    <div class="fw-bold fs-6">
                                        @if($isLunas)
                                            <span class="text-success">Lunas</span>
                                        @else
                                            <span class="text-danger">Belum Lunas ({{ $lunasBulan }}/{{ $totalBulan }} bulan)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted">Total Tunggakan</span>
                                    <div class="fw-bold fs-6 text-danger">
                                        Rp {{ number_format($totalTunggakanPerTahun[$tahun] ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px">
                                <div class="progress-bar bg-{{ $statusClass }}" 
                                     role="progressbar" 
                                     style="width: {{ $presentase }}%" 
                                     aria-valuenow="{{ $presentase }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <table class="table">
                        <tr>
                            <th width="30%">Kategori</th>
                            <td>{{ $santri->kategori->nama }}</td>
                        </tr>
                        <tr>
                            <th>Tarif SPP</th>
                            <td>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($santri->kategori->tarifTerbaru)
                                        <span>Rp {{ number_format($santri->kategori->tarifTerbaru->nominal, 0, ',', '.') }}</span>
                                        <span class="text-muted small">per bulan</span>
                                    @else
                                        <span class="text-muted">Belum diatur</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Tunggakan</th>
                            <td class="text-danger fw-bold">
                                Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Riwayat Pembayaran
                    </h5>
                    <button class="btn btn-sm btn-primary" onclick="tambahPembayaran()">
                        <i class="fas fa-plus me-1"></i>Tambah Pembayaran
                    </button>
                </div>
            </div>

            @include('admin.santri._show_table')
            
        </div>
        </div>
    </div>
</div>
@endsection

@include('admin.santri._show_modal')
