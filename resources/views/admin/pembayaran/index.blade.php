@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPembayaran">
            <i class="fas fa-plus"></i> Input Pembayaran
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
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Santri</th>
                            <th>Bulan</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $p)
                            <tr>
                                <td>
                                    @if($p->tanggal_bayar)
                                        {{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $p->santri->nama }}</div>
                                    <small class="text-muted">{{ $p->santri->nisn }}</small>
                                </td>
                                <td>
                                    @php
                                        $bulan = str_pad($p->bulan, 2, '0', STR_PAD_LEFT);
                                        $namaBulan = \Carbon\Carbon::createFromFormat('m', $bulan)->isoFormat('MMMM Y');
                                    @endphp
                                    {{ $namaBulan }}
                                </td>
                                <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                <td>
                                    @if($p->metode_pembayaran)
                                        <span class="badge bg-info">
                                            {{ $p->metode_pembayaran->nama }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Manual</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $p->status == 'success' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info" onclick="showDetail('{{ $p->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($p->status != 'success')
                                            <button class="btn btn-success" onclick="verifikasiPembayaran('{{ $p->id }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-danger" onclick="hapusPembayaran('{{ $p->id }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    <i class="fas fa-info-circle me-2"></i>Belum ada data pembayaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pembayaran->hasPages())
                <div class="card-footer bg-white border-0 pt-0">
                    <div class="d-flex justify-content-end">
                        {{ $pembayaran->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Form Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Pembayaran SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.pembayaran.index') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <!-- Data Santri -->
                        <div class="col-md-12 mb-4">
                            <div class="mb-3">
                                <label class="form-label">Cari Santri</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchSantri"
                                        placeholder="Ketik nama atau NISN santri..." autocomplete="off">
                                    <button class="btn btn-outline-secondary" type="button" onclick="resetSantri()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimal 2 karakter untuk mencari</div>
                            </div>

                            <!-- Tampilan santri terpilih -->
                            <div id="selectedSantri" style="display:none;" class="mb-3">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Nama:</strong><br><span id="selected-nama"></span></p>
                                                <p class="mb-1"><strong>NISN:</strong><br><span id="selected-nisn"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Kelas:</strong><br><span id="selected-kelas"></span></p>
                                                <p class="mb-1"><strong>Kategori:</strong><br><span id="selected-kategori"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mb-3" id="hasilPencarian" style="display:none;">
                                <table class="table table-hover table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>NISN</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>Kategori</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Data Pembayaran -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Bayar</label>
                                <input type="date" class="form-control @error('tanggal_bayar') is-invalid @enderror"
                                    name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                                @error('tanggal_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bulan</label>
                                        <select class="form-select @error('bulan') is-invalid @enderror"
                                            name="bulan" required>
                                            <option value="">Pilih Bulan</option>
                                            @foreach($bulanList as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ old('bulan') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bulan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun</label>
                                        <select class="form-select @error('tahun') is-invalid @enderror"
                                            name="tahun" required>
                                            <option value="">Pilih Tahun</option>
                                            @foreach($tahunList as $y)
                                                <option value="{{ $y }}"
                                                    {{ old('tahun') == $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nominal</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                        name="nominal" value="{{ old('nominal') }}" required>
                                </div>
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select class="form-select @error('metode_pembayaran') is-invalid @enderror"
                                    name="metode_pembayaran" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>
                                        Tunai
                                    </option>
                                    <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>
                                        Transfer Bank
                                    </option>
                                </select>
                                @error('metode_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pembayaran -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detail Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Data Santri -->
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Data Santri</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <p class="mb-1">
                                    <strong>Nama:</strong><br>
                                    <span id="detail-santri-nama"></span>
                                </p>
                                <p class="mb-0">
                                    <strong>NISN:</strong><br>
                                    <span id="detail-santri-nisn"></span>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1">
                                    <strong>Kelas:</strong><br>
                                    <span id="detail-santri-kelas"></span>
                                </p>
                                <p class="mb-0">
                                    <strong>Kategori:</strong><br>
                                    <span id="detail-santri-kategori"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Pembayaran -->
                <div class="card border-0">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Data Pembayaran</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <p class="mb-1">
                                    <strong>Tanggal Bayar:</strong><br>
                                    <span id="detail-pembayaran-tanggal"></span>
                                </p>
                                <p class="mb-1">
                                    <strong>Bulan:</strong><br>
                                    <span id="detail-pembayaran-bulan"></span>
                                </p>
                                <p class="mb-0">
                                    <strong>Tahun:</strong><br>
                                    <span id="detail-pembayaran-tahun"></span>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1">
                                    <strong>Nominal:</strong><br>
                                    Rp <span id="detail-pembayaran-nominal"></span>
                                </p>
                                <p class="mb-1">
                                    <strong>Metode:</strong><br>
                                    <span id="detail-pembayaran-metode"></span>
                                </p>
                                <p class="mb-0">
                                    <strong>Status:</strong><br>
                                    <span id="detail-pembayaran-status"></span>
                                </p>
                            </div>
                            <div class="col-12">
                                <p class="mb-0">
                                    <strong>Keterangan:</strong><br>
                                    <span id="detail-pembayaran-keterangan"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let typingTimer;
const doneTypingInterval = 500;
const minLength = 2;

// Validasi input pencarian
function validateSearch(keyword) {
    if (keyword.length < minLength) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Minimal 2 karakter untuk mencari',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        return false;
    }
    return true;
}

// Event handler untuk input pencarian
$('#searchSantri').on('keyup', function() {
    clearTimeout(typingTimer);
    const keyword = $(this).val().trim();

    // Reset hasil pencarian jika input kosong
    if (keyword === '') {
        $('#hasilPencarian').hide();
        return;
    }

    // Validasi input
    if (!validateSearch(keyword)) {
        return;
    }

    // Tampilkan loading state
    $('#hasilPencarian').show();
    $('#hasilPencarian tbody').html(`
        <tr>
            <td colspan="5" class="text-center">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </td>
        </tr>
    `);

    // Tunda pencarian untuk mengurangi request
    typingTimer = setTimeout(() => doSearch(keyword), doneTypingInterval);
});

// Fungsi pencarian AJAX
function doSearch(keyword) {
    $.ajax({
        url: '{{ route("admin.santri.search") }}',
        data: { q: keyword },
        method: 'GET',
        success: function(data) {
            renderHasilPencarian(data);
        },
        error: function(xhr) {
            handleSearchError(xhr);
        }
    });
}

// Render hasil pencarian
function renderHasilPencarian(data) {
    let html = '';
    if (data.length > 0) {
        data.forEach(function(santri) {
            html += `
                <tr>
                    <td>${santri.nisn}</td>
                    <td>${santri.nama}</td>
                    <td>${santri.kelas}</td>
                    <td>${santri.kategori}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary pilih-santri"
                            onclick="pilihSantri(${JSON.stringify(santri).replace(/"/g, '&quot;')})">
                            <i class="fas fa-check"></i> Pilih
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html = `
            <tr>
                <td colspan="5" class="text-center text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Tidak ada data yang sesuai
                </td>
            </tr>
        `;
    }
    $('#hasilPencarian tbody').html(html);
}

// Handle error pencarian
function handleSearchError(xhr) {
    $('#hasilPencarian tbody').html(`
        <tr>
            <td colspan="5" class="text-center text-danger">
                <i class="fas fa-exclamation-circle me-1"></i>
                Terjadi kesalahan saat mencari data
            </td>
        </tr>
    `);

    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Gagal melakukan pencarian. Silakan coba lagi.',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

// Fungsi untuk memilih santri
function pilihSantri(santri) {
    try {
        // Hapus input hidden yang mungkin sudah ada
        $('input[name="santri_id"]').remove();

        // Tambahkan input hidden baru
        $('<input>').attr({
            type: 'hidden',
            name: 'santri_id',
            value: santri.id
        }).appendTo('form');

        // Update tampilan santri terpilih
        $('#selected-nama').text(santri.nama);
        $('#selected-nisn').text(santri.nisn);
        $('#selected-kelas').text(santri.kelas);
        $('#selected-kategori').text(santri.kategori);

        // Tampilkan card santri terpilih
        $('#selectedSantri').show();

        // Sembunyikan hasil pencarian
        $('#hasilPencarian').hide();

        // Update input pencarian dengan nama santri
        $('#searchSantri').val(santri.nama);

        // Tampilkan notifikasi sukses
        Swal.fire({
            icon: 'success',
            title: 'Santri dipilih',
            text: `${santri.nama} telah dipilih`,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

    } catch (error) {
        console.error('Error saat memilih santri:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal memilih santri. Silakan coba lagi.',
        });
    }
}

// Fungsi untuk reset pilihan santri
function resetSantri() {
    // Hapus input hidden santri_id
    $('input[name="santri_id"]').remove();

    // Reset form pencarian
    $('#searchSantri').val('');
    $('#hasilPencarian').hide();

    // Sembunyikan card santri terpilih
    $('#selectedSantri').hide();

    // Reset nilai-nilai yang ditampilkan
    $('#selected-nama, #selected-nisn, #selected-kelas, #selected-kategori').text('-');
}

// Event handler untuk tombol reset di input pencarian
$('#searchSantri').on('search', function() {
    if ($(this).val() === '') {
        $('#hasilPencarian').hide();
    }
});

$('#dataTable').DataTable();

// Fungsi untuk menampilkan detail pembayaran
window.showDetailPembayaran = function(santriId) {
    // Reset modal
    $('#modal-pembayaran').html('<tr><td colspan="5" class="text-center">Memuat data...</td></tr>');

    // Tampilkan modal
    $('#detailPembayaranModal').modal('show');

    // Ambil data pembayaran
    $.get(`{{ url('admin/santri') }}/${santriId}/pembayaran`, function(response) {
        // Update info santri
        $('#modal-nama').text(response.santri.nama);
        $('#modal-nisn').text(response.santri.nisn);
        $('#modal-kelas').text(response.santri.kelas);
        $('#modal-kategori').text(response.santri.kategori);

        // Update tabel pembayaran
        if (response.pembayaran.length > 0) {
            let rows = '';
            response.pembayaran.forEach(function(p) {
                rows += `
                    <tr>
                        <td>${p.bulan_nama}</td>
                        <td>${p.tahun}</td>
                        <td>Rp ${parseInt(p.nominal).toLocaleString('id')}</td>
                        <td>
                            <span class="badge bg-${p.status === 'success' ? 'success' : 'warning'}">
                                ${p.status === 'success' ? 'Lunas' : 'Belum Lunas'}
                            </span>
                        </td>
                        <td>${p.tanggal_bayar ?? '-'}</td>
                    </tr>
                `;
            });
            $('#modal-pembayaran').html(rows);
        } else {
            $('#modal-pembayaran').html(`
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada data pembayaran
                    </td>
                </tr>
            `);
        }
    }).fail(function() {
        $('#modal-pembayaran').html(`
            <tr>
                <td colspan="5" class="text-center text-danger">
                    Gagal memuat data. Silakan coba lagi.
                </td>
            </tr>
        `);
    });
}

function showDetail(id) {
    // Reset modal content
    $('#detailModal .modal-body span[id^="detail-"]').text('-');

    // Show modal
    $('#detailModal').modal('show');

    // Fetch data
    $.get(`{{ url('admin/pembayaran') }}/${id}`, function(response) {
        // Update Santri Info
        $('#detail-santri-nama').text(response.santri.nama);
        $('#detail-santri-nisn').text(response.santri.nisn);
        $('#detail-santri-kelas').text(response.santri.kelas);
        $('#detail-santri-kategori').text(response.santri.kategori);

        // Update Pembayaran Info
        $('#detail-pembayaran-tanggal').text(response.pembayaran.tanggal);
        $('#detail-pembayaran-bulan').text(response.pembayaran.bulan);
        $('#detail-pembayaran-tahun').text(response.pembayaran.tahun);
        $('#detail-pembayaran-nominal').text(response.pembayaran.nominal);
        $('#detail-pembayaran-metode').text(response.pembayaran.metode);

        // Update status dengan badge
        const statusClass = response.pembayaran.status.toLowerCase() === 'success' ? 'success' :
                          (response.pembayaran.status.toLowerCase() === 'pending' ? 'warning' : 'danger');
        $('#detail-pembayaran-status').html(`
            <span class="badge bg-${statusClass}">
                ${response.pembayaran.status}
            </span>
        `);

        $('#detail-pembayaran-keterangan').text(response.pembayaran.keterangan);
    }).fail(function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal memuat detail pembayaran'
        });
        $('#detailModal').modal('hide');
    });
}
</script>
@endpush
@endsection
