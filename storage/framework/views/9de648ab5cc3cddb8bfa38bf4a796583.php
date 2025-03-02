<!-- Modal Detail Pembayaran -->
<div class="modal fade" id="modalDetailPembayaran">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailPembayaranTitle">Detail Pembayaran SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup modal"></button>
            </div>
            <div class="modal-body p-2 p-md-3">
                <div class="mb-3 mb-md-4">
                    <h6 class="fs-7 fs-md-6 mb-2 mb-md-3 fw-bold text-primary">Informasi Tagihan</h6>
                    <div class="vstack gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Periode</span>
                            <div class="text-end">
                                <span id="detail-bulan" class="fw-bold fs-7 fs-md-6"></span>
                                <span id="detail-tahun" class="fs-7 fs-md-6"></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Status</span>
                            <span id="detail-status"></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Nominal</span>
                            <div class="fw-bold text-primary fs-7 fs-md-6">
                                Rp <span id="detail-nominal"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 mb-md-4" id="detail-pembayaran-info">
                    <h6 class="fs-7 fs-md-6 mb-2 mb-md-3 fw-bold text-success">Informasi Pembayaran</h6>
                    <div class="vstack gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Tanggal</span>
                            <span id="detail-tanggal" class="fs-7 fs-md-6"></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-7 fs-md-6">Metode</span>
                            <span id="detail-metode" class="badge bg-info fs-7 fs-md-6"></span>
                        </div>
                    </div>
                </div>
                
                <?php
                    $metode_manual = App\Models\MetodePembayaran::where('kode', 'like', 'MANUAL_%')->get();
                    $metode_online = App\Models\MetodePembayaran::where('kode', 'MIDTRANS')->first();
                ?>
                <div id="pembayaran-options" class="mt-3">
                    <h6 class="fs-7 fs-md-6 mb-2 mb-md-3 fw-bold">Pilih Metode Pembayaran:</h6>
                    <div class="vstack gap-2">
                        <?php $__currentLoopData = $metode_manual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button class="btn <?php echo e($metode->kode == 'MANUAL_TUNAI' ? 'btn-outline-primary' : 'btn-outline-info'); ?> fs-7 fs-md-6 py-2"
                                    onclick="bayarManual('<?php echo e($metode->kode); ?>', '<?php echo e($metode->nama); ?>')">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas <?php echo e($metode->kode == 'MANUAL_TUNAI' ? 'fa-money-bill' : 'fa-exchange-alt'); ?>"></i>
                                    <span><?php echo e($metode->nama); ?></span>
                                </div>
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($metode_online): ?>
                            <button class="btn btn-primary fs-7 fs-md-6 py-2" onclick="bayarOnline()">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-globe"></i>
                                    <span><?php echo e($metode_online->nama); ?></span>
                                </div>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// IIFE untuk menghindari polusi global scope
(function() {
    // State management
    const state = {
        selectedPembayaranId: null
    };

    // Fungsi untuk menyimpan ID pembayaran
    function setSelectedPembayaran(id) {
        state.selectedPembayaranId = id;
    }

    // Fungsi untuk mendapatkan ID pembayaran
    function getSelectedPembayaran() {
        return state.selectedPembayaranId;
    }

    // Fungsi untuk membuat badge status
    function createStatusBadge(status) {
        const badge = document.createElement('span');
        const config = {
            success: { class: 'badge bg-success', text: 'Lunas' },
            pending: { class: 'badge bg-warning', text: 'Pending' },
            default: { class: 'badge bg-danger', text: 'Belum Lunas' }
        };
        
        const statusConfig = config[status] || config.default;
        badge.className = statusConfig.class;
        badge.textContent = statusConfig.text;
        
        return badge;
    }

    // Fungsi untuk menampilkan modal detail pembayaran
    function showDetailPembayaran(id, bulan, nominal, status, tanggal, metode, tahun) {
        // Cek nomor HP terlebih dahulu
        <?php if(!auth()->user()->no_hp): ?>
            Swal.fire({
                title: 'Nomor HP Belum Terdaftar',
                text: 'Anda harus menambahkan nomor HP terlebih dahulu',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Tambahkan Sekarang',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    const profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
                    profileModal.show();
                }
            });
            return;
        <?php endif; ?>

        // Set ID pembayaran yang dipilih
        setSelectedPembayaran(id);
        const elements = {
            bulan: document.getElementById('detail-bulan'),
            nominal: document.getElementById('detail-nominal'),
            tahun: document.getElementById('detail-tahun'),
            status: document.getElementById('detail-status'),
            pembayaranOptions: document.getElementById('pembayaran-options'),
            infoPembayaran: document.getElementById('detail-pembayaran-info'),
            tanggal: document.getElementById('detail-tanggal'),
            metode: document.getElementById('detail-metode')
        };

        // Update informasi dasar
        elements.bulan.textContent = bulan;
        elements.nominal.textContent = nominal.toLocaleString('id-ID');
        elements.tahun.textContent = tahun;

        // Update status dan badge
        elements.status.innerHTML = '';
        elements.status.appendChild(createStatusBadge(status));
        
        // Update tampilan opsi pembayaran dan info pembayaran
        elements.pembayaranOptions.style.display = status === 'success' ? 'none' : 'block';
        elements.infoPembayaran.style.display = status === 'success' ? 'block' : 'none';

        if (status === 'success') {
            elements.tanggal.textContent = tanggal;
            elements.metode.textContent = metode;
        }

        // Tampilkan modal
        const modalElement = document.getElementById('modalDetailPembayaran');
        const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        
        // Reset state saat modal ditutup dan hapus modal instance
        modalElement.addEventListener('hidden.bs.modal', () => {
            setSelectedPembayaran(null);
            modalInstance.dispose();
        }, { once: true });
        
        modalInstance.show();
    }

    // Fungsi pembayaran manual
    function bayarManual(kode, nama) {
        let pesan = '';
        if (kode === 'MANUAL_TUNAI') {
            pesan = 'Silakan lakukan pembayaran langsung ke bagian administrasi pondok.';
        } else if (kode === 'MANUAL_TRANSFER') {
            pesan = `Silakan transfer ke rekening berikut:<br><br>
                <b>Bank BRI</b><br>
                No. Rek: 1234-5678-9012-3456<br>
                A.n: Yayasan Pondok<br><br>
                Setelah transfer, harap konfirmasi dengan mengirimkan bukti transfer ke administrasi.`;
        }

        Swal.fire({
            title: `Pembayaran ${nama}`,
            html: pesan,
            icon: 'info',
            confirmButtonText: 'Mengerti'
        });
    }

    // Fungsi pembayaran online
    function bayarOnline() {
        const id = getSelectedPembayaran();
        if (id) {
            bayarSPP(id);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Data pembayaran tidak ditemukan'
            });
        }
    }

    // Fungsi untuk memproses pembayaran SPP
    function bayarSPP(id) {
    // Cek nomor HP wali terlebih dahulu
    <?php if(!auth()->user()->no_hp): ?>
        Swal.fire({
            title: 'Nomor HP Belum Terdaftar',
            text: 'Anda harus menambahkan nomor HP terlebih dahulu untuk melakukan pembayaran online',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Tambahkan Sekarang',
            cancelButtonText: 'Nanti Saja',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan modal profil
                let profileModalElement = document.getElementById('profileModal');
                let profileModalInstance = new bootstrap.Modal(profileModalElement);
                profileModalInstance.show();
            }
        });
        return;
    <?php endif; ?>

    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        text: 'Anda akan melanjutkan ke halaman pembayaran online?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses Pembayaran',
                text: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('<?php echo e(route("wali.pembayaran.store")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    tagihan_id: id
                })
            })
            .then(async response => {
                const data = await response.json();

                if (response.status === 400) {
                    Swal.close();
                    if (data.redirect_url) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: data.message,
                            confirmButtonText: 'OK'
                        });
                    }
                    return;
                }

                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }

                Swal.close();
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            Swal.fire({
                                title: 'Pembayaran Berhasil',
                                text: 'Halaman akan diperbarui',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        onPending: function(result) {
                            Swal.fire({
                                title: 'Pembayaran Pending',
                                text: 'Silakan selesaikan pembayaran Anda',
                                icon: 'info'
                            });
                        },
                        onError: function(result) {
                            console.error('Payment Error:', result);
                            let errorMessage = 'Pembayaran gagal';
                            if (result.status_message) {
                                errorMessage += ': ' + result.status_message;
                            }
                            Swal.fire('Error', errorMessage, 'error');
                        },
                        onClose: function() {
                            Swal.fire('Info', 'Pembayaran dibatalkan', 'info');
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Gagal terhubung ke server. Silakan coba lagi.'
                });
            });
        }
    });
    }

    // Expose functions to window object
    Object.assign(window, {
        showDetailPembayaran,
        bayarManual,
        bayarOnline,
        bayarSPP
    });
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\layouts\partials\modal-detail-pembayaran.blade.php ENDPATH**/ ?>