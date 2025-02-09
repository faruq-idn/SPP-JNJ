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
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori & Tarif SPP</h6>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#riwayatTarifModal">
                        <i class="fas fa-history"></i> Riwayat Tarif
                    </button>
                </div>
                <div class="card-body">
                    <div class="p-3 rounded-3 {{ $statusSpp == 'Lunas' ? 'bg-success bg-opacity-10' : 'bg-warning bg-opacity-10' }} mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">Status SPP Tahun {{ date('Y') }}</span>
                                <div class="fw-bold fs-5">{{ $statusSpp }}</div>
                            </div>
                            <div class="text-end">
                                <span class="text-muted">Total Tunggakan</span>
                                <div class="fw-bold fs-5 text-danger">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

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
                        @foreach($totalTunggakanPerTahun as $tahun => $tunggakan)
                        <tr>
                            <th>Tunggakan {{ $tahun }}</th>
                            <td class="{{ $tunggakan > 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($tunggakan, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <!-- Modal Riwayat Perubahan Tarif -->
            <div class="modal fade" id="riwayatTarifModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Riwayat Perubahan Tarif</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nominal</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($riwayatTarif as $rt)
                                        <tr>
                                            <td>{{ $rt->created_at->format('d/m/Y') }}</td>
                                            <td>Rp {{ number_format($rt->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $rt->keterangan ?: '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
                                            try {
                                                $namaBulan = \Carbon\Carbon::createFromFormat('m', $p->bulan)->translatedFormat('F');
                                            } catch (\Exception $e) {
                                                $namaBulan = '-';
                                            }
                                        @endphp
                                        {{ $namaBulan }}
                                    </td>
                                    <td>
                                        @if(isset($p->tanggal_bayar) && $p->tanggal_bayar)
                                            {{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($p->nominal))
                                            Rp {{ number_format($p->nominal, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($p->metode_pembayaran) && $p->metode_pembayaran)
                                            <span class="badge bg-info">
                                                {{ $p->metode_pembayaran->nama }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($p->status))
                                            <span class="badge bg-{{ $p->status == 'success' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(is_object($p) && isset($p->id))
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info" onclick="showDetail('{{ $p->id }}', '{{ $p->nama_bulan ?? \Carbon\Carbon::create()->month($p->bulan)->translatedFormat('F') }}', {{ $p->nominal }}, '{{ $p->tahun }}', '{{ $p->status }}', '{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y H:i') : '-' }}', '{{ optional($p->metode_pembayaran)->nama ?? '-' }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($p->status != 'success')
                                            <button class="btn btn-success" onclick="verifikasiPembayaran('{{ $p->id }}', '{{ $p->nama_bulan ?? \Carbon\Carbon::create()->month($p->bulan)->translatedFormat('F') }}', {{ $p->nominal }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-danger" onclick="hapusPembayaran('{{ $p->id }}', '{{ $p->nama_bulan ?? \Carbon\Carbon::create()->month($p->bulan)->translatedFormat('F') }}', '{{ $p->tahun }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @else
                                    <button class="btn btn-sm btn-primary" onclick="tambahPembayaran('{{ $tahun }}', '{{ $p->bulan }}', {{ $p->nominal }})">
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

<!-- Modal Detail & Form Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Detail Pembayaran SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h6 class="mb-3 fw-bold text-primary">Informasi Tagihan</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%">Periode</td>
                            <td><span id="detail-bulan" class="fw-bold"></span> <span id="detail-tahun"></span></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><span id="detail-status"></span></td>
                        </tr>
                        <tr>
                            <td>Nominal</td>
                            <td class="fw-bold text-primary">Rp <span id="detail-nominal"></span></td>
                        </tr>
                    </table>
                </div>

                <div id="pembayaran-info" style="display: none;">
                    <h6 class="mb-3 fw-bold text-success">Informasi Pembayaran</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%">Tanggal</td>
                            <td><span id="detail-tanggal"></span></td>
                        </tr>
                        <tr>
                            <td>Metode</td>
                            <td><span id="detail-metode"></span></td>
                        </tr>
                    </table>
                </div>

                <form id="formPembayaran" style="display: none;">
                    @csrf
                    <input type="hidden" name="pembayaran_id" id="pembayaran_id">
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="metode_pembayaran_id" required>
                            <option value="">Pilih Metode</option>
                            <option value="1">Manual/Tunai</option>
                            <option value="2">Manual/Transfer</option>
                            <option value="3">Payment Gateway</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan (opsional)</label>
                        <textarea class="form-control" name="keterangan" rows="2"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-1"></i>Verifikasi Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDetail(id, bulan, nominal, tahun, status, tanggal, metode) {
    const modal = new bootstrap.Modal(document.getElementById('modalPembayaran'));
    
    // Update konten modal
    document.getElementById('modalTitle').textContent = 'Detail Pembayaran SPP';
    document.getElementById('detail-bulan').textContent = bulan;
