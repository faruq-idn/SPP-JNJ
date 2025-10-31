@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@push('styles')
<!-- DataTables & Select2 -->
<link href="{{ asset('vendor/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/select2/css/select2-bootstrap5.min.css') }}" rel="stylesheet">
<style>
    .password-toggle {
        cursor: pointer;
    }

    .list-unstyled li {
        margin-bottom: 0.25rem;
    }

    .list-unstyled li:last-child {
        margin-bottom: 0;
    }

    .list-unstyled li a {
        color: #2563eb;
    }

    .list-unstyled li a:hover {
        text-decoration: underline !important;
    }

    /* Tambahkan style untuk form modal 2 kolom */
    #userFormModal .modal-body .row > .col-md-6 {
        padding-left: 15px;
        padding-right: 15px;
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
                            <th>Santri Terhubung</th>
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
                                    @if($user->role === 'wali' && $user->santri->count() > 0)
                                        <ul class="list-unstyled mb-0">
                                            @foreach($user->santri as $santri)
                                                <li>
                                                    <a href="{{ route('admin.santri.show', $santri) }}" class="text-decoration-none">
                                                        {{ $santri->nama }} ({{ $santri->nisn }})
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
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

    <!-- Modal dipindahkan ke section('modals') agar di luar stacking context -->
    </div>

@section('modals')
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
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
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

                            <div id="santriField" style="display: none;">
                                <div class="mb-3">
                                    <label for="santri_ids" class="form-label">Cari & Pilih Santri</label>
                                    <select class="form-select" id="santri_ids" name="santri_ids[]" multiple>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        Cari nama atau NISN santri yang akan dihubungkan
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Santri Yang Terhubung</label>
                                    <div id="linkedSantriList" class="list-group">
                                        <!-- Santri yang sudah terhubung akan ditampilkan di sini -->
                                    </div>
                                    <small class="form-text text-muted">
                                        Santri yang sudah terhubung dengan wali ini
                                    </small>
                                </div>
                            </div>
                        </div>
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
@endsection

@push('scripts')
<!-- DataTables & Select2 -->
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script>
let url; // Declare url in global scope

function updateSantriField() {
    console.log('updateSantriField() called');
    const role = $('#role').val();
    const isWali = role === 'wali';
    console.log('Role:', role, 'isWali:', isWali);
    $('#santriField')[isWali ? 'show' : 'hide']();
    if (!isWali) {
        $('#santri_ids').val(null).trigger('change');
        $('#linkedSantriList').empty();
    }
    console.log('#santriField visibility:', $('#santriField').is(':visible'));
}

$(document).ready(function() {
    // Inisialisasi DataTable
    const table = $('#dataTable').DataTable({
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

    // Inisialisasi event handler delete
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

    // Inisialisasi Select2 untuk field santri
    $('#santri_ids').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih santri...',
        allowClear: true,
        dropdownParent: $('#userFormModal'),
        ajax: {
            url: "{{ route('admin.users.searchSantri') }}",
            type: 'GET',
            dataType: 'json',
            delay: 800,  // Tambah delay untuk mengurangi request
            throttle: 1000,  // Batasi request maksimal 1 per detik
            data: function(params) {
                return {
                    q: params.term || '',
                    wali_id: $('#user_id').val()
                };
            },
            processResults: function(data) {
                return data;  // Data sudah dalam format {results: [...]}
            },
            cache: true,
            beforeSend: function() {
                console.log('Request akan dikirim:', {
                    url: this.url,
                    data: this.data
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Hanya tampilkan error yang bukan karena request dibatalkan
                if (textStatus !== 'abort') {
                    console.error('Select2 AJAX error:', {
                        status: jqXHR.status,
                        textStatus: textStatus,
                        error: errorThrown,
                        response: jqXHR.responseText
                    });
                }
            },
            success: function(data) {
                console.log('Response diterima:', data);
            }
        },
        minimumInputLength: 1,
        language: {
            inputTooShort: function() {
                return 'Ketik minimal 1 karakter untuk mencari';
            },
            searching: function() {
                return 'Mencari...';
            },
            noResults: function() {
                return 'Tidak ada data yang ditemukan';
            }
        }
    });

    $('#role').on('change', function() {
        console.log('role change event triggered');
        const role = $(this).val();
        const isWali = role === 'wali';
        console.log('Role:', role, 'isWali:', isWali);
        console.log('Role Value:', $('#role').val());
        $('#santriField')[isWali ? 'show' : 'hide']();
        if (!isWali) {
            $('#santri_ids').val(null).trigger('change');
            $('#linkedSantriList').empty();
        }
        console.log('#santriField visibility:', $('#santriField').is(':visible'));
    });

    // Trigger saat modal dibuka
    $('#userFormModal').on('shown.bs.modal', function() {
        console.log('userFormModal shown.bs.modal event triggered');
        setTimeout(updateSantriField, 100);
    });

    // Handle pemilihan santri dari select2
    $('#santri_ids').on('select2:select', function(e) {
        const santri = e.params.data;
        appendSantriToList(santri.id, santri.text);
        $(this).val(null).trigger('change');
    });
});

function appendSantriToList(id, text) {
    const linkedSantriList = $('#linkedSantriList');
    linkedSantriList.append(`
        <div class="list-group-item d-flex justify-content-between align-items-center" data-santri-id="${id}">
            <div>
                <a href="{{ route('admin.santri.show', '') }}/${id}" class="text-decoration-none">
                    ${text}
                </a>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSantri(${id})">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `);
}

function removeSantri(id) {
    $(`#linkedSantriList [data-santri-id="${id}"]`).remove();
}

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
    console.log('resetForm() called');
    document.getElementById('userForm').reset();
    document.getElementById('user_id').value = '';
    document.getElementById('userFormModalLabel').textContent = 'Tambah Pengguna';
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
    document.getElementById('passwordHelpText').textContent = 'Minimal 8 karakter';

    // Set form URL for add mode
    url = "{{ route('admin.users.store') }}"; // Assign to global url variable

    // Reset santri field
    $('#santri_ids').val(null).trigger('change');
    $('#santriField').hide();
    $('#linkedSantriList').empty();

    // Reset semua invalid feedback
    document.querySelectorAll('#userForm .invalid-feedback').forEach(el => el.textContent = '');
    document.querySelectorAll('#userForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));
    console.log('resetForm() finished');
}

function editUser(id) {
    resetForm();
    document.getElementById('userFormModalLabel').textContent = 'Edit Pengguna';
    document.getElementById('password').required = false;
    document.getElementById('password_confirmation').required = false;
    document.getElementById('passwordHelpText').textContent = 'Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.';

    // Set form URL for edit mode
    url = `{{ url('admin/users') }}/${id}`; // Assign to global url variable


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

                // Show modal first
                const modal = new bootstrap.Modal(document.getElementById('userFormModal'));
                modal.show();

                // After modal shown, handle santri field
                if (user.role === 'wali') {
                    fetch(`{{ url('admin/users') }}/${id}/santri`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.santri) {
                                data.santri.forEach(santri => {
                                    appendSantriToList(santri.id, `${santri.nama} (${santri.nisn})`);
                                });
                                updateSantriField(); // Update field visibility after data loaded
                            }
                        });
                } else {
                    updateSantriField();
                }
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

    // Ambil santri_ids dari linkedSantriList jika role wali
    if ($('#role').val() === 'wali') {
        formData.delete('santri_ids[]'); // Hapus data santri dari select2
        $('#linkedSantriList [data-santri-id]').each(function() {
            formData.append('santri_ids[]', $(this).data('santri-id'));
        });
    }

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
