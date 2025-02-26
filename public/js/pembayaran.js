// Gunakan IIFE untuk menghindari global scope pollution
const PaymentManager = (function() {
    const PAYMENT_STATUS = {
        SUCCESS: 'success',
        PENDING: 'pending',
        UNPAID: 'unpaid'
    };

    class PaymentManagerClass {
        static get PAYMENT_STATUS() {
            return PAYMENT_STATUS;
        }

        constructor() {
            this.initializeEventListeners();
        }

    // UI Update methods
    static getNamaBulan(bulan) {
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        // Handle both numeric and string input
        if (typeof bulan === 'number' || !isNaN(parseInt(bulan))) {
            const index = parseInt(bulan) - 1;
            return months[index] || '-';
        }
        return bulan || '-';
    }

    updateModal(data) {
        console.log('updateModal received data:', data);
        
        const {
            id, bulan, nominal, tahun, status, tanggal, metode, paymentData
        } = data;

        console.log('Extracted values:', {
            id, bulan, nominal, tahun, status, tanggal, metode
        });

        // Jangan tampilkan error jika ini pembayaran baru (id kosong)
        if (typeof id !== 'undefined' && id !== '' && id !== null) {
            if (!id) {
                console.warn('ID is invalid:', id);
                this.showError('Pembayaran tidak ditemukan atau sudah dihapus');
                $('#modalPembayaran').modal('hide');
                return;
            }
        }

        const nominalValue = PaymentManager.parseNominal(nominal);
        
        // Update form values
        $('#pembayaran_id').val(id);
        $('#detail-tahun').text(tahun || new Date().getFullYear());
        $('#detail-bulan').text(PaymentManager.getNamaBulan(bulan));
        $('#detail-nominal').text(PaymentManager.formatCurrency(nominalValue).replace('Rp ', ''));
        $('[name="nominal"]').val(nominalValue);

        // Update status badge
        const statusNormalized = status || PaymentManager.PAYMENT_STATUS.UNPAID;
        const statusClass = this.getStatusClass(statusNormalized);
        const statusText = this.getStatusText(statusNormalized);
        $('#detail-status').html(`<span class="badge ${statusClass}" data-status="${statusNormalized}">${statusText}</span>`);

        // Handle form visibility based on status
        this.toggleFormDisplay(status !== PaymentManager.PAYMENT_STATUS.SUCCESS);
        
        // Update payment details if success
        if (status === PaymentManager.PAYMENT_STATUS.SUCCESS) {
            $('#detail-tanggal').text(tanggal);
            $('#detail-metode').text(metode);
            $('#detail-keterangan').text(paymentData?.keterangan || '-');
        }

        // Handle online payment details if success
        const $onlinePaymentInfo = $('#online-payment-info');
        
        if (status === PaymentManager.PAYMENT_STATUS.SUCCESS) {
            $onlinePaymentInfo.show();
            
            if (paymentData?.order_id) {
                // Tampilkan detail pembayaran online
                $('#detail-order-id').text(paymentData.order_id);
                $('#detail-transaction-id').text(paymentData.transaction_id || '-');
                $('#detail-payment-type').text(paymentData.payment_type || '-');

                if (paymentData.payment_details) {
                    $('#payment-details-section').show();
                    $('#detail-payment-details').html(paymentData.payment_details);
                } else {
                    $('#payment-details-section').hide();
                }
            } else {
                // Tampilkan pesan pembayaran manual
                $('#online-payment-info').html(`
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-info-circle mb-2"></i>
                        <p class="mb-0">Pembayaran ini dilakukan secara manual</p>
                    </div>
                `);
            }
        } else {
            $onlinePaymentInfo.hide();
        }

        // Handle delete button visibility
        this.updateDeleteButton(id, status);
    }

    // Event handlers
    initializeEventListeners() {
        $('#formPembayaran').on('submit', (e) => this.handleFormSubmit(e));
        $('#modalPembayaran').on('hidden.bs.modal', () => this.handleModalClose());
    }

    handleFormSubmit(e) {
        e.preventDefault();
        const form = $(e.currentTarget);
        const data = {};
        
        try {
            form.serializeArray().forEach(item => {
                if (item.name === 'nominal') {
                    const nominalValue = PaymentManager.parseNominal(item.value);
                    if (nominalValue === 0) {
                        throw new Error('Nominal pembayaran tidak valid');
                    }
                    data[item.name] = nominalValue;
                } else {
                    data[item.name] = item.value;
                }
            });

            this.submitPayment(data);
        } catch (error) {
            this.showError(error.message);
        }
    }

    handleModalClose() {
        const form = document.getElementById('formPembayaran');
        if (form) {
            form.reset();
            $('#pembayaran_id').val('');
        }
        
        // Reset main displays
        $('#pembayaran-info').hide();
        $('#formPembayaran').hide();
        $('#detail-nominal').text(PaymentManager.formatCurrency(0).replace('Rp ', ''));
        $('#btn-delete').hide();

        // Reset online payment content
        const $onlinePaymentInfo = $('#online-payment-info');
        $onlinePaymentInfo.hide();
        $onlinePaymentInfo.html(`
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%">Order ID</td>
                    <td><span id="detail-order-id" class="font-monospace">-</span></td>
                </tr>
                <tr>
                    <td>Transaction ID</td>
                    <td><span id="detail-transaction-id" class="font-monospace">-</span></td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td><span id="detail-payment-type" class="badge bg-secondary">-</span></td>
                </tr>
            </table>
            <div class="mt-3" id="payment-details-section" style="display: none;">
                <h6 class="fw-bold mb-2">Detail Transaksi</h6>
                <div id="detail-payment-details" class="bg-light p-3 rounded"></div>
            </div>
        `);
    }

    // Button Management
    updateDeleteButton(id, status) {
        const $btnDelete = $('#btn-delete');
        if (window.role === 'admin' && id) {
            $btnDelete.show();
            $btnDelete.off('click').on('click', () => {
                const bulan = $('#detail-bulan').text();
                const tahun = $('#detail-tahun').text();
                this.confirmDelete(id, bulan, tahun, status);
            });
        } else {
            $btnDelete.hide();
        }
    }

    confirmDelete(id, bulan, tahun, status) {
        if (!id) {
            this.showError('ID pembayaran tidak valid');
            return;
        }

        const statusText = status === PaymentManager.PAYMENT_STATUS.SUCCESS ? 'yang sudah lunas' : 'yang belum lunas';

        Swal.fire({
            title: 'Reset Status Pembayaran?',
            text: `Anda yakin ingin mereset pembayaran ${statusText} untuk bulan ${bulan} ${tahun}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                this.deletePayment(id);
            }
        });
    }

    // API Interactions
    async submitPayment(data) {
        const pembayaranId = data.pembayaran_id;
        let baseUrl = window.role === 'admin' ? '/admin' : '/petugas';
        
        const url = pembayaranId ?
            `${baseUrl}/pembayaran/${pembayaranId}/verifikasi` :
            `${baseUrl}/pembayaran`;

        try {
            const response = await $.ajax({
                url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data,
                dataType: 'json'
            });

            if (response.status === PaymentManager.PAYMENT_STATUS.SUCCESS) {
                $('#modalPembayaran').modal('hide');
                this.showSuccess('Pembayaran berhasil diverifikasi', true);
            } else {
                throw new Error(response.message);
            }
        } catch (error) {
            this.showError(error.message || 'Terjadi kesalahan saat memproses pembayaran');
        }
    }

    async deletePayment(id) {
        if (!id) {
            this.showError('ID pembayaran tidak valid');
            return;
        }

        try {
            const response = await $.ajax({
                url: `/admin/pembayaran/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json'
            });

            if (response.status === 'success') {
                $('#modalPembayaran').modal('hide');
                this.showSuccess(response.message, true);
            } else {
                throw new Error(response.message);
            }
        } catch (error) {
            const message = error.responseJSON?.message || 'Terjadi kesalahan saat mereset pembayaran';
            this.showError(message);
        }
    }

    // Helper methods
    static formatNominal(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    }

    static formatCurrency(value) {
        if (value === null || value === undefined || isNaN(value)) {
            return 'Rp 0';
        }
        const nominalValue = parseInt(value);
        return `Rp ${PaymentManager.formatNominal(nominalValue)}`;
    }

    static parseNominal(value) {
        try {
            if (!value || value === '-') return 0;
            
            // Bersihkan string dari karakter non-digit dan Rp
            const cleanValue = String(value)
                .replace(/[Rp\s.]/g, '')  // Hapus Rp, spasi, dan titik
                .replace(/[^\d]/g, '');   // Hapus karakter non-digit
            
            const nominalValue = parseInt(cleanValue);
            
            if (isNaN(nominalValue)) {
                console.warn('Nilai nominal tidak valid:', value);
                return 0;
            }
            
            if (nominalValue > 1000000000) {
                console.warn('Nilai nominal terlalu besar:', nominalValue);
                return 0;
            }
            
            return nominalValue;
        } catch (e) {
            console.warn('Error parsing nominal:', e, 'value:', value);
            return 0;
        }
    }

    getStatusClass(status) {
        return status === PaymentManager.PAYMENT_STATUS.SUCCESS ? 'bg-success' : 
               (status === PaymentManager.PAYMENT_STATUS.PENDING ? 'bg-warning' : 'bg-danger');
    }

    getStatusText(status) {
        return status === PaymentManager.PAYMENT_STATUS.SUCCESS ? 'Lunas' : 
               (status === PaymentManager.PAYMENT_STATUS.PENDING ? 'Pending' : 'Belum Lunas');
    }

    showSuccess(message, reload = false) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            if (reload) location.reload();
        });
    }

    showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            showConfirmButton: true
        });
    }

    toggleFormDisplay(showForm) {
        const $form = $('#formPembayaran');
        const $info = $('#pembayaran-info');
        
        if (showForm) {
            $form.show();
            $info.hide();
        } else {
            $form.hide();
            $info.show();
        }
    }
}

    // Return class instance
    return PaymentManagerClass;
})();

// Initialize payment manager
const paymentManager = new PaymentManager();

// Expose necessary functions to window object
Object.assign(window, {
    showDetail: function(id, bulan, nominal, tahun, status, tanggal, metode, paymentDataString) {
        try {
            let paymentData = null;
            
            // Konversi paymentDataString menjadi objek jika valid
            if (paymentDataString) {
                if (typeof paymentDataString === 'string') {
                    try {
                        paymentData = JSON.parse(paymentDataString);
                    } catch (e) {
                        console.warn('Invalid payment data string:', paymentDataString);
                    }
                } else if (typeof paymentDataString === 'object') {
                    paymentData = paymentDataString;
                }
            }

            const modal = new bootstrap.Modal(document.getElementById('modalPembayaran'));
            
            console.log('Sending to updateModal:', {
                id, bulan, nominal, tahun, status, tanggal, metode, paymentData
            });
            
            paymentManager.updateModal({
                id, bulan, nominal, tahun, status, tanggal, metode, paymentData
            });
            
            modal.show();
        } catch (error) {
            console.error('Error in showDetail:', error);
            paymentManager.showError('Terjadi kesalahan saat menampilkan detail pembayaran');
        }
    },

    verifikasiPembayaran: function(id, bulan, nominal) {
        if (!id) {
            paymentManager.showError('ID pembayaran tidak valid');
            return;
        }

        const row = $(`button[onclick*="verifikasiPembayaran('${id}',"]`).closest('tr');
        let nominalValue;
        
        if (nominal) {
            nominalValue = PaymentManager.parseNominal(nominal);
        } else {
            const nominalText = row.find('td:eq(2)').text().trim();
            nominalValue = PaymentManager.parseNominal(nominalText);
        }
        
        if (nominalValue === 0) {
            paymentManager.showError('Nominal tidak valid atau pembayaran tidak ditemukan');
            return;
        }
        
        const tahun = $('.tab-pane.active').attr('id')?.replace('tahun-', '') ||
                     row.closest('.tab-pane').attr('id')?.replace('tahun-', '') ||
                     new Date().getFullYear();

        const baseUrl = window.role === 'admin' ? '/admin' : '/petugas';
        const url = `${baseUrl}/pembayaran/${id}/check-status`;
        
        console.log('Checking payment status:', {
            role: window.role,
            baseUrl,
            url,
            id
        });

        $.get(url)
            .done(function(response) {
                console.log('Check status response:', response);
                if (response.status === 'success') {
                    const bulanName = PaymentManager.getNamaBulan(bulan);
                    $('#bulan').val(bulanName);
                    $('#pembayaran_id').val(id);
                    showDetail(id, bulanName, nominalValue, tahun, PaymentManager.PAYMENT_STATUS.PENDING, '-', '-');
                } else {
                    paymentManager.showError('Pembayaran tidak ditemukan atau sudah dihapus');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Check status error:', {
                    status: textStatus,
                    error: errorThrown,
                    response: jqXHR.responseText
                });
                paymentManager.showError(`Gagal memeriksa status pembayaran: ${textStatus}`);
            });
    },

    tambahPembayaran: function(tahun, bulan, nominal, santriId) {
        const nominalValue = PaymentManager.parseNominal(nominal);
        if (nominalValue === 0) {
            paymentManager.showError('Nominal pembayaran tidak valid');
            return;
        }

        const bulanName = PaymentManager.getNamaBulan(bulan);
        const $form = $('#formPembayaran');
        
        if (!$('#santri_id').length) {
            $form.append(`
                <input type="hidden" name="santri_id" id="santri_id" value="${santriId}">
                <input type="hidden" name="tahun" value="${tahun}">
                <input type="hidden" name="bulan" value="${bulanName}">
                <input type="hidden" name="nominal" value="${nominalValue}">
            `);
        } else {
            $('#santri_id').val(santriId);
            $('[name="tahun"]').val(tahun);
            $('[name="bulan"]').val(bulanName);
            $('[name="nominal"]').val(nominalValue);
        }
        
        $('#detail-tahun').text(tahun);
        $('#detail-bulan').text(bulanName);
        $('#detail-nominal').text(PaymentManager.formatCurrency(nominalValue).replace('Rp ', ''));
        $('#detail-status').html(`<span class="badge bg-warning">Pending</span>`);
        
        $('#modalPembayaran').modal('show');
        $('#pembayaran-info').hide();
        $form.show();
        
        $('#modalPembayaran').one('shown.bs.modal', () => {
            $('[name="metode_pembayaran_id"]').focus();
        });
    },

    confirmDeletePembayaran: function(id, bulan, tahun) {
        if (!id) {
            paymentManager.showError('ID pembayaran tidak valid');
            return;
        }

        const row = $(`button[onclick*="${id}"]`).closest('tr');
        const monthName = row.find('td:first').text().trim();
        const status = row.find('.badge').hasClass('bg-success')
            ? PaymentManager.PAYMENT_STATUS.SUCCESS
            : PaymentManager.PAYMENT_STATUS.UNPAID;

        paymentManager.confirmDelete(id, monthName, tahun, status);
    }
});
