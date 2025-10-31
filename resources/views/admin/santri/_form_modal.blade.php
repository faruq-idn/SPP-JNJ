{{-- Modal Form Tambah/Edit Santri --}}
<div class="modal fade" id="santriFormModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="santriForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    
                    <div class="row g-3">
                        {{-- NISN --}}
                        <div class="col-md-6">
                            <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nisn" name="nisn" required>
                            <div class="invalid-feedback" id="nisn-error"></div>
                        </div>

                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                            <div class="invalid-feedback" id="nama-error"></div>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <div class="invalid-feedback" id="jenis_kelamin-error"></div>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                            <div class="invalid-feedback" id="tanggal_lahir-error"></div>
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2" required></textarea>
                            <div class="invalid-feedback" id="alamat-error"></div>
                        </div>

                        {{-- Jenjang --}}
                        <div class="col-md-6">
                            <label for="jenjang" class="form-label">Jenjang <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenjang" name="jenjang" required>
                                <option value="">Pilih Jenjang</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                            </select>
                            <div class="invalid-feedback" id="jenjang-error"></div>
                        </div>

                        {{-- Kelas --}}
                        <div class="col-md-6">
                            <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select class="form-select" id="kelas" name="kelas" required disabled>
                                <option value="">Pilih Kelas</option>
                            </select>
                            <div class="invalid-feedback" id="kelas-error"></div>
                        </div>

                        {{-- Tanggal Masuk --}}
                        <div class="col-md-6">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
                            <div class="invalid-feedback" id="tanggal_masuk-error"></div>
                        </div>

                        {{-- Kategori --}}
                        <div class="col-md-6">
                            <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" id="kategori_id" name="kategori_id" required>
                                <option value="">Pilih Kategori</option>
                                {{-- Options will be populated by AJAX --}}
                            </select>
                            <div class="invalid-feedback" id="kategori_id-error"></div>
                        </div>

                        {{-- Wali --}}
                        <div class="col-12">
                            <label for="wali_id" class="form-label">Wali Santri <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="wali_id" name="wali_id" required>
                                <option value="">Pilih Wali Santri</option>
                            </select>
                            <div class="invalid-feedback" id="wali_id-error"></div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-aktif</option>
                            </select>
                            <div class="invalid-feedback" id="status-error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitForm">
                    <i class="fas fa-save me-1"></i>
                    <span>Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let santriFormSetupDone = false;
const kelasOptions = {
    'SMP': ['7A', '7B', '8A', '8B', '9A', '9B'],
    'SMA': ['10A', '10B', '11A', '11B', '12A', '12B']
};

function initializeForm() {
    if (santriFormSetupDone) return;
    santriFormSetupDone = true;

    // Initialize Select2 (once)
    $('#wali_id').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#santriFormModal'),
        placeholder: 'Pilih Wali Santri',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.users.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    type: 'wali'
                };
            },
            processResults: function(data) {
                return { results: data };
            },
            cache: true
        },
        minimumInputLength: 2
    });

    // Handle jenjang change (once)
    $('#jenjang').on('change.santriJenjang', function() {
        const jenjang = $(this).val();
        const kelasSelect = $('#kelas');
        kelasSelect.html('<option value="">Pilih Kelas</option>');
        kelasSelect.prop('disabled', !jenjang);
        if (jenjang && kelasOptions[jenjang]) {
            kelasOptions[jenjang].forEach(kelas => {
                kelasSelect.append(`<option value="${kelas}">${kelas}</option>`);
            });
        }
    });

    // Reset validation on input (once, namespaced)
    $('#santriForm input, #santriForm select, #santriForm textarea')
        .off('.santriValidation')
        .on('input.santriValidation change.santriValidation', function() {
            $(this).removeClass('is-invalid');
            $(`#${this.id}-error`).text('');
        });
}

// Handle form submission
$('#submitForm').click(function() {
    const form = $('#santriForm');
    const formData = new FormData(form[0]);
    const url = form.attr('action');
    const method = $('input[name="_method"]').val();

    $.ajax({
        url: url,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            const modalEl = document.getElementById('santriFormModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalInstance.hide();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const input = $(`#${key}`);
                    input.addClass('is-invalid');
                    $(`#${key}-error`).text(errors[key][0]);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menyimpan data'
                });
            }
        }
    });
});

// Initialize when modal opens
$('#santriFormModal').on('show.bs.modal', function(event) {
    const button = $(event.relatedTarget);
    const mode = button.data('mode');
    const modal = $(this);
    
    // Reset form
    $('#santriForm')[0].reset();
    $('#santriForm .is-invalid').removeClass('is-invalid');
    $('#santriForm .invalid-feedback').text('');
    // Reset select2 value and kelas state
    $('#wali_id').val(null).trigger('change');
    $('#kelas').prop('disabled', true).html('<option value="">Pilih Kelas</option>');
    
    if (mode === 'edit') {
        const id = button.data('id');
        modal.find('.modal-title').text('Edit Santri');
        $('#santriForm').attr('action', `{{ url('admin/santri') }}/${id}`);
        $('input[name="_method"]').val('PUT');
        
        // Load data
        $.get(`{{ url('admin/santri') }}/${id}/edit`, function(data) {
            Object.keys(data).forEach(key => {
                const input = $(`#${key}`);
                if (input.length) {
                    input.val(data[key]);
                }
            });
            
            // Special handling for select2
            if (data.wali) {
                const option = new Option(data.wali.name, data.wali.id, true, true);
                $('#wali_id').append(option).trigger('change');
            }
            
            // Update kelas options
            $('#jenjang').trigger('change');
            if (data.kelas) {
                $('#kelas').val(data.kelas);
            }
        });
    } else {
        modal.find('.modal-title').text('Tambah Santri');
        $('#santriForm').attr('action', '{{ route("admin.santri.store") }}');
        $('input[name="_method"]').val('POST');
    }

    // Refresh kategori options setiap modal dibuka
    $.get('{{ route("admin.kategori.list") }}', function(data) {
        const options = data.map(item => 
            `<option value="${item.id}">${item.nama}</option>`
        ).join('');
        $('#kategori_id').html('<option value="">Pilih Kategori</option>' + options);
    });

    // Pastikan inisialisasi hanya sekali
    initializeForm();
});
</script>
@endpush
