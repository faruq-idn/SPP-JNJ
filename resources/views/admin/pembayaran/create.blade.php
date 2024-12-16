@extends('layouts.admin')

@section('title', 'Input Pembayaran SPP')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Pembayaran SPP</h1>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.pembayaran.index') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row">
                    <!-- Data Santri -->
                    <div class="col-md-6 mb-4">
                        <div class="mb-3">
                            <label class="form-label">Cari Santri</label>
                            <select class="form-control" id="santri_id" name="santri_id" required></select>
                            @error('santri_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <div id="detail_santri">
                                    <p class="text-muted text-center mb-0">
                                        Pilih santri untuk melihat detail
                                    </p>
                                </div>
                            </div>
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
                                        @foreach($bulan as $key => $value)
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
                                        @foreach($tahun as $y)
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

                <hr>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Pembayaran -->
    <div class="modal fade" id="detailPembayaranModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pembayaran Santri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong>Nama Santri:</strong><br>
                                <span id="modal-nama"></span>
                            </p>
                            <p class="mb-1">
                                <strong>NISN:</strong><br>
                                <span id="modal-nisn"></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong>Kelas:</strong><br>
                                <span id="modal-kelas"></span>
                            </p>
                            <p class="mb-1">
                                <strong>Kategori:</strong><br>
                                <span id="modal-kategori"></span>
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Tanggal Bayar</th>
                                </tr>
                            </thead>
                            <tbody id="modal-pembayaran">
                                <tr>
                                    <td colspan="5" class="text-center">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk pencarian santri
    $('#santri_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Cari nama/NISN santri...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("admin.santri.search") }}',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        templateResult: formatSantri,
        templateSelection: formatSantriSelection
    });

    // Update detail santri saat dipilih
    $('#santri_id').on('select2:select', function(e) {
        var data = e.params.data;
        $('#detail_santri').html(`
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Nama:</strong><br>
                        ${data.nama}
                    </p>
                    <p class="mb-1">
                        <strong>NISN:</strong><br>
                        ${data.nisn}
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Kelas:</strong><br>
                        ${data.kelas}
                    </p>
                    <p class="mb-1">
                        <strong>Kategori:</strong><br>
                        ${data.kategori}
                    </p>
                </div>
                <div class="col-12 mt-2">
                    <p class="mb-1">
                        <strong>Wali Santri:</strong><br>
                        ${data.wali.nama} (${data.wali.email})
                    </p>
                    <p class="mb-0">
                        <strong>Status Pembayaran:</strong><br>
                        <span class="badge bg-${data.tunggakan > 0 ? 'warning' : 'success'}">
                            ${data.status}
                        </span>
                        <button type="button" class="btn btn-sm btn-info ms-2"
                            onclick="showDetailPembayaran(event, ${data.id})">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                    </p>
                </div>
            </div>
        `);
    }).on('select2:clear', function() {
        $('#detail_santri').html(`
            <p class="text-muted text-center mb-0">
                Pilih santri untuk melihat detail
            </p>
        `);
    });

    // Format hasil pencarian
    function formatSantri(santri) {
        if (!santri.id) return santri.text;
        return $(`
            <div class="d-flex justify-content-between">
                <div>
                    <strong>${santri.nama}</strong><br>
                    <small class="text-muted">NISN: ${santri.nisn}</small>
                </div>
                <div class="text-end">
                    <small>${santri.kelas}</small><br>
                    <small class="text-muted">${santri.kategori}</small>
                </div>
            </div>
        `);
    }

    function formatSantriSelection(santri) {
        return santri.id ? santri.nama + ' - ' + santri.nisn : santri.text;
    }

    // Validasi form sebelum submit
    $('form').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: "Pastikan data pembayaran sudah benar sebelum disimpan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // Format nominal dengan separator ribuan
    $('input[name="nominal"]').on('input', function() {
        let value = $(this).val().replace(/[^\d]/g, '');
        if (value.length > 0) {
            value = parseInt(value).toLocaleString('id-ID');
            $(this).val(value.replace(/\./g, ''));
        }
    });

    // Fungsi untuk menampilkan detail pembayaran
    window.showDetailPembayaran = function(e, santriId) {
        // Prevent default action dan stop event propagation
        e.preventDefault();
        e.stopPropagation();

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
});
</script>
@endpush
