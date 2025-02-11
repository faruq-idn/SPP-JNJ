<!-- Modal Detail & Form Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Detail Pembayaran SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDetail(id, bulan, nominal, tahun, status, tanggal, metode) {
    const modal = new bootstrap.Modal(document.getElementById('modalPembayaran'));
    
    // Update konten modal
    document.getElementById('modalTitle').textContent = 'Detail Pembayaran SPP';
    document.getElementById('detail-bulan').textContent = bulan;
    document.getElementById('detail-tahun').textContent = tahun;
    document.getElementById('detail-nominal').textContent = nominal.toLocaleString('id-ID');
    
    // Update status dengan badge
    const statusElement = document.getElementById('detail-status');
    let statusClass = status === 'success' ? 'bg-success' : (status === 'pending' ? 'bg-warning' : 'bg-danger');
    let statusText = status === 'success' ? 'Lunas' : (status === 'pending' ? 'Pending' : 'Belum Lunas');
    statusElement.innerHTML = `<span class="badge ${statusClass}">${statusText}</span>`;
    
    // Tampilkan/sembunyikan informasi pembayaran
    const pembayaranInfo = document.getElementById('pembayaran-info');
    const formPembayaran = document.getElementById('formPembayaran');
    
    const selectMetode = formPembayaran.querySelector('[name="metode_pembayaran_id"]');
    const textareaKeterangan = formPembayaran.querySelector('[name="keterangan"]');
    const inputId = document.getElementById('pembayaran_id');
    
    // Reset semua field terlebih dahulu
    selectMetode.value = '';
    textareaKeterangan.value = '';
    inputId.value = '';
    
    if (status === 'success') {
        pembayaranInfo.style.display = 'block';
        formPembayaran.style.display = 'none';
        document.getElementById('detail-tanggal').textContent = tanggal;
        document.getElementById('detail-metode').textContent = metode;
    } else {
        pembayaranInfo.style.display = 'none';
        formPembayaran.style.display = 'block';
        inputId.value = id;
    }

    modal.show();
    if (!status || status !== 'success') {
        setTimeout(() => selectMetode.focus(), 500);
    }
}

// Verifikasi Pembayaran
function verifikasiPembayaran(id, bulan, nominal) {
    showDetail(id, bulan, nominal, '', 'pending', '', '');
}

$(document).ready(function() {
    // Reset form saat modal ditutup
    $('#modalPembayaran').on('hidden.bs.modal', function() {
        document.getElementById('formPembayaran').reset();
    });

    // Handle form submission
    $('#formPembayaran').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const pembayaranId = form.find('[name="pembayaran_id"]').val();
        const formData = form.serializeArray();
        const data = {};
        
        formData.forEach(item => {
            data[item.name] = item.value;
        });

        // Kirim request ke endpoint verifikasi
        $.ajax({
            url: `/petugas/pembayaran/${pembayaranId}/verifikasi`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(response.message);
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error?.message || 'Terjadi kesalahan saat memproses pembayaran'
                });
            }
        });
    });
});
</script>
@endpush
