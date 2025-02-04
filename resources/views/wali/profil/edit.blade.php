@extends('layouts.wali')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Edit Profil</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('wali.profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text"
                                   name="no_hp"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ old('no_hp', $user->no_hp) }}"
                                   required>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('wali.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Konfirmasi Perubahan',
        text: 'Apakah Anda yakin ingin menyimpan perubahan profil?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>
@endpush
@endsection
