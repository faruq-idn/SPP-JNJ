@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pembayaran (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPembayaranBulanIni, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Santri Lunas (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $santriLunas }} Santri</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Tunggakan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Santri Nunggak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $santriNunggak }} Santri</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Cards -->
    <div class="row mb-4">
        <!-- Card Pembayaran -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold">Laporan Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form id="formPembayaran" action="{{ route('admin.laporan.pembayaran') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <div class="row g-2">
                                <div class="col-12 mb-2">
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-outline-secondary" onclick="setPeriode('bulan-ini')">
                                            Bulan Ini
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="setPeriode('bulan-lalu')">
                                            Bulan Lalu
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="setPeriode('tahun-ini')">
                                            Tahun Ini
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Lunas</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary mb-2">
                                <i class="fas fa-search me-1"></i>Tampilkan
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-primary" onclick="exportReport('pdf')">
                                    <i class="fas fa-file-pdf me-1"></i>PDF
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="exportReport('excel')">
                                    <i class="fas fa-file-excel me-1"></i>Excel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Card Tunggakan -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="m-0 font-weight-bold">Laporan Tunggakan</h5>
                </div>
                <div class="card-body">
                    <form id="formTunggakan" action="{{ route('admin.laporan.tunggakan') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Jenjang</label>
                            <select name="jenjang" id="jenjang" class="form-select">
                                <option value="">Semua Jenjang</option>
                                <option value="SMP" {{ request('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ request('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select name="kelas" id="kelas" class="form-select" disabled>
                                <option value="">Semua Kelas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Minimal Tunggakan (Bulan)</label>
                            <input type="number" name="min_tunggakan" class="form-control" min="1" max="12" value="{{ request('min_tunggakan') }}">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger mb-2">
                                <i class="fas fa-search me-1"></i>Tampilkan
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-danger" onclick="exportTunggakan('pdf')">
                                    <i class="fas fa-file-pdf me-1"></i>PDF
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="exportTunggakan('excel')">
                                    <i class="fas fa-file-excel me-1"></i>Excel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Cards -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="pembayaranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
.chart-area, .chart-pie {
    position: relative;
    height: 300px;
    width: 100%;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
// Populate kelas based on jenjang
const kelasOptions = {
    'SMP': ['7A', '7B', '8A', '8B', '9A', '9B'],
    'SMA': ['10A', '10B', '11A', '11B', '12A', '12B']
};

const currentKelas = '{{ request('kelas') }}';
const currentJenjang = '{{ request('jenjang') }}';

function populateKelas() {
    const jenjang = $('#jenjang').val();
    const kelasSelect = $('#kelas');
    kelasSelect.prop('disabled', !jenjang);
    
    let options = '<option value="">Semua Kelas</option>';
    if (jenjang && kelasOptions[jenjang]) {
        options += kelasOptions[jenjang]
            .map(k => `<option value="${k}"${k === currentKelas ? ' selected' : ''}>${k}</option>`)
            .join('');
    }
    kelasSelect.html(options);
}

$('#jenjang').on('change', populateKelas);

// Initialize kelas on page load if jenjang is selected
if (currentJenjang) {
    populateKelas();
}

function exportReport(type) {
    const form = $('#formPembayaran');
    const action = type === 'pdf' 
        ? '{{ route('admin.laporan.export.pembayaran') }}?export=pdf'
        : '{{ route('admin.laporan.export.pembayaran') }}?export=excel';
    form.attr('action', action);
    form.submit();
}

function exportTunggakan(type) {
    const form = $('#formTunggakan');
    const action = type === 'pdf'
        ? '{{ route('admin.laporan.export.tunggakan') }}?export=pdf'
        : '{{ route('admin.laporan.export.tunggakan') }}?export=excel';
    form.attr('action', action);
    form.submit();
}

// Set periode laporan
function setPeriode(tipe) {
    const now = new Date();
    let tanggalAwal, tanggalAkhir;
    
    switch(tipe) {
        case 'bulan-ini':
            tanggalAwal = new Date(now.getFullYear(), now.getMonth(), 1);
            tanggalAkhir = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            break;
        case 'bulan-lalu':
            tanggalAwal = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            tanggalAkhir = new Date(now.getFullYear(), now.getMonth(), 0);
            break;
        case 'tahun-ini':
            tanggalAwal = new Date(now.getFullYear(), 0, 1);
            tanggalAkhir = new Date(now.getFullYear(), 11, 31);
            break;
    }
    
    document.getElementById('tanggal_awal').value = tanggalAwal.toISOString().split('T')[0];
    document.getElementById('tanggal_akhir').value = tanggalAkhir.toISOString().split('T')[0];
}

// Form validation
$('#formPembayaran').on('submit', function(e) {
    const tanggalAwal = $('input[name="tanggal_awal"]').val();
    const tanggalAkhir = $('input[name="tanggal_akhir"]').val();

    if (!tanggalAwal || !tanggalAkhir) {
        e.preventDefault();
        Swal.fire('Error', 'Silakan pilih periode tanggal', 'error');
        return false;
    }

    if (tanggalAwal > tanggalAkhir) {
        e.preventDefault();
        Swal.fire('Error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir', 'error');
        return false;
    }
    return true;
});

// Chart Pembayaran
const pembayaranCtx = document.getElementById('pembayaranChart');
new Chart(pembayaranCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [{
            label: 'Total Pembayaran',
            data: {!! json_encode($chartData['data']) !!},
            borderColor: '#4e73df',
            backgroundColor: '#4e73df20',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            },
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Total Pembayaran per Bulan'
            }
        }
    }
});

// Chart Kategori
const kategoriCtx = document.getElementById('kategoriChart');
new Chart(kategoriCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($chartKategori['labels']) !!},
        datasets: [{
            data: {!! json_encode($chartKategori['data']) !!},
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Persentase Pelunasan per Kategori'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed.toFixed(1) + '%';
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection
