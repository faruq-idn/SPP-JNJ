@extends('layouts.admin')

@section('title', 'Laporan Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Pembayaran</h1>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.laporan.pembayaran', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => request('kategori'), 'export' => 'excel']) }}" 
               class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('admin.laporan.pembayaran', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => request('kategori'), 'export' => 'pdf']) }}" 
               class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <!-- Info Filter Card -->
        <div>
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-0">
                            Filter Aktif:
                            <span class="text-primary">
                                Bulan: {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }},
                                Kategori: {{ request('kategori') ? \App\Models\KategoriSantri::find(request('kategori'))->nama : 'Semua' }},
                                Status: {{ ucfirst(request('status', 'aktif')) }}
                               {{ request('jenjang') ? ', Jenjang ' . request('jenjang') : '' }}
                               {{ request('kelas') ? ', Kelas ' . request('kelas') : '' }}
                           </span>
                       </h4>
                   </div>
                </div>
            </div>
        </div>
        <div class="card-header py-3">
            <form method="GET" action="{{ route('admin.laporan.pembayaran') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ sprintf('%02d', $i) }}" 
                                {{ $bulan == sprintf('%02d', $i) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        @php $currentYear = date('Y'); @endphp
                        @for($year = $currentYear - 1; $year <= $currentYear + 1; $year++)
                            <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori_list as $k)
                            <option value="{{ $k->id }}" 
                                {{ request('kategori') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status Santri</label>
                    <select class="form-select" name="status" id="status">
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="col-md-3 filter-aktif">
                    <label for="jenjang" class="form-label">Jenjang</label>
                    <select class="form-select" name="jenjang" id="jenjang" {{ request('status') == 'lulus' ? 'disabled' : '' }}>
                        <option value="">Semua Jenjang</option>
                        <option value="SMP" {{ request('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ request('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    </select>
                </div>
                <div class="col-md-3 filter-aktif">
                    <label for="kelas" class="form-label">Kelas</label>
                    <select class="form-select" name="kelas" id="kelas" {{ request('status') == 'lulus' ? 'disabled' : '' }}>
                        <option value="">Semua Kelas</option>
                        @if(request('jenjang') == 'SMP')
                            <option value="7A" {{ request('kelas') == '7A' ? 'selected' : '' }}>7A</option>
                            <option value="7B" {{ request('kelas') == '7B' ? 'selected' : '' }}>7B</option>
                            <option value="8A" {{ request('kelas') == '8A' ? 'selected' : '' }}>8A</option>
                            <option value="8B" {{ request('kelas') == '8B' ? 'selected' : '' }}>8B</option>
                            <option value="9A" {{ request('kelas') == '9A' ? 'selected' : '' }}>9A</option>
                            <option value="9B" {{ request('kelas') == '9B' ? 'selected' : '' }}>9B</option>
                        @elseif(request('jenjang') == 'SMA')
                            <option value="10A" {{ request('kelas') == '10A' ? 'selected' : '' }}>10A</option>
                            <option value="10B" {{ request('kelas') == '10B' ? 'selected' : '' }}>10B</option>
                            <option value="11A" {{ request('kelas') == '11A' ? 'selected' : '' }}>11A</option>
                            <option value="11B" {{ request('kelas') == '11B' ? 'selected' : '' }}>11B</option>
                            <option value="12A" {{ request('kelas') == '12A' ? 'selected' : '' }}>12A</option>
                            <option value="12B" {{ request('kelas') == '12B' ? 'selected' : '' }}>12B</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.laporan.pembayaran') }}" class="btn btn-secondary mt-2 d-block w-100">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>NISN</th>
                            <th>Nama Santri</th>
                            <th>Kategori</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                            <td>{{ $p->santri->nisn }}</td>
                            <td>{{ $p->santri->nama }}</td>
                            <td>{{ $p->santri->kategori->nama }}</td>
                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td>{{ $p->metode_pembayaran->nama ?? 'Manual' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total:</th>
                            <th colspan="2">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('vendor/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/datatables/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Handle status change
    $('#status').on('change', function() {
        const status = $(this).val();
        const jenjangSelect = $('#jenjang');
        const kelasSelect = $('#kelas');
        const filterAktif = $('.filter-aktif');
        
        if (status === 'lulus' || status === 'keluar') {
            jenjangSelect.prop('disabled', true).val('');
            kelasSelect.prop('disabled', true).val('');
            filterAktif.addClass('opacity-50');
        } else {
            jenjangSelect.prop('disabled', false);
            kelasSelect.prop('disabled', false);
            filterAktif.removeClass('opacity-50');
        }
    });

    // Handle jenjang change
    $('#jenjang').on('change', function() {
        const jenjang = $(this).val();
        const kelasSelect = $('#kelas');
        kelasSelect.empty().append('<option value="">Semua Kelas</option>');
        
        if (jenjang === 'SMP') {
            const kelasSMP = ['7A', '7B', '8A', '8B', '9A', '9B'];
            kelasSMP.forEach(kelas => {
                kelasSelect.append(`<option value="${kelas}">${kelas}</option>`);
            });
        } else if (jenjang === 'SMA') {
            const kelasSMA = ['10A', '10B', '11A', '11B', '12A', '12B'];
            kelasSMA.forEach(kelas => {
                kelasSelect.append(`<option value="${kelas}">${kelas}</option>`);
            });
        }
    });
});

</script>
@endpush
