(function() {
    // State management
    const state = {
        selectedModal: null,
        createModalInstance: null,
        editModalInstance: null
    };

    // Fungsi untuk set instance modal
    function setModalInstance(type, instance) {
        try {
            if (!instance) {
                throw new Error(`Modal instance for ${type} is null`);
            }
            state[type + 'ModalInstance'] = instance;

            // Safety check - ensure modal is properly initialized
            const modalElement = document.getElementById(`${type}KategoriModal`);
            if (!modalElement) {
                throw new Error(`Modal element for ${type} not found`);
            }

            // Add backup close handler
            const closeButton = modalElement.querySelector('.btn-close, [data-bs-dismiss="modal"]');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    try {
                        resetState(type);
                    } catch (error) {
                        console.error('Error in backup close handler:', error);
                        // Force modal cleanup as last resort
                        modalElement.style.display = 'none';
                        modalElement.classList.remove('show');
                        document.body.classList.remove('modal-open');
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) backdrop.remove();
                    }
                });
            }
        } catch (error) {
            console.error('Error setting modal instance:', error);
            throw error;
        }
    }

    // Fungsi untuk get instance modal
    function getModalInstance(type) {
        return state[type + 'ModalInstance'];
    }

    // Fungsi untuk reset state
    function resetState(type) {
        try {
            if (state[type + 'ModalInstance']) {
                // Jangan panggil Bootstrap dispose di sini untuk menghindari error internal
                state[type + 'ModalInstance'] = null;
            }
        } catch (error) {
            console.error(`Error resetting ${type} modal:`, error);

            // Force cleanup if normal disposal fails
            try {
                const modalElement = document.getElementById(`${type}KategoriModal`);
                if (modalElement) {
                    modalElement.style.display = 'none';
                    modalElement.classList.remove('show');
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                }
            } catch (e) {
                console.error('Error in force cleanup:', e);
            }
        }
    }

    // Fungsi untuk menghitung total SPP dari rincian biaya
    function hitungTotalSPP(formId) {
        try {
            const prefix = formId === 'formCreateKategori' ? '' : 'edit_';
            const biayaMakan = parseInt(document.getElementById(prefix + 'biaya_makan').value) || 0;
            const biayaAsrama = parseInt(document.getElementById(prefix + 'biaya_asrama').value) || 0;
            const biayaListrik = parseInt(document.getElementById(prefix + 'biaya_listrik').value) || 0;
            const biayaKesehatan = parseInt(document.getElementById(prefix + 'biaya_kesehatan').value) || 0;

            const total = Math.round(biayaMakan + biayaAsrama + biayaListrik + biayaKesehatan);
            document.getElementById(prefix + 'nominal_spp').value = total;
        } catch (error) {
            console.error('Error dalam hitungTotalSPP:', error);
        }
    }


    // Setup modal dengan pattern yang sama seperti modal detail pembayaran
    const init = function() {
        console.log('Initializing modals with payment modal pattern');
        try {
            // Setup create modal
            const createModal = document.getElementById('createKategoriModal');
            if (createModal) {
                // Inisialisasi modal dengan pattern yang sama seperti modal pembayaran
            let createInstance;
            try {
                createInstance = bootstrap.Modal.getInstance(createModal);
                if (!createInstance) {
                    createInstance = new bootstrap.Modal(createModal);
                }
                console.log('Create modal initialized');
                setModalInstance('create', createInstance);
            } catch (error) {
                console.error('Error initializing create modal:', error);
            }

                // Add event listener with once option to prevent duplicates
                createModal.addEventListener('hidden.bs.modal', () => {
                    try {
                        resetState('create');
                        createModal.querySelector('form').reset();
                    } catch (error) {
                        console.error('Error resetting create modal:', error);
                        // Hindari force cleanup agresif di sini
                    }
                }, { once: true });

                const createInputs = document.querySelectorAll('#formCreateKategori .rincian-biaya');
                createInputs.forEach(input => {
                    input.addEventListener('input', () => hitungTotalSPP('formCreateKategori'));
                });
            }

            // Setup edit modal
            const editModal = document.getElementById('editKategoriModal');
            if (editModal) {
            // Inisialisasi modal dengan pattern yang sama
            let editInstance;
            try {
                editInstance = bootstrap.Modal.getInstance(editModal);
                if (!editInstance) {
                    editInstance = new bootstrap.Modal(editModal);
                }
                console.log('Edit modal initialized');
                setModalInstance('edit', editInstance);
            } catch (error) {
                console.error('Error initializing edit modal:', error);
            }

                // Add event listener with once option to prevent duplicates
                editModal.addEventListener('hidden.bs.modal', () => {
                    try {
                        resetState('edit');
                        document.getElementById('riwayatTarifBody').innerHTML = '';
                    } catch (error) {
                        console.error('Error resetting edit modal:', error);
                        // Hindari force cleanup agresif di sini untuk mencegah error internal Bootstrap
                    }
                }, { once: true });

                const editInputs = document.querySelectorAll('#formEditKategori .rincian-biaya');
                editInputs.forEach(input => {
                    input.addEventListener('input', () => hitungTotalSPP('formEditKategori'));
                });
            }
        } catch (error) {
            console.error('Error during modal setup:', error);
            // Global cleanup if something goes wrong
            document.body.classList.remove('modal-open');
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
        }
    };


    // Fungsi untuk menambah kategori
    function submitCreateForm(event) {
        event.preventDefault();

        const form = event.target;
        const nominal = document.getElementById('nominal_spp').value;

        // Validasi nominal
        if (isNaN(nominal) || nominal <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Input Tidak Valid',
                text: 'Nominal SPP harus berupa angka positif'
            });
            return;
        }

        const formData = new FormData(form);

        fetch('/admin/kategori', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById('createKategoriModal');
                const inst = el ? (bootstrap.Modal.getInstance(el) || null) : null;
                if (inst) {
                    try { inst.hide(); } catch(e) { console.warn('Hide create modal ignored:', e); }
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan! Silakan coba lagi.'
            });
        });
    }

    function editKategori(id) {
        fetch(`/admin/kategori/${id}/get-data`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const kategori = data.data;
                    document.getElementById('edit_kategori_id').value = kategori.id;
                    document.getElementById('edit_nama').value = kategori.nama;
                    document.getElementById('edit_keterangan').value = kategori.keterangan;
                    document.getElementById('edit_biaya_makan').value = Math.round(kategori.biaya_makan);
                    document.getElementById('edit_biaya_asrama').value = Math.round(kategori.biaya_asrama);
                    document.getElementById('edit_biaya_listrik').value = Math.round(kategori.biaya_listrik);
                    document.getElementById('edit_biaya_kesehatan').value = Math.round(kategori.biaya_kesehatan);

                    // Hitung total SPP
                    hitungTotalSPP('formEditKategori');

                    // Tampilkan riwayat tarif
                    if (kategori.riwayat_tarif && kategori.riwayat_tarif.length > 0) {
                        const riwayatHtml = kategori.riwayat_tarif
                            .sort((a, b) => new Date(b.berlaku_mulai) - new Date(a.berlaku_mulai))
                            .map((tarif, index) => `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID', {maximumFractionDigits: 0}).format(tarif.biaya_makan)}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID', {maximumFractionDigits: 0}).format(tarif.biaya_asrama)}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID', {maximumFractionDigits: 0}).format(tarif.biaya_listrik)}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID', {maximumFractionDigits: 0}).format(tarif.biaya_kesehatan)}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID', {maximumFractionDigits: 0}).format(tarif.nominal)}</td>
                                    <td>${new Date(tarif.berlaku_mulai).toLocaleDateString('id-ID')}</td>
                                    <td>${tarif.keterangan || '-'}</td>
                                </tr>
                            `).join('');
                        document.getElementById('riwayatTarifBody').innerHTML = riwayatHtml;
                    } else {
                        document.getElementById('riwayatTarifBody').innerHTML = `
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada riwayat perubahan tarif</td>
                            </tr>
                        `;
                    }

                    let modalInstance = getModalInstance('edit');
                    try {
                        if (!modalInstance) {
                            const el = document.getElementById('editKategoriModal');
                            modalInstance = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
                            setModalInstance('edit', modalInstance);
                        }
                        modalInstance.show();
                    } catch (e) {
                        console.error('Gagal menampilkan edit modal:', e);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal mengambil data kategori!'
                });
            });
    }

    function submitEditForm(event) {
        event.preventDefault();

        const id = document.getElementById('edit_kategori_id').value;
        const form = event.target;
        const formData = new FormData(form);

        fetch(`/admin/kategori/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById('editKategoriModal');
                const inst = el ? (bootstrap.Modal.getInstance(el) || null) : null;
                if (inst) {
                    try { inst.hide(); } catch(e) { console.warn('Hide edit modal ignored:', e); }
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan! Silakan coba lagi.'
            });
        });
    }

    // Untuk kategori reguler (3x konfirmasi)
    function confirmDeleteReguler(event) {
        event.preventDefault();
        const form = event.target;

    // Konfirmasi pertama
    Swal.fire({
        title: 'Konfirmasi Pertama',
        text: "Apakah Anda yakin ingin menghapus kategori Reguler?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Konfirmasi kedua
            Swal.fire({
                title: 'Konfirmasi Kedua',
                text: "Menghapus kategori akan menghapus semua data terkait!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, saya mengerti',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Konfirmasi ketiga
                    Swal.fire({
                        title: 'Konfirmasi Terakhir',
                        text: "Tindakan ini tidak dapat dibatalkan!",
                        icon: 'warning',
                        input: 'text',
                        inputPlaceholder: 'Ketik "HAPUS" untuk konfirmasi',
                        inputValidator: (value) => {
                            if (value !== 'HAPUS') {
                                return 'Anda harus mengetik "HAPUS"';
                            }
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus sekarang!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        }
    });

    return false;
}

    // Untuk kategori lainnya (2x konfirmasi)
    function confirmDelete(event) {
    event.preventDefault();
    const form = event.target;
    const kategoriNama = form.closest('tr').querySelector('td:first-child').textContent;

    // Konfirmasi pertama
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus kategori "${kategoriNama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Konfirmasi kedua
            Swal.fire({
                title: 'Konfirmasi Terakhir',
                text: "Menghapus kategori akan menghapus semua data terkait dan tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });

        return false;
    }

    // Helper function untuk membersihkan semua modal yang mungkin masih terbuka
    function cleanupAllModals() {
        try {
            const modalElements = document.querySelectorAll('.modal');
            modalElements.forEach(modal => {
                const modalId = modal.id;
                if (modalId) {
                    const type = modalId.replace('KategoriModal', '').toLowerCase();
                    resetState(type);
                }
            });

            // Cleanup any stray backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());

            // Reset body
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
        } catch (error) {
            console.error('Error cleaning up modals:', error);
        }
    }

    // Event listeners untuk cleanup dengan focus pada Bootstrap modal behavior
    window.addEventListener('beforeunload', () => {
        try {
            cleanupAllModals();
        } catch (error) {
            console.error('Error during modal cleanup:', error);
        }
    });

    // Catatan: Handler global z-index/backdrop dikelola secara app-wide di layouts.
    // File ini tidak lagi memanipulasi backdrop/z-index secara global untuk menghindari konflik.

    // Expose functions ke window object
    Object.assign(window, {
        submitCreateForm,
        submitEditForm,
        editKategori,
        confirmDelete,
        confirmDeleteReguler,
        cleanupAllModals
    });

    // Attach initialization and ensure cleanup on page unload
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Cleanup on page unload
    window.addEventListener('unload', () => {
        document.removeEventListener('DOMContentLoaded', init);
        cleanupAllModals();
    });
})();
