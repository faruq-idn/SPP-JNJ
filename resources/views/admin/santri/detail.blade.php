@extends('layouts.admin')

@section('title', 'Detail Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Santri</h1>
        <div class="d-flex gap-2">
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
                            <th>No. HP</th>
                            <td>{{ $santri->wali->no_hp ?? '-' }}</td>
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
                    <h6 class="m-0 font-weight-bold text-primary">Kategori & SPP</h6>
                    <a href="{{ route('admin.kategori.edit', $santri->kategori) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-cog"></i> Kelola Kategori
                    </a>
                </div>
                <div class="card-body">
                    <!-- Status SPP -->
                    <div class="p-3 mb-3 rounded-3 {{ $statusSpp == 'Lunas' ? 'bg-success bg-opacity-10' : 'bg-warning bg-opacity-10' }}" style="border: 1px solid {{ $statusSpp == 'Lunas' ? '#19875414' : '#ffc10714' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Status SPP Tahun {{ date('Y') }}</div>
                                <div class="fw-bold fs-5">{{ $statusSpp }}</div>
                            </div>
                            @if($statusSpp != 'Lunas')
                            <div class="text-end">
                                <div class="text-muted small">Total Tunggakan</div>
                                <div class="fw-bold fs-5 text-danger">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</div>
                            </div>
                            @endif
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
                        @if($tunggakan > 0)
                        <tr>
                            <th>Tunggakan {{ $tahun }}</th>
                            <td class="text-danger">Rp {{ number_format($tunggakan, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
        </div>
        <div class="card-header bg-white py-3 border-bottom">
            <ul class="nav nav-tabs card-header-tabs">
                @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                       data-bs-toggle="tab"
                       href="#tahun-{{ $tahun }}"
                       style="background-color: {{ $tahun == date('Y') ? '#e0e7ff' : '#f3f4f6' }}; font-size: 1.1rem; font-weight: 500;">
                        <i class="fas fa-calendar-alt me-2"></i>{{ $tahun }}
                        @if($totalTunggakanPerTahun[$tahun] > 0)
                            <span class="badge bg-danger ms-2">
                                Rp {{ number_format($totalTunggakanPerTahun[$tahun], 0, ',', '.') }}
                            </span>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="card-body">
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
                                    <td>{{ \Carbon\Carbon::createFromDate(null, $p->bulan, 1)->translatedFormat('F') }}</td>
                                    <td>{{ isset($p->tanggal_bayar) ? $p->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                                    <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        @if(isset($p->metode_pembayaran))
                                            <span class="badge bg-info">{{ $p->metode_pembayaran->nama }}</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $p->status == 'success' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($p->status == 'success' ? 'Lunas' : ($p->status == 'pending' ? 'Pending' : 'Belum Lunas')) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($p->status != 'success' && isset($p->id))
                                            <button class="btn btn-success btn-sm" onclick="verifikasiPembayaran('{{ $p->id }}')">
                                                <i class="fas fa-check"></i>
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

<!-- Modal Verifikasi Pembayaran -->
<div class="modal fade" id="modalVerifikasi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formVerifikasi" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="metode_pembayaran_id" required>
                            <option value="">Pilih Metode</option>
                            <option value="1">Manual/Tunai</option>
                            <option value="2">Manual/Transfer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan (opsional)</label>
                        <textarea class="form-control" name="keterangan" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Verifikasi Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verifikasiPembayaran(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalVerifikasi'));
    const form = document.getElementById('formVerifikasi');
    form.action = `{{ url('admin/santri/pembayaran') }}/${id}/verifikasi`;
    modal.show();
}

// Handle form submit
document.getElementById('formVerifikasi').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                title: 'Berhasil',
                text: data.message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Terjadi kesalahan saat memproses pembayaran', 'error');
    });
});
</script>
@endpush
@endsection
