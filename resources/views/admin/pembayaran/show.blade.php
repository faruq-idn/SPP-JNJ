@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pembayaran</h1>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- Data Santri -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Santri</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">NISN</th>
                            <td>{{ $pembayaran->santri->nisn }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $pembayaran->santri->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $pembayaran->santri->jenjang }} {{ $pembayaran->santri->kelas }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $pembayaran->santri->status === 'aktif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($pembayaran->santri->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Data Pembayaran -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Pembayaran</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Periode</th>
                            <td>{{ \Carbon\Carbon::createFromDate(null, $pembayaran->bulan, 1)->translatedFormat('F') }} {{ $pembayaran->tahun }}</td>
                        </tr>
                        <tr>
                            <th>Nominal</th>
                            <td>Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $pembayaran->status == 'success' ? 'success' : ($pembayaran->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($pembayaran->status == 'success' ? 'Lunas' : ($pembayaran->status == 'pending' ? 'Pending' : 'Belum Lunas')) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Metode</th>
                            <td>
                                @if($pembayaran->metode_pembayaran)
                                    <span class="badge bg-info">{{ $pembayaran->metode_pembayaran->nama }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Bayar</th>
                            <td>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $pembayaran->keterangan ?: '-' }}</td>
                        </tr>
                    </table>

                    @if($pembayaran->status != 'success')
                    <div class="mt-3">
                        <button class="btn btn-success" onclick="verifikasiPembayaran('{{ $pembayaran->id }}')">
                            <i class="fas fa-check me-1"></i> Verifikasi Pembayaran
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
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
    form.action = `{{ url('admin/pembayaran') }}/${id}/verifikasi`;
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
