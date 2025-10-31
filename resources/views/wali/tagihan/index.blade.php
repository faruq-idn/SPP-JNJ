@extends('layouts.wali')

@section('title', 'Tagihan & Riwayat SPP')


@section('content')
<div class="container-fluid p-2 p-md-4 mb-5">
    @include('layouts.partials.dropdown-santri')
    <div class="row g-2 g-md-3">
        <div class="col-12">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Riwayat Pembayaran per Tahun -->
            @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tahun {{ $tahun }}</h5>
                    <button type="button"
                            class="btn btn-sm btn-secondary btn-print-year"
                            data-url="{{ route('wali.pembayaran.tahun.pdf', ['santri' => $santri->id, 'tahun' => $tahun]) }}"
                            data-year="{{ $tahun }}">
                        <i class="fas fa-print me-1"></i> Cetak Tahun
                    </button>
                </div>
                <!-- Riwayat Tabel (Responsive) -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Bulan</th>
                                <th>Status</th>
                                <th>Nominal</th>
                                <th>Tanggal Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembayaranBulanan as $pembayaran)
                            <tr style="cursor: pointer" onclick="showDetailPembayaran({{ $pembayaran->id }}, '{{ $pembayaran->nama_bulan }}', {{ $pembayaran->nominal }}, '{{ $pembayaran->status }}', '{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-' }}', '{{ $pembayaran->metode_pembayaran ? $pembayaran->metode_pembayaran->nama : '-' }}', '{{ $pembayaran->tahun }}')">
                                <td>{{ $pembayaran->nama_bulan }}</td>
                                <td>
                                    <span class="badge bg-{{ $pembayaran->status == 'success' ? 'success' : ($pembayaran->status == 'pending' ? 'warning' : 'danger') }}">
                                        @if($pembayaran->status == 'success')
                                            Lunas
                                        @elseif($pembayaran->status == 'pending')
                                            Pending
                                        @else
                                            Belum Lunas
                                        @endif
                                    </span>
                                </td>
                                <td>Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                                <td>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
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

@include('layouts.partials.modal-detail-pembayaran')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-print-year').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const tahun = this.getAttribute('data-year');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Cetak Riwayat Tahunan',
                    html: `Anda akan mencetak riwayat pembayaran tahun <b>${tahun}</b>. Lanjutkan?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Cetak PDF',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) window.open(url, '_blank');
                });
            } else {
                window.open(url, '_blank');
            }
        });
    });
});
</script>
@endpush
