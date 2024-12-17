@extends('layouts.wali')

@section('title', 'Tagihan SPP')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-file-invoice-dollar me-2"></i>Tagihan SPP
            </h2>

            @if(!$santri)
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        Data santri tidak ditemukan. Silakan hubungi admin untuk informasi lebih lanjut.
                    </div>
                </div>
            @else
                <!-- Info Santri -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-user-graduate text-primary fa-2x"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="card-title mb-0">{{ $santri->nama }}</h5>
                                <p class="card-text text-muted mb-0">
                                    NIS: {{ $santri->nis }} | Kelas: {{ $santri->kelas }}
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Kategori: {{ $santri->kategori->nama ?? '-' }}
                                        (Rp {{ number_format($santri->kategori->tarif ?? 0, 0, ',', '.') }}/bulan)
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Tunggakan -->
                @if($total_tunggakan > 0)
                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                        <div class="bg-warning bg-opacity-25 p-2 rounded me-3">
                            <i class="fas fa-exclamation-circle text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">Total Tunggakan</h6>
                            <p class="mb-0">
                                Rp {{ number_format($total_tunggakan, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Tabel Tagihan -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Rincian Tagihan {{ date('Y') }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tagihan as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $item['bulan'] }}</div>
                                                <small class="text-muted">{{ $item['tahun'] }}</small>
                                            </td>
                                            <td>Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $item['status_class'] }}">
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($item['status'] === 'Belum Lunas')
                                                    <button class="btn btn-sm btn-primary" onclick="bayarSPP('{{ $item['bulan'] }}')">
                                                        <i class="fas fa-money-bill-wave me-1"></i>Bayar
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-success" disabled>
                                                        <i class="fas fa-check me-1"></i>Lunas
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pembayaran -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informasi Pembayaran
                        </h5>
                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading">Cara Pembayaran:</h6>
                            <ol class="mb-0">
                                <li>Klik tombol "Bayar" pada bulan yang ingin dibayar</li>
                                <li>Pilih metode pembayaran yang tersedia</li>
                                <li>Ikuti instruksi pembayaran yang muncul</li>
                                <li>Pembayaran akan diverifikasi secara otomatis</li>
                                <li>Status akan berubah menjadi "Lunas" jika pembayaran berhasil</li>
                            </ol>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function bayarSPP(bulan) {
    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        text: `Anda akan membayar SPP untuk bulan ${bulan}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Bayar Sekarang',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementasi pembayaran akan ditambahkan nanti
            Swal.fire(
                'Info',
                'Fitur pembayaran akan segera tersedia',
                'info'
            );
        }
    });
}
</script>
@endpush
@endsection
