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
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Riwayat Pembayaran
                    </h5>
                    <button class="btn btn-sm btn-primary" onclick="tambahPembayaran()">
                        <i class="fas fa-plus me-1"></i>Tambah Pembayaran
                    </button>
                </div>
            </div>

            <!-- Tab tahun -->
            <div class="card-header bg-white py-3 border-bottom">
                <ul class="nav nav-tabs card-header-tabs">
                    @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                           data-bs-toggle="tab"
                           style="font-size: 1.1rem; font-weight: 500; background-color: #e0e7ff;"
                           href="#tahun-{{ $tahun }}">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ $tahun }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Tampilkan tahun yang tidak aktif -->
            
            <div class="tab-content">
                @foreach($pembayaranPerTahun as $tahun => $pembayaranList)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                     id="tahun-{{ $tahun }}">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Bulan</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Nominal</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembayaranList as $p)
                                <tr>
                                    <td>
                                        @php
                                            $namaBulan = \Carbon\Carbon::createFromFormat('m', $p->bulan)->translatedFormat('F');
                                        @endphp
                                        {{ $namaBulan }}
                                    </td>
                                    <td>
                                        @if($p->tanggal_bayar)
                                            {{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        @if($p->metode_pembayaran)
                                            <span class="badge bg-info">
                                                {{ $p->metode_pembayaran->nama }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $p->status == 'success' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if(is_object($p) && isset($p->id))
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-info" onclick="showDetail('{{ $p->id }}')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($p->status != 'success')
                                                    <button class="btn btn-success" onclick="verifikasiPembayaran('{{ $p->id }}')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-danger" onclick="hapusPembayaran('{{ $p->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @else
                                            <button class="btn btn-sm btn-primary" onclick="tambahPembayaran('{{ $tahun }}', '{{ $p->bulan }}')">
                                                <i class="fas fa-plus me-1"></i>Bayar
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showDetail(id) {
    // Implementasi detail pembayaran
    Swal.fire({
        title: 'Detail Pembayaran',
        text: 'Fitur detail pembayaran akan segera tersedia',
        icon: 'info'
    });
}

function verifikasiPembayaran(id) {
    Swal.fire({
        title: 'Verifikasi Pembayaran',
        text: 'Apakah Anda yakin ingin memverifikasi pembayaran ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Verifikasi',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementasi verifikasi pembayaran
            Swal.fire('Berhasil', 'Pembayaran telah diverifikasi', 'success');
        }
    });
}

function hapusPembayaran(id) {
    Swal.fire({
        title: 'Hapus Pembayaran',
        text: 'Apakah Anda yakin ingin menghapus pembayaran ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementasi hapus pembayaran
            Swal.fire('Berhasil', 'Pembayaran telah dihapus', 'success');
        }
    });
}

function tambahPembayaran() {
    // Implementasi tambah pembayaran
    Swal.fire({
        title: 'Tambah Pembayaran',
        text: 'Fitur tambah pembayaran akan segera tersedia',
        icon: 'info'
    });
}
</script>
@endpush
