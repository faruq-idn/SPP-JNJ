// Fungsi untuk menghitung total SPP dari rincian biaya
function hitungTotalSPP(formId) {
    const prefix = formId === 'formCreateKategori' ? '' : 'edit_';
    const biayaMakan = parseInt(document.getElementById(prefix + 'biaya_makan').value) || 0;
    const biayaAsrama = parseInt(document.getElementById(prefix + 'biaya_asrama').value) || 0;
    const biayaListrik = parseInt(document.getElementById(prefix + 'biaya_listrik').value) || 0;
    const biayaKesehatan = parseInt(document.getElementById(prefix + 'biaya_kesehatan').value) || 0;
    
    const total = Math.round(biayaMakan + biayaAsrama + biayaListrik + biayaKesehatan);
    document.getElementById(prefix + 'nominal_spp').value = total;
}

// Event listener untuk input rincian biaya
document.addEventListener('DOMContentLoaded', function() {
    const createInputs = document.querySelectorAll('#formCreateKategori .rincian-biaya');
    createInputs.forEach(input => {
        input.addEventListener('input', () => hitungTotalSPP('formCreateKategori'));
    });

    const editInputs = document.querySelectorAll('#formEditKategori .rincian-biaya');
    editInputs.forEach(input => {
        input.addEventListener('input', () => hitungTotalSPP('formEditKategori'));
    });
});

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
                
                const modal = new bootstrap.Modal(document.getElementById('editKategoriModal'));
                modal.show();
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