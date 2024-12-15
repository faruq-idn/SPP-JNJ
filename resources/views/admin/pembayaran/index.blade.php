@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPembayaran">
            <i class="fas fa-plus"></i> Input Pembayaran
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Santri</th>
                            <th>Bulan/Tahun</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran ?? [] as $p)
                            <tr>
                                <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                                <td>{{ $p->santri->nama }}</td>
                                <td>{{ $p->bulan }}/{{ $p->tahun }}</td>
                                <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                                <td>
                                    <span class="badge bg-{{ $p->status == 'success' ? 'success' : 'warning' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Pembayaran SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.pembayaran.index') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <!-- Data Santri -->
                        <div class="col-md-12 mb-4">
                            <div class="mb-3">
                                <label class="form-label">Cari Santri</label>
                                <select class="form-control" id="santri_id" name="santri_id" required></select>
                                @error('santri_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card bg-light">
                                <div class="card-body">
                                    <div id="detail_santri">
                                        <p class="text-muted text-center mb-0">
                                            Pilih santri untuk melihat detail
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pembayaran -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Bayar</label>
                                <input type="date" class="form-control @error('tanggal_bayar') is-invalid @enderror"
                                    name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                                @error('tanggal_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bulan</label>
                                        <select class="form-select @error('bulan') is-invalid @enderror"
                                            name="bulan" required>
                                            <option value="">Pilih Bulan</option>
                                            @foreach($bulan as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ old('bulan') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bulan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun</label>
                                        <select class="form-select @error('tahun') is-invalid @enderror"
                                            name="tahun" required>
                                            <option value="">Pilih Tahun</option>
                                            @foreach($tahun as $y)
                                                <option value="{{ $y }}"
                                                    {{ old('tahun') == $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nominal</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                        name="nominal" value="{{ old('nominal') }}" required>
                                </div>
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select class="form-select @error('metode_pembayaran') is-invalid @enderror"
                                    name="metode_pembayaran" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>
                                        Tunai
                                    </option>
                                    <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>
                                        Transfer Bank
                                    </option>
                                </select>
                                @error('metode_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk pencarian santri
    $('#santri_id').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#modalPembayaran'),
        placeholder: 'Cari nama/NISN santri...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("admin.santri.search") }}',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        templateResult: formatSantri,
        templateSelection: formatSantriSelection
    });

    $('#dataTable').DataTable();
});
</script>
@endpush
@endsection
