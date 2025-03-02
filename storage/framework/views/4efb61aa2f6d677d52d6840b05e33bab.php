<?php $__env->startSection('title', 'Laporan Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Pembayaran</h1>
        <div class="btn-group" role="group">
            <a href="<?php echo e(route('admin.laporan.pembayaran', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => request('kategori'), 'export' => 'excel'])); ?>" 
               class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="<?php echo e(route('admin.laporan.pembayaran', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => request('kategori'), 'export' => 'pdf'])); ?>" 
               class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <!-- Info Filter Card -->
        <div>
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-0">
                            Filter Aktif:
                            <span class="text-primary">
                                Bulan: <?php echo e(date('F', mktime(0, 0, 0, $bulan, 1))); ?> <?php echo e($tahun); ?>,
                                Kategori: <?php echo e(request('kategori') ? \App\Models\KategoriSantri::find(request('kategori'))->nama : 'Semua'); ?>,
                                Status: <?php echo e(ucfirst(request('status', 'aktif'))); ?>

                               <?php echo e(request('jenjang') ? ', Jenjang ' . request('jenjang') : ''); ?>

                               <?php echo e(request('kelas') ? ', Kelas ' . request('kelas') : ''); ?>

                           </span>
                       </h4>
                   </div>
                </div>
            </div>
        </div>
        <div class="card-header py-3">
            <form method="GET" action="<?php echo e(route('admin.laporan.pembayaran')); ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e(sprintf('%02d', $i)); ?>" 
                                <?php echo e($bulan == sprintf('%02d', $i) ? 'selected' : ''); ?>>
                                <?php echo e(date('F', mktime(0, 0, 0, $i, 1))); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        <?php $currentYear = date('Y'); ?>
                        <?php for($year = $currentYear - 1; $year <= $currentYear + 1; $year++): ?>
                            <option value="<?php echo e($year); ?>" <?php echo e($tahun == $year ? 'selected' : ''); ?>>
                                <?php echo e($year); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php $__currentLoopData = $kategori_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->id); ?>" 
                                <?php echo e(request('kategori') == $k->id ? 'selected' : ''); ?>>
                                <?php echo e($k->nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status Santri</label>
                    <select class="form-select" name="status" id="status">
                        <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                        <option value="lulus" <?php echo e(request('status') == 'lulus' ? 'selected' : ''); ?>>Lulus</option>
                        <option value="keluar" <?php echo e(request('status') == 'keluar' ? 'selected' : ''); ?>>Keluar</option>
                    </select>
                </div>
                <div class="col-md-3 filter-aktif">
                    <label for="jenjang" class="form-label">Jenjang</label>
                    <select class="form-select" name="jenjang" id="jenjang" <?php echo e(request('status') == 'lulus' ? 'disabled' : ''); ?>>
                        <option value="">Semua Jenjang</option>
                        <option value="SMP" <?php echo e(request('jenjang') == 'SMP' ? 'selected' : ''); ?>>SMP</option>
                        <option value="SMA" <?php echo e(request('jenjang') == 'SMA' ? 'selected' : ''); ?>>SMA</option>
                    </select>
                </div>
                <div class="col-md-3 filter-aktif">
                    <label for="kelas" class="form-label">Kelas</label>
                    <select class="form-select" name="kelas" id="kelas" <?php echo e(request('status') == 'lulus' ? 'disabled' : ''); ?>>
                        <option value="">Semua Kelas</option>
                        <?php if(request('jenjang') == 'SMP'): ?>
                            <option value="7A" <?php echo e(request('kelas') == '7A' ? 'selected' : ''); ?>>7A</option>
                            <option value="7B" <?php echo e(request('kelas') == '7B' ? 'selected' : ''); ?>>7B</option>
                            <option value="8A" <?php echo e(request('kelas') == '8A' ? 'selected' : ''); ?>>8A</option>
                            <option value="8B" <?php echo e(request('kelas') == '8B' ? 'selected' : ''); ?>>8B</option>
                            <option value="9A" <?php echo e(request('kelas') == '9A' ? 'selected' : ''); ?>>9A</option>
                            <option value="9B" <?php echo e(request('kelas') == '9B' ? 'selected' : ''); ?>>9B</option>
                        <?php elseif(request('jenjang') == 'SMA'): ?>
                            <option value="10A" <?php echo e(request('kelas') == '10A' ? 'selected' : ''); ?>>10A</option>
                            <option value="10B" <?php echo e(request('kelas') == '10B' ? 'selected' : ''); ?>>10B</option>
                            <option value="11A" <?php echo e(request('kelas') == '11A' ? 'selected' : ''); ?>>11A</option>
                            <option value="11B" <?php echo e(request('kelas') == '11B' ? 'selected' : ''); ?>>11B</option>
                            <option value="12A" <?php echo e(request('kelas') == '12A' ? 'selected' : ''); ?>>12A</option>
                            <option value="12B" <?php echo e(request('kelas') == '12B' ? 'selected' : ''); ?>>12B</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="<?php echo e(route('admin.laporan.pembayaran')); ?>" class="btn btn-secondary mt-2 d-block w-100">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>NISN</th>
                            <th>Nama Santri</th>
                            <th>Kategori</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($p->tanggal_bayar->format('d/m/Y')); ?></td>
                            <td><?php echo e($p->santri->nisn); ?></td>
                            <td><?php echo e($p->santri->nama); ?></td>
                            <td><?php echo e($p->santri->kategori->nama); ?></td>
                            <td>Rp <?php echo e(number_format($p->nominal, 0, ',', '.')); ?></td>
                            <td><?php echo e($p->metode_pembayaran->nama ?? 'Manual'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total:</th>
                            <th colspan="2">Rp <?php echo e(number_format($totalPembayaran, 0, ',', '.')); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('vendor/datatables/css/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('vendor/datatables/css/buttons.bootstrap5.min.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('vendor/datatables/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/datatables/js/dataTables.bootstrap5.min.js')); ?>"></script>
<script>
$(document).ready(function() {
    // Handle status change
    $('#status').on('change', function() {
        const status = $(this).val();
        const jenjangSelect = $('#jenjang');
        const kelasSelect = $('#kelas');
        const filterAktif = $('.filter-aktif');
        
        if (status === 'lulus' || status === 'keluar') {
            jenjangSelect.prop('disabled', true).val('');
            kelasSelect.prop('disabled', true).val('');
            filterAktif.addClass('opacity-50');
        } else {
            jenjangSelect.prop('disabled', false);
            kelasSelect.prop('disabled', false);
            filterAktif.removeClass('opacity-50');
        }
    });

    // Handle jenjang change
    $('#jenjang').on('change', function() {
        const jenjang = $(this).val();
        const kelasSelect = $('#kelas');
        kelasSelect.empty().append('<option value="">Semua Kelas</option>');
        
        if (jenjang === 'SMP') {
            const kelasSMP = ['7A', '7B', '8A', '8B', '9A', '9B'];
            kelasSMP.forEach(kelas => {
                kelasSelect.append(`<option value="${kelas}">${kelas}</option>`);
            });
        } else if (jenjang === 'SMA') {
            const kelasSMA = ['10A', '10B', '11A', '11B', '12A', '12B'];
            kelasSMA.forEach(kelas => {
                kelasSelect.append(`<option value="${kelas}">${kelas}</option>`);
            });
        }
    });
});

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\laporan\pembayaran.blade.php ENDPATH**/ ?>