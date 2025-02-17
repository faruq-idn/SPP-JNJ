@extends('layouts.admin')

@section('title', 'Detail Santri')

@push('styles')
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Santri</h1>
        <div class="d-flex gap-2">
            @if(auth()->user()->role === 'admin')
                <button type="button"
                        class="btn btn-warning d-flex align-items-center gap-2"
                        data-bs-toggle="modal"
                        data-bs-target="#santriFormModal"
                        data-mode="edit"
                        data-id="{{ $santri->id }}">
                    <i class="fas fa-edit"></i> Edit Data
                </button>
            @endif
            <a href="{{ url()->previous() }}"
               class="btn btn-secondary d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Data Santri & Wali-->
        <div class="col-md-6 mb-4">
            @include('shared.santri._data_santri')

            @include('shared.santri._wali_info')
        </div>

        <!-- Kategori -->
        <div class="col-md-6 mb-4">
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
    @include('admin.santri._form_modal')
@endsection
