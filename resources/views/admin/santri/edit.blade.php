@extends('layouts.admin')

@section('title', 'Edit Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Santri</h1>
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.santri.update', $santri) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Data Pribadi -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Data Pribadi</h5>

                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                id="nisn" name="nisn" value="{{ old('nisn', $santri->nisn) }}" required>
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                id="nama" name="nama" value="{{ old('nama', $santri->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin"
                                        id="jk_l" value="L" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'L' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jk_l">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin"
                                        id="jk_p" value="P" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'P' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jk_p">Perempuan</label>
                                </div>
                            </div>
                            @error('jenis_kelamin')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                id="tanggal_lahir" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $santri->tanggal_lahir->format('Y-m-d')) }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                id="alamat" name="alamat" rows="3" required>{{ old('alamat', $santri->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Data Akademik -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Data Akademik</h5>

                        <div class="mb-3">
                            <label for="wali_id" class="form-label">Wali Santri</label>
                            <select class="form-select select2-wali @error('wali_id') is-invalid @enderror"
                                id="wali_id" name="wali_id" required>
                                <option value="">Pilih Wali Santri</option>
                                @if($santri->wali_id)
                                    <option value="{{ $santri->wali_id }}" selected>
                                        {{ $santri->wali->name }} ({{ $santri->wali->email }})
                                    </option>
                                @endif
                            </select>
                            <div id="wali_info" class="mt-2 small"></div>
                            @error('wali_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                id="tanggal_masuk" name="tanggal_masuk"
                                value="{{ old('tanggal_masuk', $santri->tanggal_masuk->format('Y-m-d')) }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jenjang" class="form-label">Jenjang</label>
                            <select class="form-select @error('jenjang') is-invalid @enderror"
                                id="jenjang" name="jenjang" required>
                                <option value="">Pilih Jenjang</option>
                                @foreach($jenjang as $j)
                                    <option value="{{ $j }}"
                                        {{ old('jenjang', $santri->jenjang) == $j ? 'selected' : '' }}>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenjang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select class="form-select @error('kelas') is-invalid @enderror"
                                id="kelas" name="kelas" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas[$santri->jenjang] ?? [] as $k)
                                    <option value="{{ $k }}"
                                        {{ old('kelas', $santri->kelas) == $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select class="form-select @error('kategori_id') is-invalid @enderror"
                                id="kategori_id" name="kategori_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id }}"
                                        {{ old('kategori_id', $santri->kategori_id) == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                id="status" name="status" required>
                                <option value="aktif" {{ old('status', $santri->status) == 'aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="non-aktif" {{ old('status', $santri->status) == 'non-aktif' ? 'selected' : '' }}>
                                    Non-aktif
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk wali santri
    $('.select2-wali').select2({
        theme: 'bootstrap-5',
        placeholder: 'Cari nama/email wali...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("admin.users.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    role: 'wali'
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.text,
                            santri: item.santri
                        };
                    })
                };
            },
            cache: true
        }
    }).on('select2:select', function(e) {
        // Tampilkan info santri dari wali yang dipilih
        var data = e.params.data;
        if (data.santri && data.santri.length > 0) {
            var santriInfo = '<div class="text-muted">' +
                '<i class="fas fa-info-circle me-1"></i> ' +
                'Wali dari:';

            data.santri.forEach(function(s) {
                santriInfo += `<br>• ${s.nama} (${s.kelas})`;
            });

            santriInfo += '</div>';
            $('#wali_info').html(santriInfo);
        } else {
            $('#wali_info').html('<div class="text-muted">' +
                '<i class="fas fa-info-circle me-1"></i> ' +
                'Belum memiliki santri</div>');
        }
    }).on('select2:clear', function() {
        $('#wali_info').empty();
    });

    // Update opsi kelas saat jenjang berubah
    $('#jenjang').change(function() {
        var jenjang = $(this).val();
        var kelas = @json($kelas);
        var options = '<option value="">Pilih Kelas</option>';

        if (jenjang && kelas[jenjang]) {
            kelas[jenjang].forEach(function(k) {
                options += `<option value="${k}">${k}</option>`;
            });
        }

        $('#kelas').html(options);
    });
});
</script>
@endpush
