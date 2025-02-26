$(document).ready(function() {
    const table = $('#dataTable');
    if (table.length) {
        table.DataTable({
            language: {
                url: '/vendor/datatables/i18n/id.json'
            },
            dom: '<"d-flex justify-content-between align-items-center mb-3"l<"ml-2"f>>rtip',
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                ['5', '10', '25', '50', 'Semua']
            ],
            order: [[1, 'asc']], // Urutkan berdasarkan nama
            columnDefs: [{
                targets: -1, // Kolom terakhir (aksi)
                orderable: false,
                searchable: false
            }],
            responsive: true
        });
    }
});
