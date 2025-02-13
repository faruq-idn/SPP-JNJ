<!-- Modal Detail & Form Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Detail Pembayaran SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="modalTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info-umum">
                            Informasi Umum
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#detail-online" id="tab-online">
                            Detail Pembayaran Online
                        </button>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content pt-3">
                    <!-- Informasi Umum -->
                    <div class="tab-pane fade show active" id="info-umum">
                        <div class="mb-4">
                            <h6 class="mb-3 fw-bold text-primary">Informasi Tagihan</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%">Periode</td>
                                    <td><span id="detail-bulan" class="fw-bold"></span> <span id="detail-tahun"></span></td>
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
                        </div>

                        <form id="formPembayaran" style="display: none;">
                            @csrf
                            <input type="hidden" name="pembayaran_id" id="pembayaran_id">
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
                    </div>

                    <!-- Detail Pembayaran Online -->
                    <div class="tab-pane fade" id="detail-online">
                        <div id="online-payment-info">
                            <table class="table table-sm">
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

                            <div class="mt-4" id="payment-details-section">
                                <h6 class="fw-bold mb-3">Detail Transaksi</h6>
                                <div id="detail-payment-details" class="bg-light p-3 rounded"></div>
                            </div>
                        </div>

                        <div id="no-online-payment" class="text-center py-4 text-muted d-none">
                            <i class="fas fa-info-circle mb-2 fa-2x"></i>
                            <p class="mb-0">Pembayaran ini tidak dilakukan secara online</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const PAYMENT_STATUS = {
    SUCCESS: '{{ App\Models\PembayaranSpp::STATUS_SUCCESS }}',
    FAILED: '{{ App\Models\PembayaranSpp::STATUS_FAILED }}',
    PENDING: '{{ App\Models\PembayaranSpp::STATUS_PENDING }}'
};

window.role = 'petugas';
</script>
<script src="{{ asset('js/pembayaran.js') }}"></script>
@endpush
