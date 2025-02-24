@extends('layouts.petugas')

@section('title', 'Detail Santri')

@push('styles')
<!-- Select2 -->
<link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('vendor/select2/css/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" />
@endpush

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

                @include('shared.santri._show_table')
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
    @include('shared.santri._show_modal')
@endsection
