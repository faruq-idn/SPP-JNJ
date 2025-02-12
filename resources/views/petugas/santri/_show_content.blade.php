@extends('layouts.admin')

@section('title', 'Detail Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Santri</h1>
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Data Santri -->
        <div class="col-md-6 mb-4">
            @include('shared.santri._data_santri')
        </div>

        <!-- Data Wali & Kategori -->
        <div class="col-md-6 mb-4">
            @include('shared.santri._wali_info')

            @include('shared.santri._kategori_tarif')
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="col-12">
        <div class="card shadow-sm">
            @include('shared.santri._riwayat_header')

            @include('petugas.santri._show_table')
            
        </div>
        </div>
    </div>
</div>
@endsection

@include('petugas.santri._show_modal')
