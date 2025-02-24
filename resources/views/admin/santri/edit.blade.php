@extends('layouts.admin')

@once
@push('before-styles')
<!-- Force browser to reload the page -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endpush
@endonce

@section('title', 'Edit Santri')

@push('styles')
<link href="/vendor/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="/vendor/select2/css/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
@php
\Illuminate\Support\Facades\Log::info('Edit view rendering', [
    'santri_id' => $santri->id ?? 'not set',
    'view_path' => 'admin.santri.edit'
]);
@endphp
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Santri</h1>
        <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i>
            Kembali
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.santri.update', $santri->id) }}" method="POST" id="editSantriForm">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <!-- NISN -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                   id="nisn" name="nisn" value="{{ old('nisn', $santri->nisn) }}" required>
                            @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Nama -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   id="nama" name="nama" value="{{ old('nama', $santri->nama) }}" required>
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                    id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="L" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                   id="tanggal_lahir" name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir', $santri->tanggal_lahir?->format('Y-m-d')) }}" required>
                            @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      id="alamat" name="alamat" rows="3" required>{{ old('alamat', $santri->alamat) }}</textarea>
                            @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Jenjang -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jenjang" class="form-label">Jenjang <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenjang') is-invalid @enderror"
                                    id="jenjang" name="jenjang" required>
                                <option value="SMP" {{ old('jenjang', $santri->jenjang) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('jenjang', $santri->jenjang) == 'SMA' ? 'selected' : '' }}>SMA</option>
                            </select>
                            @error('jenjang')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kelas -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select class="form-select @error('kelas') is-invalid @enderror"
                                    id="kelas" name="kelas" required>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                            @error('kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                   id="tanggal_masuk" name="tanggal_masuk" 
                                   value="{{ old('tanggal_masuk', $santri->tanggal_masuk?->format('Y-m-d')) }}" required>
                            @error('tanggal_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori_id') is-invalid @enderror"
                                    id="kategori_id" name="kategori_id" required>
                                @foreach($kategori_santri as $kategori)
                                <option value="{{ $kategori->id }}"
                                        {{ old('kategori_id', $santri->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Wali -->
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="wali_id" class="form-label">Wali Santri <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('wali_id') is-invalid @enderror"
                                    id="wali_id" name="wali_id" required>
                                @if($santri->wali)
                                    <option value="{{ $santri->wali_id }}" selected>
                                        {{ $santri->wali->name }} ({{ $santri->wali->email }})
                                    </option>
                                @endif
                            </select>
                            @error('wali_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="aktif" {{ old('status', $santri->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="lulus" {{ old('status', $santri->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="keluar" {{ old('status', $santri->status) == 'keluar' ? 'selected' : '' }}>Keluar</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/vendor/select2/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk wali santri
    $('#wali_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Wali Santri',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.users.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    type: 'wali'
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        minimumInputLength: 2
    });

    // Fungsi untuk memperbarui opsi kelas berdasarkan jenjang
    function updateKelasOptions() {
        const jenjang = $('#jenjang').val();
        const currentKelas = '{{ $santri->kelas }}';
        let options = [];

        if (jenjang === 'SMP') {
            options = ['7A', '7B', '8A', '8B', '9A', '9B'];
        } else {
            options = ['10A', '10B', '11A', '11B', '12A', '12B'];
        }

        const kelasSelect = $('#kelas');
        kelasSelect.empty();

        options.forEach(kelas => {
            kelasSelect.append(new Option(kelas, kelas, false, kelas === currentKelas));
        });
    }

    // Event listener untuk perubahan jenjang
    $('#jenjang').on('change', updateKelasOptions);

    // Inisialisasi opsi kelas
    updateKelasOptions();

    // Form validation
    $('#editSantriForm').on('submit', function(e) {
        let isValid = true;
        
        // Remove previous validation styles
        $('.is-invalid').removeClass('is-invalid');
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Mohon lengkapi semua field yang wajib diisi',
                confirmButtonClass: 'btn btn-primary'
            });
        }
    });
});
</script>
@endpush
