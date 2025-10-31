<div class="modal fade" id="modalGenerateTagihanBaru" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Tagihan Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Tagihan akan dibuat untuk semua santri aktif pada bulan yang dipilih.
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Bulan</label>
                            <select class="form-select" id="bulanGenerate" required>
                                @foreach(range(1, 12) as $bulan)
                                    @php
                                        $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                                        $namaBulan = \Carbon\Carbon::create(null, $bulan)->translatedFormat('F');
                                        $selected = $bulan == date('n') ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $bulanPadded }}" {{ $selected }}>
                                        {{ $namaBulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <select class="form-select" id="tahunGenerate" required>
                                @foreach(range(date('Y')-1, date('Y')+1) as $tahun)
                                    @php $selected = $tahun == date('Y') ? 'selected' : ''; @endphp
                                    <option value="{{ $tahun }}" {{ $selected }}>{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="prosesGenerateTagihan()">
                    <i class="fas fa-sync me-1"></i>Generate Tagihan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateTagihan() {
    console.log('Fungsi generateTagihan() dipanggil.');
    const el = document.getElementById('modalGenerateTagihanBaru');
    const modal = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
    modal.show();
    console.log('Mencoba menampilkan modalGenerateTagihan.');
}

function prosesGenerateTagihan() {
    console.log('Fungsi prosesGenerateTagihan() dipanggil.');
    const bulan = $('#bulanGenerate').val();
    const tahun = $('#tahunGenerate').val();
    const periode = `${tahun}-${bulan}`;

    Swal.fire({
        title: 'Konfirmasi Generate Tagihan',
        html: `Anda akan generate tagihan untuk:<br>
              <b>${$('#bulanGenerate option:selected').text()} ${tahun}</b><br><br>
              Apakah Anda yakin?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Generate',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                html: 'Sedang generate tagihan',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Send request
            $.post('{{ route('admin.pembayaran.generate-tagihan') }}', {
                _token: '{{ csrf_token() }}',
                period: periode
            })
            .done(response => {
                const el = document.getElementById('modalGenerateTagihanBaru');
                const modal = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
                modal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    confirmButtonColor: '#28a745'
                }).then(() => window.location.reload());
            })
            .fail(xhr => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat generate tagihan',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}
</script>
@endpush
