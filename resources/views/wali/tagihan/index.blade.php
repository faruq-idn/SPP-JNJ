@extends('layouts.wali')

@section('title', 'Tagihan & Riwayat SPP')

@include('layouts.partials.dropdown-santri')

@section('content')
<div class="container-fluid p-2 p-md-4 mb-5">
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
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Tahun {{ $tahun }}</h5>
                </div>
                <!-- Mobile View -->
                <div class="d-block d-md-none">
                    <div class="vstack gap-2">
                        @foreach($pembayaranBulanan as $pembayaran)
                        <div class="card border-0 bg-light shadow-sm"
                             style="cursor: pointer"
                             onclick="showDetailPembayaran({{ $pembayaran->id }}, '{{ $pembayaran->nama_bulan }}', {{ $pembayaran->nominal }}, '{{ $pembayaran->status }}', '{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y H:i') : '-' }}', '{{ $pembayaran->metode_pembayaran ? $pembayaran->metode_pembayaran->nama : '-' }}', '{{ $pembayaran->tahun }}')">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="vstack gap-1">
                                        <span class="fw-bold fs-6">{{ $pembayaran->nama_bulan }}</span>
                                        <span class="badge bg-{{ $pembayaran->status == 'success' ? 'success' : ($pembayaran->status == 'pending' ? 'warning' : 'danger') }} fs-7">
                                            @if($pembayaran->status == 'success')
                                                Lunas
                                            @elseif($pembayaran->status == 'pending')
                                                Pending
                                            @else
                                                Belum Lunas
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-primary fs-7">
                                            Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}
                                        </div>
                                        @if($pembayaran->tanggal_bayar)
                                        <small class="text-muted fs-7">
                                            {{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Desktop View -->
                <div class="table-responsive d-none d-md-block">
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
