@extends('layouts.admin')

@section('title', 'Data Santri')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .table tbody tr {
        cursor: pointer;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Santri</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-success" onclick="kenaikanKelas()">
                <i class="fas fa-graduation-cap fa-sm me-1"></i>
                Kenaikan Kelas
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import fa-sm me-1"></i>
                Import Data
            </button>
            <a href="{{ route('admin.santri.create') }}" class="btn btn-primary">
                <i class="fas fa-plus fa-sm me-1"></i>
                Tambah Santri
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Data Santri -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Santri</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($santri as $s)
                        <tr>
                            <td onclick="window.location='{{ route('admin.santri.show', $s->id) }}'">{{ $s->nisn }}</td>
                            <td onclick="window.location='{{ route('admin.santri.show', $s->id) }}'">{{ $s->nama }}</td>
                            <td onclick="window.location='{{ route('admin.santri.show', $s->id) }}'">{{ $s->jenjang }} {{ $s->kelas }}</td>
                            <td onclick="window.location='{{ route('admin.santri.show', $s->id) }}'">{{ $s->kategori->nama ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $s->status_color }}">
                                    {{ ucfirst($s->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.santri.show', $s->id) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.santri.edit', $s->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.santri.destroy', $s->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.santri.partials.modal-import')
@include('admin.santri.partials.modal-kenaikan-kelas')
@endsection

@push('scripts')
<script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/kenaikan-kelas.js') }}"></script>
@endpush
