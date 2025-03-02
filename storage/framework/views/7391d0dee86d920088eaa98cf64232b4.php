

<?php $__env->startSection('title', 'Laporan Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Pembayaran</h1>
        <div class="btn-group" role="group">
            <a href="<?php echo e(route('petugas.laporan.pembayaran', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => request('kategori'), 'export' => 'excel'])); ?>" 
               class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="<?php echo e(route('petugas.laporan.pembayaran', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => request('kategori'), 'export' => 'pdf'])); ?>" 
               class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form method="GET" class="row g-3 align-items-center">
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
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        language: {
            url: "<?php echo e(asset('vendor/datatables/i18n/id.json')); ?>"
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
        order: [[1, 'desc']], // Sort by tanggal desc
        columnDefs: [{
            targets: [5], // Nominal column
            render: function(data, type, row) {
                if (type === 'display') {
                    return data;
                }
                return data.replace(/[^\d]/g, '');
            }
        }]
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\petugas\laporan\pembayaran.blade.php ENDPATH**/ ?>