@extends('layouts.admin')

@section('title', 'Kategori Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kategori Santri</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createKategoriModal">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>
    </div>

    <div id="alertContainer">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Keterangan</th>
                            <th>Biaya Makan</th>
                            <th>Biaya Asrama</th>
                            <th>Biaya Listrik</th>
                            <th>Biaya Kesehatan</th>
                            <th>Total SPP</th>
                            <th>Berlaku Mulai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $k)
                            <tr>
                                <td>{{ $k->nama }}</td>
                                <td>{{ $k->keterangan }}</td>
                                <td>Rp {{ number_format($k->biaya_makan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($k->biaya_asrama, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($k->biaya_listrik, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($k->biaya_kesehatan, 0, ',', '.') }}</td>
                                <td>
                                    @if($k->tarifTerbaru)
                                        Rp {{ number_format($k->tarifTerbaru->nominal, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted">Belum diatur</span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->tarifTerbaru)
                                        {{ $k->tarifTerbaru->berlaku_mulai->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button"
                                            class="btn btn-sm btn-warning"
                                            onclick="editKategori({{ $k->id }})">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.kategori.destroy', $k) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return {{ $k->nama === 'Reguler' ? 'confirmDeleteReguler(event)' : 'confirmDelete(event)' }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data kategori</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.kategori.modals.create-edit')
@include('admin.kategori.modals.tarif')

@push('scripts')
<script src="{{ asset('js/kategori.js') }}"></script>
@endpush
@endsection
