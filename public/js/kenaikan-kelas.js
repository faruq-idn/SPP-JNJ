function kenaikanKelas() {
    // Tampilkan modal
    const modal = new bootstrap.Modal(document.getElementById('modalKenaikanKelas'));
    modal.show();
}

function prosesKenaikanKelas() {
    // Tampilkan loading state
    const button = document.querySelector('#modalKenaikanKelas .btn-success');
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

    // Kirim request ke server
    fetch('/admin/santri/kenaikan-kelas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            text: error.message || 'Terjadi kesalahan saat memproses kenaikan kelas'
        });
    })
    .finally(() => {
        // Reset loading state
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-graduation-cap me-1"></i> Proses Kenaikan Kelas';
        bootstrap.Modal.getInstance(document.getElementById('modalKenaikanKelas')).hide();
    });
}

function batalKenaikan() {
    const checkboxes = document.querySelectorAll('input[name="santri_ids[]"]:checked');
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);

    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Pilih santri yang akan dibatalkan kenaikan kelasnya'
        });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Pembatalan',
        text: `Batalkan kenaikan kelas untuk ${selectedIds.length} santri?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/admin/santri/batal-kenaikan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    santri_ids: selectedIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: error.message || 'Terjadi kesalahan saat membatalkan kenaikan kelas'
                });
            });
        }
    });
}
