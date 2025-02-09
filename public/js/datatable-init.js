// Inisialisasi DataTable untuk halaman yang membutuhkan
document.addEventListener('DOMContentLoaded', function() {
    const dataTable = document.getElementById('dataTable');
    if (dataTable) {
        $(dataTable).DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            order: [[1, 'asc']], // Urutkan berdasarkan nama
            columnDefs: [{
                targets: -1, // Kolom terakhir (aksi)
                orderable: false,
                searchable: false
            }],
            responsive: true,
            drawCallback: function() {
                // Pastikan event handler untuk baris yang dapat diklik
                $('#dataTable tbody tr').off('click').on('click', function(e) {
                    // Jika yang diklik adalah tombol atau link di dalam kolom aksi, biarkan event default
                    if ($(e.target).closest('td:last-child').length > 0) {
                        return;
                    }
                    // Ambil URL dari data attribute atau generate dari ID
                    const url = $(this).find('td:first').attr('onclick').match(/'([^']+)'/)[1];
                    window.location = url;
                });
            }
        });
    }
});
