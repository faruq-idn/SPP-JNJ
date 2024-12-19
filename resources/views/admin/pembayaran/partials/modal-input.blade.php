<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Pembayaran SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pembayaran.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Santri</label>
                        <select class="form-select select2" name="santri_id" id="santri_id" required>
                            <option value="">Pilih Santri</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Bayar</label>
                        <input type="date" class="form-control" name="tanggal_bayar"
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Bulan</label>
                                <select class="form-select" name="bulan" id="bulan" required>
                                    <option value="">Pilih Bulan</option>
                                    @foreach(range(1, 12) as $bulan)
                                        @php
                                            $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                                            $namaBulan = \Carbon\Carbon::create(null, $bulan)->translatedFormat('F');
                                        @endphp
                                        <option value="{{ $bulanPadded }}">{{ $namaBulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <select class="form-select" name="tahun" id="tahun" required>
                                    <option value="">Pilih Tahun</option>
                                    @foreach(range(date('Y')-1, date('Y')+1) as $tahun)
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal SPP</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="nominal_display" readonly>
                            <input type="hidden" name="nominal" id="nominal">
                        </div>
                        <small class="text-muted">Nominal sesuai tarif kategori santri</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="metode_pembayaran_id" required>
                            <option value="">Pilih Metode</option>
                            <option value="1">Manual/Tunai</option>
                            <option value="2">Transfer Bank</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk pilihan santri
    $('.select2').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#modalPembayaran'),
        ajax: {
            url: '{{ route("admin.santri.search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.nama + ' (' + item.nisn + ')',
                            nominal: item.nominal_spp,
                            tunggakan: item.tunggakan // Data tunggakan dari backend
                        };
                    }),
                    pagination: {
                        more: false
                    }
                };
            },
            cache: true
        },
        placeholder: 'Cari nama atau NISN santri...',
        minimumInputLength: 3,
    }).on('select2:select', function(e) {
        var data = e.params.data;
        // Set nominal dan tampilkan dengan format rupiah
        $('#nominal').val(data.nominal);
        $('#nominal_display').val(formatRupiah(data.nominal));

        // Set bulan dan tahun tunggakan pertama
        if (data.tunggakan && data.tunggakan.length > 0) {
            var tunggakan = data.tunggakan[0];
            $('#bulan').val(tunggakan.bulan);
            $('#tahun').val(tunggakan.tahun);
        }
    });

    // Disable bulan dan tahun yang sudah dibayar
    $('#bulan, #tahun').on('change', function() {
        var santriId = $('.select2').val();
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();

        if (santriId && bulan && tahun) {
            $.get('{{ route("admin.pembayaran.check-status") }}', {
                santri_id: santriId,
                bulan: bulan,
                tahun: tahun
            }).done(function(response) {
                if (response.status === 'lunas') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'SPP untuk periode ini sudah dibayar'
                    });
                    $('#bulan').val('');
                    $('#tahun').val('');
                }
            });
        }
    });
});

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID').format(angka);
}
</script>
@endpush
