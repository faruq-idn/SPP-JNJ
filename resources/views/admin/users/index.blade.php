@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@push('styles')
<!-- DataTables -->
<link href="{{ asset('vendor/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<style>
    .password-toggle {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pengguna</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userFormModal" onclick="resetForm()">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead class="bg-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->no_hp }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                            class="btn btn-sm btn-warning"
                                            onclick="editUser({{ $user->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                            class="d-inline delete-form">
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

<!-- Modal Form -->
<div class="modal fade" id="userFormModal" tabindex="-1" aria-labelledby="userFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userFormModalLabel">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm" onsubmit="submitForm(event)">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="user_id">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="wali">Wali Santri</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password">
                            <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <small class="form-text text-muted" id="passwordHelpText">
                            Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.
                        </small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            <span class="input-group-text password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- DataTables -->
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        language: {
            url: "{{ asset('vendor/datatables/i18n/id.json') }}"
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        columnDefs: [{
            targets: -1,
            orderable: false,
            searchable: false
        }]
    });

    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Anda yakin ingin menghapus pengguna ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function resetForm() {
    document.getElementById('userForm').reset();
    document.getElementById('user_id').value = '';
    document.getElementById('userFormModalLabel').textContent = 'Tambah Pengguna';
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
    document.getElementById('passwordHelpText').textContent = 'Minimal 8 karakter';
    
    // Reset semua invalid feedback
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}

function editUser(id) {
    resetForm();
    document.getElementById('userFormModalLabel').textContent = 'Edit Pengguna';
    document.getElementById('password').required = false;
    document.getElementById('password_confirmation').required = false;
    document.getElementById('passwordHelpText').textContent = 'Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.';

    // Fetch user data
    fetch(`{{ url('admin/users') }}/${id}/get-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.data;
                document.getElementById('user_id').value = user.id;
                document.getElementById('name').value = user.name;
                document.getElementById('email').value = user.email;
                document.getElementById('no_hp').value = user.no_hp;
                document.getElementById('role').value = user.role;

                const modal = new bootstrap.Modal(document.getElementById('userFormModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Gagal mengambil data pengguna!'
            });
        });
}

function submitForm(event) {
    event.preventDefault();
    const form = event.target;
    const id = document.getElementById('user_id').value;
    const formData = new FormData(form);
    const isEdit = id !== '';

    // URL endpoint
    const url = isEdit 
        ? `{{ url('admin/users') }}/${id}`
        : "{{ route('admin.users.store') }}";

    // Jika edit, tambahkan method PUT
    if (isEdit) {
        formData.append('_method', 'PUT');
    }

    // Validasi password
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;

    if (password && password.length < 8) {
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Password harus minimal 8 karakter!'
        });
        return;
    }

    if (password !== confirmation) {
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Password dan konfirmasi tidak cocok!'
        });
        return;
    }

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat menyimpan data'
        });
    });
}
</script>
@endpush
@endsection
