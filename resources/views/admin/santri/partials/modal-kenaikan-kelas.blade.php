<div class="modal fade" id="modalKenaikanKelas" tabindex="-1" aria-labelledby="modalKenaikanKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formKenaikanKelas">
                @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalKenaikanKelasLabel">Konfirmasi Kenaikan Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan!</strong>
                    <p class="mb-0">Pastikan data berikut sudah sesuai sebelum melakukan kenaikan kelas:</p>
                    <ul class="mb-0 mt-2">
                        <li>Santri akan dipindahkan ke kelas yang ditentukan</li>
                        <li>Untuk kelas 9 SMP, pilih "Lulus" atau "Lanjut ke SMA"</li>
                        <li>Perubahan ini akan tercatat dalam riwayat kenaikan kelas</li>
                        <li>Tindakan ini dapat dibatalkan jika terjadi kesalahan</li>
                    </ul>
                </div>
                <div id="detailKenaikanKelas">
                    <!-- Detail santri akan ditampilkan di sini -->
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <i class="fas fa-info-circle me-1"></i>
                    <small>Centang santri kelas 9 yang akan diproses</small>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>
                        Konfirmasi Kenaikan Kelas
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
