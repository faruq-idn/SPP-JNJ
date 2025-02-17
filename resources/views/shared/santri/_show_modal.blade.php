{{-- Modal Detail & Form Pembayaran --}}
<div class="modal fade" id="modalPembayaran" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header">
<h5 class="modal-title" id="modalTitle">Detail Pembayaran SPP</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div><div class="mb-4">
                        <h6 class="mb-3 fw-bold text-primary">Informasi Tagihan</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%">Periode</td>
                                <td>
                                    <span id="detail-bulan" class="fw-bold"></span> 
                                    <span id="detail-tahun"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td><span id="detail-status"></span></td>
                            </tr>
                            <tr>
                                <td>Nominal</td>
                                <td class="fw-bold text-primary">Rp <span id="detail-nominal"></span></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="mb-3 fw-bold text-primary">Informasi Wali</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%">Nomor HP Wali</td>
                                <td><span id="detail-wali-no-hp"></span></td>
                            </tr>
                        </table>
                    </div>

                    <div id="pembayaran-info" style="display: none;">
                        <h6 class="mb-3 fw-bold text-success">Informasi Pembayaran</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%">Tanggal</td>
                                <td><span id="detail-tanggal"></span></td>
                            </tr>
                            <tr>
                                <td>Metode</td>
                                <td><span id="detail-metode" class="badge bg-info"></span></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td><span id="detail-keterangan" class="text-muted fst-italic"></span></td>
                            </tr>
                        </table>

                        {{-- Detail Pembayaran Online --}}
                        <div id="online-payment-info" style="display: none;">
                            <h6 class="mb-3 fw-bold text-primary">Detail Pembayaran Online</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%">Order ID</td>
                                    <td><span id="detail-order-id" class="font-monospace"></span></td>
                                </tr>
                                <tr>
                                    <td>Transaction ID</td>
                                    <td><span id="detail-transaction-id" class="font-monospace"></span></td>
                                </tr>
                                <tr>
                                    <td>Payment Type</td>
                                    <td><span id="detail-payment-type" class="badge bg-secondary"></span></td>
                                </tr>
                            </table>

                            <div class="mt-3" id="payment-details-section" style="display: none;">
                                <h6 class="fw-bold mb-2">Detail Transaksi</h6>
                                <div id="detail-payment-details" class="bg-light p-3 rounded"></div>
                            </div>
                        </div>
                    </div>

                    @if(in_array(auth()->user()->role, ['admin', 'petugas']))
                    <form id="formPembayaran" style="display: none;">
                        @csrf
                        <input type="hidden" name="pembayaran_id" id="pembayaran_id">
                        <input type="hidden" name="bulan" id="bulan">
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select class="form-select" name="metode_pembayaran_id" required>
                                <option value="">Pilih Metode</option>
                                @foreach($metode as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan (opsional)</label>
                            <textarea class="form-control" name="keterangan" rows="2"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i>Verifikasi Pembayaran
                            </button>
                        </div>
                    </form>
                    @endif
                </div>

                    @if(auth()->user()->role === 'admin')
                    <div class="mt-3 d-flex gap-2">
                        <button type="button"
                                id="btn-reset"
                                class="btn btn-warning flex-grow-1"
                                style="display: none;">
                            <i class="fas fa-undo me-1"></i>Reset Pembayaran
                        </button>
                        <button type="button"
                                id="btn-delete"
                                class="btn btn-danger flex-grow-1"
                                style="display: none;">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </div>
                    @endif

@push('scripts')
<script>
$(document).ready(function() {
    let currentPembayaranId = null;

    // Fungsi untuk menampilkan detail pembayaran & santri
    window.showDetail = function(id, bulan, nominal, tahun, status, tanggal, metode, santri) {
                        currentPembayaranId = id;
                        showSantriDetail(santri);
                        
                        // Update informasi tagihan
                        $('#detail-bulan').text(bulan);
                        $('#detail-tahun').text(tahun);
                        $('#detail-nominal').text(nominal.toLocaleString('id-ID'));
                        
                        // Update status pembayaran
                        const statusMap = {
                            'success': '<span class="badge bg-success">Lunas</span>',
                            'pending': '<span class="badge bg-warning">Pending</span>',
                            'unpaid': '<span class="badge bg-danger">Belum Lunas</span>'
                        };
                        $('#detail-status').html(statusMap[status] || '');
                        
                        // Tampilkan informasi pembayaran jika sudah lunas
                        const pembayaranInfo = $('#pembayaran-info');
                        if (status === 'success') {
                            pembayaranInfo.show();
                            $('#detail-tanggal').text(tanggal);
                            $('#detail-metode').text(metode);
                        } else {
                            pembayaranInfo.hide();
                        }
                        
                        // Tampilkan form verifikasi jika belum lunas
                        const formPembayaran = $('#formPembayaran');
                        if (id && status !== 'success') {
                            formPembayaran.show();
                            $('#pembayaran_id').val(id);
                            $('#bulan').val(bulan);
                        } else {
                            formPembayaran.hide();
                        }
                        
                        // Tampilkan/sembunyikan tombol reset & hapus
                        if (id && status === 'success') {
                            $('#btn-reset').show();
                        } else {
                            $('#btn-reset').hide();
                        }
                        
                        if (id) {
                            $('#btn-delete').show();
                        } else {
                            $('#btn-delete').hide();
                        }
                    }

                    window.showSantriDetail = function(santri) {
                        $('#detail-wali-no-hp').text(santri.wali.no_hp || '-');
                    }

                    $('#btn-reset').on('click', function() {
                        if (!currentPembayaranId) return;

                        Swal.fire({
                            title: 'Reset Pembayaran?',
                            text: "Tindakan ini akan mengosongkan status, tanggal bayar, dan metode pembayaran. Tagihan tetap ada dan dapat diverifikasi ulang.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ffc107',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Reset',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `/admin/santri/pembayaran/${currentPembayaranId}/reset`,
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire('Gagal!', response.message, 'error');
                                        }
                                    },
                                    error: function(xhr) {
                                        Swal.fire('Error!', 'Terjadi kesalahan saat reset pembayaran', 'error');
                                    }
                                });
                            }
                        });
                    });

                    $('#btn-delete').on('click', function() {
                        if (!currentPembayaranId) return;

                        Swal.fire({
                            title: 'Hapus Pembayaran?',
                            text: "Tindakan ini akan menghapus seluruh record pembayaran. Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `/admin/santri/pembayaran/${currentPembayaranId}/hapus`,
                                    type: 'DELETE',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire('Gagal!', response.message, 'error');
                                        }
                                    },
                                    error: function(xhr) {
                                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus pembayaran', 'error');
                                    }
                                });
                            }
                        });
                    });

                    // Handle modal show/hide
                    $('#modalPembayaran').on('hidden.bs.modal', function() {
                        currentPembayaranId = null;
                        $('#btn-reset, #btn-delete').hide();
                        $('#formPembayaran').hide();
                        $('#pembayaran-info').hide();
                    });
                });
                </script>
                @endpush
            </div>
        </div>
    </div>
</div>
