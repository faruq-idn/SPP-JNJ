@extends('layouts.admin')

@section('title', 'Detail Pembayaran Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pembayaran Santri</h1>
        <div>
            <a href="{{ route('admin.santri.show', $santri) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail
            </a>
        </div>
    </div>

    <!-- Ringkasan Pembayaran -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded">
                                        <i class="fas fa-user-graduate text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Santri</small>
                                        <span class="fw-bold">{{ $santri->nama }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-info bg-opacity-10 p-2 rounded">
                                        <i class="fas fa-tags text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kategori</small>
                                        <span class="fw-bold">{{ $santri->kategori->nama }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success bg-opacity-10 p-2 rounded">
                                        <i class="fas fa-money-bill text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tarif Bulanan</small>
                                        <span class="fw-bold">Rp {{ number_format($santri->kategori->tarifTerbaru->nominal ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status SPP -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded-3 {{ $statusSpp == 'Lunas' ? 'bg-success bg-opacity-10' : 'bg-warning bg-opacity-10' }}" style="border: 1px solid {{ $statusSpp == 'Lunas' ? '#19875414' : '#ffc10714' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <i class="fas fa-chart-pie fa-2x {{ $statusSpp == 'Lunas' ? 'text-success' : 'text-warning' }}"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Status SPP Tahun {{ date('Y') }}</div>
                                <div class="fw-bold fs-5">{{ $statusSpp }}</div>
                            </div>
                        </div>
                        @if($statusSpp != 'Lunas')
                        <div>
                            <div class="text-muted small">Total Tunggakan</div>
                            <div class="fw-bold fs-5 text-danger">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran per Tahun -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <ul class="nav nav-tabs card-header-tabs">
                @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                       data-bs-toggle="tab"
                       href="#tahun-{{ $tahun }}">
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

        <div class="card-body p-0">
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
                                    <td>{{ $p->nama_bulan }}</td>
                                    <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                                    <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        @if($p->metode_pembayaran)
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
                                        @if($p->status != 'success')
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
