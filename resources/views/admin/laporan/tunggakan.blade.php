@extends('layouts.admin')

@section('title', 'Laporan Tunggakan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Tunggakan</h1>
        <div class="btn-group">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('admin.laporan.tunggakan', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i>PDF
            </a>
            <a href="{{ route('admin.laporan.tunggakan', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i>Excel
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow mb-4">
        <!-- Summary Card -->
        <div class="row mb-4">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Santri Nunggak</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($santri) }} Santri</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-times fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tunggakan Tertinggi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                   {{ $santri->max('jumlah_bulan_tunggakan') }} Bulan
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Info Filter Card -->
        <div>
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-0">
                            Filter Aktif:
                            <span class="text-primary">
                                @if(request('status'))
                                    Status: {{ ucfirst(request('status')) }}
                                @else
                                    Status: Semua Status
                                @endif
                                @if(request('status') == 'aktif' || !request('status'))
                                    {{ request('jenjang') ? ', Jenjang ' . request('jenjang') : '' }}
                                    {{ request('kelas') ? ', Kelas ' . request('kelas') : '' }}
                                @endif
                            </span>
                        </h4>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <h5 class="mb-0">
                            Total Tunggakan: <span class="text-danger">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Form --}}
        <div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.laporan.tunggakan') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Santri</label>
                        <select class="form-select" name="status" id="status">
                            <option value="">Semua Status</option>
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
                        <button type="submit" class="btn btn-primary me-2 mt-4">Filter</button>
                        <a href="{{ route('admin.laporan.tunggakan') }}" class="btn btn-secondary mt-4">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Santri</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Status Santri</th>
                            <th>Wali Santri</th>
                            <th>No HP</th>
                            <th>Jumlah Bulan</th>
                            <th>Total Tunggakan</th>
                            <th>Bulan Tunggakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($santri as $index => $s)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ str_pad($s->nisn, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->jenjang }} {{ $s->kelas }}</td>
                            <td>{{ $s->kategori->nama }}</td>
                            <td>
                                <span class="badge bg-{{ $s->status == 'aktif' ? 'success' : ($s->status == 'lulus' ? 'info' : 'danger') }}">
                                    {{ ucfirst($s->status) }}
                                </span>
                            </td>
                            <td>{{ $s->wali->name ?? '-' }}</td>
                            <td>{{ $s->wali->no_hp ?? '-' }}</td>
                            <td>{{ $s->jumlah_bulan_tunggakan }} bulan</td>
                            <td>Rp {{ number_format($s->total_tunggakan, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $bulanTunggakan = $s->pembayaran
                                        ->filter(function($p) {
                                            return in_array($p->status, ['failed', 'pending', 'unpaid']) && $p->nominal > 0;
                                        })
                                        ->sortBy(function($p) {
                                            return sprintf('%04d%02d', $p->tahun, $p->bulan);
                                        })
                                        ->map(function($p) {
                                            return Carbon\Carbon::create($p->tahun, $p->bulan)
                                                ->translatedFormat('F Y');
                                        })->values();
                                @endphp
                                <small class="d-block text-danger">
                                    {{ $bulanTunggakan->implode(', ') }}
                                </small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="{{ asset('vendor/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/datatables/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        language: {
            url: '{{ asset("vendor/datatables/i18n/id.json") }}'
        },
        order: [[8, 'desc']] // Sort by jumlah bulan descending
    });

    function handleStatusChange(status) {
        const jenjangSelect = $('#jenjang');
        const kelasSelect = $('#kelas');
        const filterAktif = $('.filter-aktif');
        
        if (status === 'aktif' || status === '') {
            jenjangSelect.prop('disabled', false);
            kelasSelect.prop('disabled', false);
            filterAktif.removeClass('opacity-50');
        } else {
            jenjangSelect.prop('disabled', true).val('');
            kelasSelect.prop('disabled', true).val('');
            filterAktif.addClass('opacity-50');
        }
    }

    // Handle status change event
    $('#status').on('change', function() {
        handleStatusChange($(this).val());
    });

    // Run on page load
    handleStatusChange($('#status').val());

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
@endsection
