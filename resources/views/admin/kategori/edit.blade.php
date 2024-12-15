@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Kategori</h1>
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Kategori</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST" id="formEditKategori">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                id="nama" name="nama" value="{{ old('nama', $kategori->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $kategori->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="konfirmasiEdit()">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tarif SPP</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kategori.updateTarif', $kategori) }}" method="POST" id="formUpdateTarif">
                        @csrf
                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal Tarif Baru</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                    id="nominal" name="nominal" value="{{ old('nominal') }}" required>
                            </div>
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="berlaku_mulai" class="form-label">Berlaku Mulai</label>
                            <input type="date" class="form-control @error('berlaku_mulai') is-invalid @enderror"
                                id="berlaku_mulai" name="berlaku_mulai" value="{{ old('berlaku_mulai', date('Y-m-d')) }}" required>
                            @error('berlaku_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan Perubahan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-success" onclick="konfirmasiUpdateTarif()">
                                <i class="fas fa-save"></i> Update Tarif
                            </button>
                        </div>
                    </form>

                    <hr>

                    <h6 class="font-weight-bold">Riwayat Perubahan Tarif</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nominal</th>
                                    <th>Berlaku Mulai</th>
                                    <th>Berlaku Sampai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategori->riwayatTarif as $tarif)
                                    <tr>
                                        <td>Rp {{ number_format($tarif->nominal, 0, ',', '.') }}</td>
                                        <td>{{ $tarif->berlaku_mulai->format('d/m/Y') }}</td>
                                        <td>{{ $tarif->berlaku_sampai ? $tarif->berlaku_sampai->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $tarif->keterangan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada riwayat tarif</td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function konfirmasiEdit() {
    Swal.fire({
        title: 'Konfirmasi Perubahan',
        text: "Apakah Anda yakin ingin mengubah data kategori ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEditKategori').submit();
        }
    });
}

function konfirmasiUpdateTarif() {
    Swal.fire({
        title: 'Konfirmasi Update Tarif',
        text: "Perubahan tarif akan berlaku sesuai tanggal yang ditentukan. Lanjutkan?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formUpdateTarif').submit();
        }
    });
}
</script>
@endpush
