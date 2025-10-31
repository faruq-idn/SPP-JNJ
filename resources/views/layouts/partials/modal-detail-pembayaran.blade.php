<!-- Modal Detail Pembayaran -->
<div class="modal fade" id="modalDetailPembayaran">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailPembayaranTitle">Detail Pembayaran SPP</h5>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="btn-print-detail-wali" class="btn btn-sm btn-secondary" style="display:none;">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup modal"></button>
                </div>
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

                @php
                    $metode_manual = App\Models\MetodePembayaran::where('kode', 'like', 'MANUAL_%')->get();
                    $metode_online = App\Models\MetodePembayaran::where('kode', 'MIDTRANS')->first();
                @endphp
                <div id="pembayaran-options" class="mt-3">
                    <h6 class="fs-7 fs-md-6 mb-2 mb-md-3 fw-bold">Pilih Metode Pembayaran:</h6>
                    <div class="vstack gap-2">
                        @foreach($metode_manual as $metode)
                            <button class="btn {{ $metode->kode == 'MANUAL_TUNAI' ? 'btn-outline-primary' : 'btn-outline-info' }} fs-7 fs-md-6 py-2"
                                    onclick="bayarManual('{{ $metode->kode }}', '{{ $metode->nama }}')">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas {{ $metode->kode == 'MANUAL_TUNAI' ? 'fa-money-bill' : 'fa-exchange-alt' }}"></i>
                                    <span>{{ $metode->nama }}</span>
                                </div>
                            </button>
                        @endforeach
                        @if($metode_online)
                            <button class="btn btn-primary fs-7 fs-md-6 py-2" onclick="bayarOnline()">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-globe"></i>
                                    <span>{{ $metode_online->nama }}</span>
                                </div>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// IIFE untuk menghindari polusi global scope
(function() {
    // State management dengan error handling
    const state = {
        selectedPembayaranId: null,
        modalInstance: null
    };

    // Fungsi untuk menyimpan ID pembayaran
    function setSelectedPembayaran(id) {
        // Hanya validasi saat setting ID baru, bukan saat reset
        if (id !== null && (id === undefined || !id)) {
            console.warn('ID pembayaran tidak valid');
            return;
        }
        state.selectedPembayaranId = id;
    }

    // Fungsi untuk mendapatkan ID pembayaran
    function getSelectedPembayaran() {
        try {
            return state.selectedPembayaranId;
        } catch (error) {
            console.error('Error dalam getSelectedPembayaran:', error);
            return null;
        }
    }

    // Fungsi untuk set modal instance
    function setModalInstance(instance) {
        state.modalInstance = instance;
    }
    // Helper: blur focus jika masih berada di dalam element (untuk mencegah aria-hidden ancestor memiliki descendant berfokus)
    function blurIfFocusedIn(rootEl) {
        try {
            const ae = document.activeElement;
            if (!ae) return;
            if (rootEl && rootEl.contains(ae)) {
                ae.blur();
                // fallback: fokuskan body agar tidak ada elemen tersembunyi yang tetap fokus
                if (document.body && document.body.focus) {
                    document.body.focus();
                }
            }
        } catch (e) {
            // abaikan
        }
    }

    // Fungsi untuk mendapatkan modal instance
    function getModalInstance() {
        return state.modalInstance;
    }

    // Fungsi untuk reset state (tanpa dispose untuk menghindari race saat hide)
    function resetState() {
        state.selectedPembayaranId = null;
        state.modalInstance = null;
    }

    // Fungsi untuk membuat badge status
    function createStatusBadge(status) {
        try {
            const badge = document.createElement('span');
            if (!badge) {
                throw new Error('Gagal membuat elemen badge');
            }

            const config = {
                success: { class: 'badge bg-success', text: 'Lunas' },
                pending: { class: 'badge bg-warning', text: 'Pending' },
                default: { class: 'badge bg-danger', text: 'Belum Lunas' }
            };

            const statusConfig = config[status] || config.default;
            badge.className = `${statusConfig.class} fs-7 fs-md-6`;
            badge.textContent = statusConfig.text;

            return badge;
        } catch (error) {
            console.error('Error dalam createStatusBadge:', error);
            // Return fallback badge jika terjadi error
            const fallbackBadge = document.createElement('span');
            fallbackBadge.className = 'badge bg-secondary fs-7 fs-md-6';
            fallbackBadge.textContent = 'Status Tidak Diketahui';
            return fallbackBadge;
        }
    }

    // Fungsi untuk menampilkan modal detail pembayaran
function showDetailPembayaran(id, bulan, nominal, status, tanggal, metode, tahun) {
        console.log('showDetailPembayaran dipanggil dari modal-detail-pembayaran.blade.php');
        console.log('Parameter:', { id, bulan, nominal, status, tanggal, metode, tahun });
        try {
            // Cek nomor HP terlebih dahulu
            @if(!auth()->user()->no_hp)
                console.log('Nomor HP belum terdaftar, menampilkan peringatan.');
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
                        const profileModal = document.getElementById('profileModal');
                        if (profileModal) {
                            console.log('Menampilkan modal profil.');
                            const modalInstance = new bootstrap.Modal(profileModal);
                            modalInstance.show();
                        } else {
                            console.warn('Elemen modal profil tidak ditemukan.');
                        }
                    }
                });
                return;
            @endif

            // Set ID pembayaran yang dipilih
            setSelectedPembayaran(id);

            // Dapatkan semua elemen yang diperlukan
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

            // Periksa apakah semua elemen yang diperlukan ada
            const missingElements = Object.entries(elements)
                .filter(([key, element]) => !element)
                .map(([key]) => key);

            if (missingElements.length > 0) {
                throw new Error(`Elemen tidak ditemukan: ${missingElements.join(', ')}`);
            }

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
                document.getElementById('btn-print-detail-wali').style.display = 'inline-block';
            }
            else {
                document.getElementById('btn-print-detail-wali').style.display = 'none';
            }

            // Tampilkan modal
const modalElement = document.getElementById('modalDetailPembayaran');
            if (!modalElement) {
                console.error('Elemen modalDetailPembayaran tidak ditemukan di DOM.');
                throw new Error('Modal element tidak ditemukan');
            }
            console.log('Elemen modal ditemukan:', modalElement);

const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);

            // Reset state saat modal ditutup dan hapus modal instance
            modalElement.addEventListener('hidden.bs.modal', () => {
                console.log('Modal disembunyikan, mereset state.');
                resetState();
                document.getElementById('btn-print-detail-wali').style.display = 'none';
            }, { once: true });

            // Pastikan tidak ada elemen di dalam modal yang tetap fokus saat proses hide (untuk mencegah warning aria-hidden)
            modalElement.addEventListener('hide.bs.modal', () => {
                blurIfFocusedIn(modalElement);
            }, { once: true });

            // Update modal instance di state
setModalInstance(modalInstance);

            console.log('Memanggil modalInstance.show().');
            modalInstance.show();
            console.log('Modal seharusnya sudah ditampilkan.');
        } catch (error) {
            console.error('Error dalam showDetailPembayaran:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat menampilkan detail pembayaran'
            });
        }
    }

    // Cetak detail pembayaran
    document.getElementById('btn-print-detail-wali').addEventListener('click', function() {
        const id = getSelectedPembayaran();
        if (!id) return;
        const url = `{{ url('wali/pembayaran') }}/${id}/pdf`;
        const bulan = (document.getElementById('detail-bulan')?.textContent || '-');
        const tahun = (document.getElementById('detail-tahun')?.textContent || '-');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Cetak Bukti Pembayaran',
                html: `Periode: <b>${bulan} ${tahun}</b><br>Buka bukti dalam format PDF?`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Cetak PDF',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) window.open(url, '_blank');
            });
        } else {
            window.open(url, '_blank');
        }
    });

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

    // Helper: muat Snap.js hanya saat diperlukan
    function loadSnapScriptIfNeeded(callback) {
        if (typeof snap !== 'undefined') {
            callback();
            return;
        }
        const existing = document.querySelector('script[data-midtrans-snap]');
        if (existing) {
            existing.addEventListener('load', () => callback());
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
        script.type = 'text/javascript';
        script.setAttribute('data-midtrans-snap', '1');
        script.setAttribute('data-client-key', '{{ config('midtrans.client_key') }}');
        script.onload = () => callback();
        script.onerror = () => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat Midtrans. Coba lagi.' });
        };
        document.body.appendChild(script);
    }

    // Fungsi pembayaran online dengan error handling
    function bayarOnline() {
        const id = getSelectedPembayaran();
        const modalInstance = getModalInstance();

        if (!id) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Data pembayaran tidak ditemukan'
            });
            return;
        }

        // Sembunyikan modal detail sebelum membuka payment gateway
        if (modalInstance) {
            try {
                console.log('Menyembunyikan modal detail sebelum membuka payment gateway.');
                // Hindari fokus tertinggal pada elemen di dalam modal sebelum proses hide
                const modalEl = document.getElementById('modalDetailPembayaran');
                if (modalEl) blurIfFocusedIn(modalEl);
                modalInstance.hide();
            } catch (error) {
                console.error('Error saat menutup modal:', error);
            }
        }

        // Proses pembayaran (Snap akan dimuat on-demand)
        bayarSPP(id);
    }

    // Fungsi untuk memproses pembayaran SPP dengan error handling
function bayarSPP(id) {
        console.log('bayarSPP dipanggil dengan ID:', id);
        // Cek nomor HP wali terlebih dahulu
        @if(!auth()->user()->no_hp)
            console.log('Nomor HP belum terdaftar untuk pembayaran online, menampilkan peringatan.');
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
                    const profileModal = document.getElementById('profileModal');
                    if (!profileModal) {
                        console.error('Modal profil tidak ditemukan');
                        return;
                    }
                    console.log('Menampilkan modal profil dari bayarSPP.');
                    const profileModalInstance = new bootstrap.Modal(profileModal);
                    profileModalInstance.show();
                }
            });
            return;
        @endif

        // Konfirmasi pembayaran
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: 'Anda akan melanjutkan ke halaman pembayaran online?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

// Tampilkan loading
            console.log('Menampilkan loading SweetAlert.');
            Swal.fire({
                title: 'Memproses Pembayaran',
                text: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Proses pembayaran
            fetch('{{ route("wali.pembayaran.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    tagihan_id: id
                })
            })
            .then(response => response.json().then(data => ({ response, data })))
.then(({ response, data }) => {
                console.log('Respon pembayaran diterima:', data);
                Swal.close();

                // Handle status 400
                if (response.status === 400) {
                    if (data.redirect_url) {
                        return Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    }

                    return Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                }

                // Handle error response
                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses pembayaran');
                }

                // Handle successful response
                if (!data.snap_token) {
                    throw new Error('Tidak dapat memulai pembayaran: Token tidak valid');
                }

                // Muat Snap on-demand, lalu jalankan pembayaran
                loadSnapScriptIfNeeded(function() {
                    console.log('Memulai pembayaran Midtrans dengan snap token:', data.snap_token);
                    snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        console.log('Pembayaran Midtrans Berhasil:', result);
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
                        console.log('Pembayaran Midtrans Pending:', result);
                        Swal.fire({
                            title: 'Pembayaran Pending',
                            text: 'Silakan selesaikan pembayaran Anda',
                            icon: 'info'
                        });
                    },
                    onError: function(result) {
                        console.error('Payment Error:', result);
                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: result.status_message || 'Terjadi kesalahan saat memproses pembayaran'
                        });
                    },
                    onClose: function() {
                        console.log('Pembayaran Midtrans Dibatalkan oleh pengguna.');
                        Swal.fire({
                            icon: 'info',
                            title: 'Pembayaran Dibatalkan',
                            text: 'Anda telah menutup halaman pembayaran'
                        });
                    }
                    });
                });
            })
            .catch(error => {
                console.error('Error dalam bayarSPP:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Gagal terhubung ke server. Silakan coba lagi.'
                });
            });
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
@endpush
