@extends('layouts.wali')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-history me-2"></i>Riwayat Pembayaran
            </h2>

            @if(!$santri)
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        Data santri tidak ditemukan. Silakan hubungi admin untuk informasi lebih lanjut.
                    </div>
                </div>
            @else
                <!-- Santri Selector -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('wali.change-santri') }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            <div class="me-3">
                                <i class="fas fa-users text-primary fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <label for="santri_id" class="form-label mb-0">Pilih Santri:</label>
                                <select name="santri_id" id="santri_id" class="form-select" onchange="this.form.submit()">
                                    @foreach($santri_list as $s)
                                        <option value="{{ $s->id }}" {{ $santri->id == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama }} ({{ $s->nisn }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Riwayat Pembayaran Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Daftar Pembayaran
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Bulan</th>
                                    <th>Nominal</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayaran as $p)
                                    <tr>
                                        <td>
                                            @if($p->tanggal_bayar)
                                                {{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $bulan = str_pad($p->bulan, 2, '0', STR_PAD_LEFT);
                                                $namaBulan = \Carbon\Carbon::createFromFormat('m', $bulan)->isoFormat('MMMM Y');
                                            @endphp
                                            {{ $namaBulan }}
                                        </td>
                                        <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                        <td>
                                            @if($p->metode_pembayaran)
                                                <span class="badge bg-info">
                                                    {{ $p->metode_pembayaran->nama }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $p->status == 'success' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($p->status == 'success')
                                                <button class="btn btn-sm btn-primary" onclick="showDetail('{{ $p->id }}')">
                                                    <i class="fas fa-eye me-1"></i>Detail
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-warning" onclick="bayarSekarang('{{ $p->id }}')">
                                                    <i class="fas fa-money-bill me-1"></i>Bayar
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">
                                            <i class="fas fa-info-circle me-2"></i>Belum ada riwayat pembayaran
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($pembayaran->hasPages())
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-flex justify-content-end">
                                {{ $pembayaran->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDetail(id) {
    Swal.fire({
        title: 'Info',
        text: 'Detail pembayaran akan segera tersedia',
        icon: 'info'
    });
}

function bayarSekarang(id) {
    Swal.fire({
        title: 'Info',
        text: 'Fitur pembayaran akan segera tersedia',
        icon: 'info'
    });
}
</script>
@endpush
@endsection
