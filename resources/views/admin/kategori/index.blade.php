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
                            <th>Tarif SPP</th>
                            <th>Berlaku Mulai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $k)
                            <tr>
                                <td>{{ $k->nama }}</td>
                                <td>{{ $k->keterangan }}</td>
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
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button type="button" 
                                            class="btn btn-sm btn-info"
                                            onclick="updateTarif({{ $k->id }})">
                                            <i class="fas fa-money-bill"></i> Tarif
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
                                <td colspan="5" class="text-center">Tidak ada data kategori</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="createKategoriModal" tabindex="-1" aria-labelledby="createKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createKategoriModalLabel">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreateKategori" onsubmit="submitCreateForm(event)">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="nominal_spp" class="form-label">Nominal SPP</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                class="form-control" 
                                id="nominal_spp" 
                                name="nominal_spp" 
                                min="0"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                required>
                        </div>
                        <small class="text-muted">Hanya masukkan angka tanpa titik atau koma</small>
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

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKategori" onsubmit="submitEditForm(event)">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_kategori_id">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update Tarif -->
<div class="modal fade" id="updateTarifModal" tabindex="-1" aria-labelledby="updateTarifModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTarifModalLabel">Update Tarif SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdateTarif" onsubmit="submitUpdateTarifForm(event)">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="tarif_kategori_id">
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal Tarif Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                class="form-control" 
                                id="nominal" 
                                name="nominal" 
                                min="0"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                required>
                        </div>
                        <small class="text-muted">Hanya masukkan angka tanpa titik atau koma</small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="berlaku_mulai" class="form-label">Berlaku Mulai</label>
                        <input type="date" class="form-control" id="berlaku_mulai" name="berlaku_mulai" 
                            value="{{ date('Y-m-d') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="tarif_keterangan" class="form-label">Keterangan Perubahan</label>
                        <textarea class="form-control" id="tarif_keterangan" name="keterangan" rows="2"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Tarif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Fungsi update tarif
function updateTarif(id) {
    document.getElementById('tarif_kategori_id').value = id;
    const modal = new bootstrap.Modal(document.getElementById('updateTarifModal'));
    modal.show();
}

function submitUpdateTarifForm(event) {
    event.preventDefault();
    
    const id = document.getElementById('tarif_kategori_id').value;
    const form = event.target;
    const nominal = document.getElementById('nominal').value;
    
    // Validasi nominal
    if (isNaN(nominal) || nominal <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Input Tidak Valid',
            text: 'Nominal harus berupa angka positif'
        });
        return;
    }
    
    const formData = new FormData(form);
    
    fetch(`{{ url('admin/kategori') }}/${id}/update-tarif`, {
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
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan! Silakan coba lagi.'
        });
    });
}

// Fungsi untuk menambah kategori
function submitCreateForm(event) {
    event.preventDefault();
    
    const form = event.target;
    const nominal = document.getElementById('nominal_spp').value;
    
    // Validasi nominal
    if (isNaN(nominal) || nominal <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Input Tidak Valid',
            text: 'Nominal SPP harus berupa angka positif'
        });
        return;
    }
    
    const formData = new FormData(form);
    
    fetch("{{ route('admin.kategori.store') }}", {
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
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan! Silakan coba lagi.'
        });
    });
}

function editKategori(id) {
    fetch(`{{ url('admin/kategori') }}/${id}/get-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const kategori = data.data;
                document.getElementById('edit_kategori_id').value = kategori.id;
                document.getElementById('edit_nama').value = kategori.nama;
                document.getElementById('edit_keterangan').value = kategori.keterangan;
                
                const modal = new bootstrap.Modal(document.getElementById('editKategoriModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Gagal mengambil data kategori!'
            });
        });
}

function submitEditForm(event) {
    event.preventDefault();
    
    const id = document.getElementById('edit_kategori_id').value;
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(`{{ url('admin/kategori') }}/${id}`, {
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
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan! Silakan coba lagi.'
        });
    });
}

// Untuk kategori reguler (3x konfirmasi)
function confirmDeleteReguler(event) {
    event.preventDefault();
    const form = event.target;

    // Konfirmasi pertama
    Swal.fire({
        title: 'Konfirmasi Pertama',
        text: "Apakah Anda yakin ingin menghapus kategori Reguler?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Konfirmasi kedua
            Swal.fire({
                title: 'Konfirmasi Kedua',
                text: "Menghapus kategori akan menghapus semua data terkait!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, saya mengerti',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Konfirmasi ketiga
                    Swal.fire({
                        title: 'Konfirmasi Terakhir',
                        text: "Tindakan ini tidak dapat dibatalkan!",
                        icon: 'warning',
                        input: 'text',
                        inputPlaceholder: 'Ketik "HAPUS" untuk konfirmasi',
                        inputValidator: (value) => {
                            if (value !== 'HAPUS') {
                                return 'Anda harus mengetik "HAPUS"';
                            }
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus sekarang!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        }
    });

    return false;
}

// Untuk kategori lainnya (2x konfirmasi)
function confirmDelete(event) {
    event.preventDefault();
    const form = event.target;
    const kategoriNama = form.closest('tr').querySelector('td:first-child').textContent;

    // Konfirmasi pertama
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus kategori "${kategoriNama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Konfirmasi kedua
            Swal.fire({
                title: 'Konfirmasi Terakhir',
                text: "Menghapus kategori akan menghapus semua data terkait dan tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });

    return false;
}
</script>
@endpush
@endsection
