@extends('layouts.admin')

@section('title', 'Edit Santri')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-2 text-gray-800">Edit Santri</h1>
            <p class="mb-4">Form untuk mengubah data santri</p>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.santri.update', $santri->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" class="form-control @error('nisn') is-invalid @enderror" 
                                   id="nisn" name="nisn" value="{{ old('nisn', $santri->nisn) }}">
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama', $santri->nama) }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                    id="jenis_kelamin" name="jenis_kelamin">
                                <option value="L" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   id="tanggal_lahir" name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir', $santri->tanggal_lahir->format('Y-m-d')) }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="3">{{ old('alamat', $santri->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jenjang" class="form-label">Jenjang</label>
                            <select class="form-select @error('jenjang') is-invalid @enderror" 
                                    id="jenjang" name="jenjang">
                                <option value="SMP" {{ old('jenjang', $santri->jenjang) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('jenjang', $santri->jenjang) == 'SMA' ? 'selected' : '' }}>SMA</option>
                            </select>
                            @error('jenjang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select class="form-select @error('kelas') is-invalid @enderror" 
                                    id="kelas" name="kelas">
                            </select>
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                   id="tanggal_masuk" name="tanggal_masuk" 
                                   value="{{ old('tanggal_masuk', $santri->tanggal_masuk->format('Y-m-d')) }}">
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select class="form-select @error('kategori_id') is-invalid @enderror" 
                                    id="kategori_id" name="kategori_id">
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

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="wali_id" class="form-label">Wali Santri</label>
                            <select class="form-control select2-wali @error('wali_id') is-invalid @enderror" 
                                    id="wali_id" name="wali_id">
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

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status">
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

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Setup Select2 untuk wali santri
    $('.select2-wali').select2({
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

    // Update opsi kelas berdasarkan jenjang
    function updateKelasOptions() {
        const jenjang = $('#jenjang').val();
        const currentKelas = '{{ $santri->kelas }}';
        let options = '';

        if (jenjang === 'SMP') {
            ['7A', '7B', '8A', '8B', '9A', '9B'].forEach(kelas => {
                options += `<option value="${kelas}" ${currentKelas === kelas ? 'selected' : ''}>${kelas}</option>`;
            });
        } else {
            ['10A', '10B', '11A', '11B', '12A', '12B'].forEach(kelas => {
                options += `<option value="${kelas}" ${currentKelas === kelas ? 'selected' : ''}>${kelas}</option>`;
            });
        }

        $('#kelas').html(options);
    }

    // Event listener untuk perubahan jenjang
    $('#jenjang').change(updateKelasOptions);

    // Initialize kelas options
    updateKelasOptions();
});
</script>
@endpush
