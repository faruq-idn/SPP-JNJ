document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk memeriksa apakah berada di halaman Data Santri (index atau turunannya)
    function isSantriPage() {
        return window.location.pathname.startsWith('/admin/santri');
    }

    // Auto-expand saat di halaman santri (selain index)
    if (isSantriPage() && !window.location.pathname.endsWith('/admin/santri')) {
        const santriDropdown = document.getElementById('collapseSantri');
        if (santriDropdown && !santriDropdown.classList.contains('show')) {
            new bootstrap.Collapse(santriDropdown).show();
        }
    }

    // Handle click pada menu Data Santri
    const santriMenu = document.querySelector('a[data-bs-target="#collapseSantri"]');
    if (santriMenu) {
        santriMenu.addEventListener('click', function(e) {
            // Periksa apakah yang diklik adalah icon dropdown
            if (e.target.closest('.fa-angle-down')) {
                e.preventDefault(); // Mencegah navigasi jika mengklik icon dropdown
                const santriDropdown = document.getElementById('collapseSantri');
                new bootstrap.Collapse(santriDropdown).toggle(); // Toggle collapse
            } else if (!isSantriPage()) { // Navigasi hanya jika BUKAN di halaman santri
                window.location.href = this.dataset.url;
            }
        });
    }

    // Tutup submenu jika berada di halaman index santri
    if (window.location.pathname === '/admin/santri') {
        const santriDropdown = document.getElementById('collapseSantri');
        if (santriDropdown && santriDropdown.classList.contains('show')) {
            new bootstrap.Collapse(santriDropdown).hide();
        }
    }
});
