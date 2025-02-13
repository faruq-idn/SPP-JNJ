let currentPembayaranId = null;
let currentBulan = '';
let currentTahun = '';

function showDetail(id, bulan, nominal, tahun, status, tanggal, metode, paymentDataString = null) {
    let paymentData = null;
    if (paymentDataString) {
        try {
            paymentData = typeof paymentDataString === 'string' ? JSON.parse(paymentDataString) : paymentDataString;
        } catch (e) {
            console.warn('Failed to parse payment data:', e);
        }
    }
    const modalElement = document.getElementById('modalPembayaran');
    const modal = new bootstrap.Modal(modalElement);
    
    // Reset form dan info sebelum menampilkan modal
    const pembayaranInfo = document.getElementById('pembayaran-info');
    const formPembayaran = document.getElementById('formPembayaran');
    const selectMetode = formPembayaran.querySelector('[name="metode_pembayaran_id"]');
    const textareaKeterangan = formPembayaran.querySelector('[name="keterangan"]');
    const inputId = document.getElementById('pembayaran_id');
    
    // Set current values
    currentPembayaranId = id;
    currentBulan = bulan;
    currentTahun = tahun;
    
    // Update konten modal tab informasi umum
    document.getElementById('modalTitle').textContent = 'Detail Pembayaran SPP';
    document.getElementById('detail-bulan').textContent = bulan;
    document.getElementById('detail-tahun').textContent = tahun || new Date().getFullYear();
    document.getElementById('detail-nominal').textContent = nominal.toLocaleString('id-ID');
    
    // Update status dengan badge
    const statusElement = document.getElementById('detail-status');
    let statusClass = status === PAYMENT_STATUS.SUCCESS ? 'bg-success' : 
                     (status === PAYMENT_STATUS.PENDING ? 'bg-warning' : 'bg-danger');
    let statusText = status === PAYMENT_STATUS.SUCCESS ? 'Lunas' : 
                    (status === PAYMENT_STATUS.PENDING ? 'Pending' : 'Belum Lunas');
    statusElement.innerHTML = `<span class="badge ${statusClass}">${statusText}</span>`;
    
    // Reset form fields
    selectMetode.value = '';
    textareaKeterangan.value = '';
    inputId.value = id;

    // Update online payment tab visibility and content
    const tabOnline = document.getElementById('tab-online');
    const noOnlinePayment = document.getElementById('no-online-payment');
    const onlinePaymentInfo = document.getElementById('online-payment-info');
    
    if (paymentData) {
        tabOnline.classList.remove('d-none');
        noOnlinePayment.classList.add('d-none');
        onlinePaymentInfo.classList.remove('d-none');
        
        // Update online payment details
        document.getElementById('detail-order-id').textContent = paymentData.order_id || '-';
        document.getElementById('detail-transaction-id').textContent = paymentData.transaction_id || '-';
        document.getElementById('detail-payment-type').textContent = paymentData.payment_type || '-';
        
        // Format payment details
        const detailsContainer = document.getElementById('detail-payment-details');
        if (paymentData.payment_details) {
            try {
                const details = typeof paymentData.payment_details === 'string' 
                    ? JSON.parse(paymentData.payment_details) 
                    : paymentData.payment_details;
                
                detailsContainer.innerHTML = formatPaymentDetails(details);
            } catch (e) {
                detailsContainer.innerHTML = '<div class="text-muted">Detail pembayaran tidak tersedia</div>';
            }
        } else {
            detailsContainer.innerHTML = '<div class="text-muted">Detail pembayaran tidak tersedia</div>';
        }
    } else {
        noOnlinePayment.classList.remove('d-none');
        onlinePaymentInfo.classList.add('d-none');
    }
    
    if (status === PAYMENT_STATUS.SUCCESS) {
        pembayaranInfo.style.display = 'block';
        formPembayaran.style.display = 'none';
        document.getElementById('detail-tanggal').textContent = tanggal;
        document.getElementById('detail-metode').textContent = metode;
        document.getElementById('detail-keterangan').textContent = paymentData?.keterangan || '-';
    } else {
        pembayaranInfo.style.display = 'none';
        formPembayaran.style.display = 'block';
    }

    modal.show();
    
    if (!status || status !== PAYMENT_STATUS.SUCCESS) {
        setTimeout(() => selectMetode.focus(), 500);
    }
}

function formatPaymentDetails(details, level = 0) {
    if (typeof details !== 'object' || details === null) {
        return `<span class="text-muted">${details === null ? '-' : details}</span>`;
    }

    const indent = '  '.repeat(level);
    let html = '<div class="payment-details">';

    for (const [key, value] of Object.entries(details)) {
        const formattedKey = key.replace(/_/g, ' ').toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
        
        if (typeof value === 'object' && value !== null) {
            html += `<div class="mb-2">
                <strong>${formattedKey}:</strong>
                ${formatPaymentDetails(value, level + 1)}
            </div>`;
        } else {
            html += `<div class="mb-1">
                <span class="text-muted">${formattedKey}:</span> 
                <span class="ms-2">${value || '-'}</span>
            </div>`;
        }
    }

    html += '</div>';
    return html;
}

function verifikasiPembayaran(id, bulan, nominal) {
    // Get the current year from the active tab
    const activeTab = $('.tab-pane.active');
    const tahun = activeTab.attr('id').replace('tahun-', '');
    
    // Reset any previous form data
    $('#formPembayaran')[0].reset();
    
    // Show modal in verification mode
    showDetail(id, bulan, nominal, tahun, PAYMENT_STATUS.PENDING, '-', '-');
    
    // Ensure form is displayed and info is hidden
    $('#pembayaran-info').hide();
    $('#formPembayaran').show();
    
    // Set the payment ID
    $('#pembayaran_id').val(id);
    
    // Focus on metode pembayaran select
    setTimeout(() => {
        $('[name="metode_pembayaran_id"]').focus();
    }, 500);
}

function confirmDeletePembayaran() {
    Swal.fire({
        title: 'Hapus Pembayaran?',
        text: `Anda yakin ingin menghapus pembayaran bulan ${currentBulan} ${currentTahun}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/${window.role}/pembayaran/${currentPembayaranId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pembayaran berhasil dihapus',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    const error = xhr.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error?.message || 'Terjadi kesalahan saat menghapus pembayaran'
                    });
                }
            });
        }
    });
}

// Handle form submission
$(document).ready(function() {
    // Reset form saat modal ditutup
    $('#modalPembayaran').on('hidden.bs.modal', function() {
        document.getElementById('formPembayaran').reset();
        $('#pembayaran_id').val('');
    });

    $('#formPembayaran').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const pembayaranId = form.find('[name="pembayaran_id"]').val();
        const formData = form.serializeArray();
        const data = {};
        
        formData.forEach(item => {
            data[item.name] = item.value;
        });

        // Kirim request ke endpoint verifikasi
        $.ajax({
            url: `/${window.role}/santri/pembayaran/${pembayaranId}/verifikasi`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.status === PAYMENT_STATUS.SUCCESS) {
                    // Hide modal before updating UI
                    $('#modalPembayaran').modal('hide');
                    
                    // Update tampilan tabel
                    const row = $(`button[onclick*="verifikasiPembayaran('${pembayaranId}',"]`).closest('tr');
                    
                    // Update status cell
                    row.find('td:eq(4)').html(`
                        <span class="badge bg-success">Lunas</span>
                    `);
                    
                    // Update metode cell
                    row.find('td:eq(3)').html(`
                        <span class="badge bg-info">${response.data.metode}</span>
                    `);
                    
                    // Update tanggal bayar cell
                    row.find('td:eq(1)').text(response.data.tanggal_bayar);

                    // Format payment info for detail button
                    const paymentInfo = {
                        order_id: null,
                        transaction_id: null,
                        payment_type: 'Manual',
                        payment_details: null,
                        keterangan: response.data.keterangan || ''
                    };

                    // Update tombol aksi
                    row.find('td:last-child .btn-group').html(`
                        <button class="btn btn-info" onclick="showDetail('${response.data.id}', '${row.find('td:first').text()}', ${row.find('td:eq(2)').text().replace(/[^\d]/g, '')}, '${currentTahun}', '${PAYMENT_STATUS.SUCCESS}', '${response.data.tanggal_bayar}', '${response.data.metode}', '${JSON.stringify(paymentInfo).replace(/'/g, "\\'")}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        ${window.role === 'admin' ? `
                        <button class="btn btn-danger" onclick="confirmDeletePembayaran()">
                            <i class="fas fa-trash"></i>
                        </button>` : ''}
                    `);

                    // Update badge status di tab
                    const currentTab = row.closest('.tab-pane');
                    const tahun = currentTab.attr('id').replace('tahun-', '');
                    const tabLink = $(`.nav-link[href="#tahun-${tahun}"]`);
                    const badge = tabLink.find('.badge');
                    const [lunas, total] = badge.text().split('/');
                    badge.text(`${parseInt(lunas) + 1}/${total}`);
                    
                    if (parseInt(lunas) + 1 === parseInt(total)) {
                        badge.removeClass('bg-warning bg-danger').addClass('bg-success');
                    } else {
                        badge.removeClass('bg-danger').addClass('bg-warning');
                    }

                    // Tampilkan notifikasi
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    throw new Error(response.message);
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error?.message || 'Terjadi kesalahan saat memproses pembayaran'
                });
            }
        });
    });

    // Add some styling to payment details
    const style = document.createElement('style');
    style.textContent = `
        .payment-details {
            font-size: 0.9rem;
        }
        .payment-details .payment-details {
            margin-left: 1rem;
            margin-top: 0.5rem;
            padding-left: 1rem;
            border-left: 2px solid #e9ecef;
        }
    `;
    document.head.appendChild(style);
});
