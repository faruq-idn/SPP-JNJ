{{-- Riwayat Pembayaran Header --}}
<div class="card-header py-3">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-history me-2"></i>Riwayat Pembayaran
        </h5>
        <button class="btn btn-sm btn-secondary" onclick="printActiveYearPembayaran(this)"
                data-url-template="{{ route('admin.santri.pembayaran.tahun.pdf', ['santri' => $santri->id, 'tahun' => 'YEAR_PLACEHOLDER']) }}">
            <i class="fas fa-print me-1"></i>Cetak Pembayaran
        </button>
    </div>
</div>
@push('scripts')
<script>
function printActiveYearPembayaran(btnEl) {
    const activePane = document.querySelector('.tab-content .tab-pane.show.active');
    if (!activePane) return;
    const tahun = (activePane.id || '').replace('tahun-', '');
    const template = btnEl.getAttribute('data-url-template') || '';
    if (!template) return;
    const url = template.replace('YEAR_PLACEHOLDER', encodeURIComponent(tahun));

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Cetak PDF?',
            text: `Anda akan mencetak riwayat pembayaran tahun ${tahun}. Lanjutkan?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Cetak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(url, '_blank');
            }
        });
    } else {
        if (confirm(`Cetak riwayat pembayaran tahun ${tahun}?`)) {
            window.open(url, '_blank');
        }
    }
}
</script>
@endpush
