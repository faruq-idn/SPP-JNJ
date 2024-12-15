@extends('layouts.admin')

@section('title', 'Kategori Santri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kategori Santri</h1>
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
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
                                        <a href="{{ route('admin.kategori.edit', $k) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
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

@push('scripts')
<script>
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
