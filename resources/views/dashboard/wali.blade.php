@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Dashboard Wali Santri</h2>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Informasi Santri
                        </div>
                        <div class="card-body">
                            @if($santri)
                            <table class="table">
                                <tr>
                                    <th>Nama Santri</th>
                                    <td>{{ $santri->nama }}</td>
                                </tr>
                                <tr>
                                    <th>NISN</th>
                                    <td>{{ $santri->nisn }}</td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td>{{ $santri->kelas }}</td>
                                </tr>
                                {{-- Status SPP akan diimplementasikan nanti
                                <tr>
                                    <th>Status SPP</th>
                                    <td>
                                        <span class="badge bg-{{ $santri->status_spp == 'Lunas' ? 'success' : 'warning' }}">
                                            {{ $santri->status_spp ?? 'Belum ada data' }}
                                        </span>
                                    </td>
                                </tr>
                                --}}
                            </table>
                            @else
                            <p>Data santri belum tersedia</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Fitur tagihan akan diimplementasikan nanti
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Tagihan SPP
                        </div>
                        <div class="card-body">
                            <h3>Rp {{ number_format($tagihan ?? 0) }}</h3>
                            <a href="{{ route('pembayaran.create') }}" class="btn btn-primary mt-3">
                                Bayar SPP
                            </a>
                        </div>
                    </div>
                </div>
                --}}
            </div>

            {{-- Riwayat pembayaran akan diimplementasikan nanti
            <div class="card mt-4">
                <div class="card-header">
                    Riwayat Pembayaran
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Bulan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data riwayat pembayaran -->
                        </tbody>
                    </table>
                </div>
            </div>
            --}}
        </div>
    </div>
</div>
@endsection
