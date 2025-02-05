@extends('layouts.admin')

@section('title', 'Data Santri')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Data Santri
            @isset($currentKelas)
            - Kelas {{ $currentKelas['jenjang'] }} {{ $currentKelas['kelas'] }}
            @endisset
        </h1>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.santri.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus fa-sm"></i>
                Tambah Santri
            </a>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import fa-sm"></i>
                Import Data
            </button>
            <a href="{{ route('admin.santri.export') }}" class="btn btn-sm btn-info">
                <i class="fas fa-file-export fa-sm"></i>
                Export Data
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {!! nl2br(e(session('warning'))) !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Santri</h6>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-success" id="btnKenaikanKelas" style="display: none;" onclick="kenaikanKelas()">
                <i class="fas fa-level-up-alt fa-sm"></i>
                Naikan Kelas
            </button>
            <button type="button" class="btn btn-sm btn-warning" id="btnBatalKenaikan" style="display: none;" onclick="batalKenaikanKelas()">
                <i class="fas fa-undo fa-sm"></i>
                Batal Naik Kelas
            </button>
        </div>
    </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="checkAll" onclick="toggleCheckAll()">
                            </th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Wali</th>
                            <th>Status</th>
                            <th>Status SPP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($santri as $s)
                        <tr>
                            <td>
                                <input type="checkbox" class="santri-checkbox" value="{{ $s->id }}" onchange="toggleButtons()">
                            </td>
                            <td>{{ $s->nisn }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->jenjang }} {{ $s->kelas }}</td>
                            <td>{{ $s->kategori->nama ?? '-' }}</td>
                            <td>{{ $s->wali->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $s->status_color }}">
                                    {{ ucfirst($s->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $s->status_spp_color }}">
                                    {{ $s->status_spp }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.santri.edit', $s->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.santri.destroy', $s->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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

            {{ $santri->links() }}
        </div>
    </div>
</div>

@include('admin.santri.partials.modal-import')
@include('admin.santri.partials.modal-kenaikan-kelas')
@endsection

@push('scripts')
<script>
// Add CSRF token to all ajax requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function toggleCheckAll() {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.getElementsByClassName('santri-checkbox');
    
    Array.from(checkboxes).forEach(checkbox => {
        checkbox.checked = checkAll.checked;
    });
    
    toggleButtons();
}

function toggleButtons() {
    const checkboxes = document.getElementsByClassName('santri-checkbox');
    const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
    const btnKenaikanKelas = document.getElementById('btnKenaikanKelas');
    const btnBatalKenaikan = document.getElementById('btnBatalKenaikan');
    
    if (checkedBoxes.length > 0) {
        btnKenaikanKelas.style.display = 'inline-block';
        btnBatalKenaikan.style.display = 'inline-block';
    } else {
        btnKenaikanKelas.style.display = 'none';
        btnBatalKenaikan.style.display = 'none';
    }
}

function kenaikanKelas() {
    const modal = new bootstrap.Modal(document.getElementById('modalKenaikanKelas'));
    modal.show();
}

function prosesKenaikanKelas() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalKenaikanKelas'));
    const checkboxes = document.getElementsByClassName('santri-checkbox');
    const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
    const kelasTujuan = document.getElementById('kelas_tujuan').value;
    
    if (!kelasTujuan) {
        alert('Mohon isi kelas tujuan');
        return;
    }
    
    const santriIds = checkedBoxes.map(cb => cb.value);
    
    // Kirim request ke server
    $.ajax({
        url: "{{ route('admin.santri.kenaikan-kelas') }}",
        type: 'POST',
        data: {
            santri_ids: santriIds,
            kelas_tujuan: kelasTujuan
        },
        dataType: 'json'
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses kenaikan kelas');
    });
}

function batalKenaikanKelas() {
    if (!confirm('Apakah Anda yakin ingin membatalkan kenaikan kelas untuk santri yang dipilih?')) {
        return;
    }
    
    const checkboxes = document.getElementsByClassName('santri-checkbox');
    const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
    const santriIds = checkedBoxes.map(cb => cb.value);
    
    // Kirim request ke server
    $.ajax({
        url: "{{ route('admin.santri.batal-kenaikan-kelas') }}",
        type: 'POST',
        data: {
            santri_ids: santriIds
        },
        dataType: 'json'
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat membatalkan kenaikan kelas');
    });
}
</script>
@endpush
