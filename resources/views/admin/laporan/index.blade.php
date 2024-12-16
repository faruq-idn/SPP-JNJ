@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan</h1>
    </div>

    <div class="row">
        <!-- Filter -->
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
                </div>
                <div class="card-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label class="form-label">Jenis Laporan</label>
                            <select class="form-select" id="jenis_laporan" name="jenis_laporan">
                                <option value="pembayaran">Pembayaran SPP</option>
                                <option value="tunggakan">Tunggakan SPP</option>
                            </select>
                        </div>

                        <div id="filter-pembayaran">
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <select class="form-select" name="tahun">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahun as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bulan</label>
                                <select class="form-select" name="bulan">
                                    <option value="">Semua Bulan</option>
                                    @foreach($bulan as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="success">Lunas</option>
                                    <option value="pending">Belum Lunas</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori_id">
                                <option value="">Semua Kategori</option>
                                @foreach($kategori as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenjang</label>
                            <select class="form-select" name="jenjang" id="jenjang">
                                <option value="">Semua Jenjang</option>
                                @foreach($jenjang as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select class="form-select" name="kelas" id="kelas">
                                <option value="">Semua Kelas</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-search me-1"></i> Tampilkan
                        </button>
                        <button type="button" class="btn btn-success w-100" id="btnExport">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hasil Laporan -->
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-body">
                    <div id="loadingIndicator" class="text-center py-5" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </div>

                    <div id="hasilLaporan"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Update opsi kelas saat jenjang berubah
    $('#jenjang').change(function() {
        var jenjang = $(this).val();
        var kelas = {
            'SMP': ['7A', '7B', '8A', '8B', '9A', '9B'],
            'SMA': ['10A', '10B', '11A', '11B', '12A', '12B']
        };
        var options = '<option value="">Semua Kelas</option>';

        if (jenjang && kelas[jenjang]) {
            kelas[jenjang].forEach(function(k) {
                options += `<option value="${k}">${k}</option>`;
            });
        }

        $('#kelas').html(options);
    });

    // Toggle filter pembayaran
    $('#jenis_laporan').change(function() {
        if ($(this).val() === 'pembayaran') {
            $('#filter-pembayaran').show();
        } else {
            $('#filter-pembayaran').hide();
        }
    });

    // Handle form submit
    $('#filterForm').submit(function(e) {
        e.preventDefault();
        loadLaporan();
    });

    // Handle export
    $('#btnExport').click(function() {
        var jenis = $('#jenis_laporan').val();
        var url = `{{ url('admin/laporan') }}/${jenis}/export?${$('#filterForm').serialize()}`;
        window.location.href = url;
    });

    function loadLaporan() {
        var jenis = $('#jenis_laporan').val();
        var url = `{{ url('admin/laporan') }}/${jenis}`;

        $('#loadingIndicator').show();
        $('#hasilLaporan').hide();

        $.get(url, $('#filterForm').serialize(), function(response) {
            renderLaporan(jenis, response);
        }).fail(function() {
            $('#hasilLaporan').html(`
                <div class="alert alert-danger">
                    Gagal memuat data. Silakan coba lagi.
                </div>
            `);
        }).always(function() {
            $('#loadingIndicator').hide();
            $('#hasilLaporan').show();
        });
    }

    function renderLaporan(jenis, response) {
        if (jenis === 'pembayaran') {
            renderLaporanPembayaran(response);
        } else {
            renderLaporanTunggakan(response);
        }
    }

    function renderLaporanPembayaran(response) {
        let html = `
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Pembayaran</h6>
                            <h3 class="mb-0">Rp ${response.total.nominal.toLocaleString('id')}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Lunas</h6>
                            <h3 class="mb-0">${response.total.lunas} Pembayaran</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">Belum Lunas</h6>
                            <h3 class="mb-0">${response.total.tunggakan} Pembayaran</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Santri</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Bulan/Tahun</th>
                            <th>Nominal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>`;

        if (response.data.length > 0) {
            response.data.forEach(function(item) {
                html += `
                    <tr>
                        <td>${item.tanggal}</td>
                        <td>
                            ${item.nama_santri}<br>
                            <small class="text-muted">NISN: ${item.nisn}</small>
                        </td>
                        <td>${item.kelas}</td>
                        <td>${item.kategori}</td>
                        <td>${item.bulan}/${item.tahun}</td>
                        <td>Rp ${parseInt(item.nominal).toLocaleString('id')}</td>
                        <td>
                            <span class="badge bg-${item.status === 'success' ? 'success' : 'warning'}">
                                ${item.status === 'success' ? 'Lunas' : 'Belum Lunas'}
                            </span>
                        </td>
                    </tr>
                `;
            });
        } else {
            html += `
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            `;
        }

        html += `
                    </tbody>
                </table>
            </div>
        `;

        $('#hasilLaporan').html(html);
        $('#dataTable').DataTable();
    }

    function renderLaporanTunggakan(response) {
        let html = `
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Tunggakan</h6>
                            <h3 class="mb-0">Rp ${response.total.nominal.toLocaleString('id')}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">Jumlah Santri</h6>
                            <h3 class="mb-0">${response.total.santri} Santri</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Bulan</h6>
                            <h3 class="mb-0">${response.total.tunggakan} Bulan</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>Santri</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Wali</th>
                            <th>Jumlah Bulan</th>
                            <th>Total Tunggakan</th>
                        </tr>
                    </thead>
                    <tbody>`;

        if (response.data.length > 0) {
            response.data.forEach(function(item) {
                html += `
                    <tr>
                        <td>
                            ${item.nama}<br>
                            <small class="text-muted">NISN: ${item.nisn}</small>
                        </td>
                        <td>${item.kelas}</td>
                        <td>${item.kategori}</td>
                        <td>${item.wali}</td>
                        <td>${item.jumlah_tunggakan} Bulan</td>
                        <td>Rp ${parseInt(item.total_tunggakan).toLocaleString('id')}</td>
                    </tr>
                `;
            });
        } else {
            html += `
                <tr>
                    <td colspan="6" class="text-center">Tidak ada tunggakan</td>
                </tr>
            `;
        }

        html += `
                    </tbody>
                </table>
            </div>
        `;

        $('#hasilLaporan').html(html);
        $('#dataTable').DataTable();
    }

    // Load laporan saat halaman pertama kali dibuka
    loadLaporan();
});
</script>
@endpush
@endsection
