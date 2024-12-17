@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan</h1>
    </div>

    <!-- Instruksi Card -->
    <div class="alert alert-info mb-4" role="alert">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle fa-2x me-3"></i>
            </div>
            <div>
                <h5 class="alert-heading">Petunjuk Penggunaan:</h5>
                <ol class="mb-0">
                    <li>Pilih jenis laporan yang ingin Anda lihat (Pembayaran atau Tunggakan)</li>
                    <li>Gunakan filter yang tersedia untuk menyaring data sesuai kebutuhan</li>
                    <li>Klik tombol "Tampilkan" untuk melihat laporan</li>
                    <li>Anda juga dapat mengunduh laporan dalam format Excel</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Laporan Pembayaran Card -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>Laporan Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.laporan.pembayaran') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-text">Dari</span>
                                        <input type="date" class="form-control" name="tanggal_awal">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-text">Sampai</span>
                                        <input type="date" class="form-control" name="tanggal_akhir">
                                    </div>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Kosongkan untuk melihat semua periode
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Pembayaran</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="success">Lunas</option>
                                <option value="pending">Belum Lunas</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Tampilkan
                            </button>
                            <button type="submit" class="btn btn-success" formaction="{{ route('admin.laporan.export.pembayaran') }}">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Laporan Tunggakan Card -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Laporan Tunggakan
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.laporan.tunggakan') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label">Jenjang</label>
                            <select class="form-select" name="jenjang">
                                <option value="">Semua Jenjang</option>
                                @foreach($jenjang as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select class="form-select" name="kelas" id="kelas" disabled>
                                <option value="">Pilih Jenjang Dulu</option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih jenjang terlebih dahulu
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Tampilkan
                            </button>
                            <button type="submit" class="btn btn-success" formaction="{{ route('admin.laporan.export.tunggakan') }}">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Populate kelas based on jenjang
$('select[name="jenjang"]').on('change', function() {
    const jenjang = $(this).val();
    const kelasSelect = $('#kelas');

    kelasSelect.prop('disabled', !jenjang);

    if (jenjang) {
        const kelas = jenjang === 'SMP'
            ? ['7A', '7B', '8A', '8B', '9A', '9B']
            : ['10A', '10B', '11A', '11B', '12A', '12B'];

        kelasSelect.html('<option value="">Semua Kelas</option>' +
            kelas.map(k => `<option value="${k}">${k}</option>`).join('')
        );
    } else {
        kelasSelect.html('<option value="">Pilih Jenjang Dulu</option>');
    }
});
</script>
@endpush
@endsection
