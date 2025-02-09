document.addEventListener('DOMContentLoaded', function() {
    // Animasi ikon dropdown untuk tombol di Data Santri
    const dropdownButtons = document.querySelectorAll('button[data-bs-toggle="collapse"]');
    dropdownButtons.forEach(button => {
        // Inisialisasi state awal
        const icon = button.querySelector('.fa-angle-down');
        if (icon && button.getAttribute('aria-expanded') === 'true') {
            icon.style.transform = 'rotate(180deg)';
        }

        // Event listener untuk animasi
        button.addEventListener('click', () => {
            const icon = button.querySelector('.fa-angle-down');
            if (icon) {
                icon.style.transition = 'transform 0.2s';
                icon.style.transform = button.getAttribute('aria-expanded') === 'true' ? 
                    'rotate(0)' : 'rotate(180deg)';
            }
        });
    });

    // Handler khusus untuk Manajemen Pengguna yang masih menggunakan chevron
    document.querySelectorAll('a[data-bs-toggle="collapse"]').forEach(link => {
        const icon = link.querySelector('.fa-chevron-down');
        if (icon && link.getAttribute('aria-expanded') === 'true') {
            icon.style.transform = 'rotate(180deg)';
        }

        link.addEventListener('click', () => {
            if (icon) {
                icon.style.transition = 'transform 0.2s';
                icon.style.transform = link.getAttribute('aria-expanded') === 'true' ? 
                    'rotate(0)' : 'rotate(180deg)';
            }
        });
    });
});
