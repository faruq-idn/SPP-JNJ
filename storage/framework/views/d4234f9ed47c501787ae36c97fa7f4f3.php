<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th>Tanggal Bayar</th>
                <th>Santri</th>
                <th>Periode</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $lunas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y H:i') : '-'); ?></td>
                    <td>
                        <div class="fw-bold"><?php echo e($p->santri->nama); ?></div>
                        <small class="text-muted"><?php echo e($p->santri->nisn); ?></small>
                    </td>
                    <td><?php echo e($p->nama_bulan); ?> <?php echo e($p->tahun); ?></td>
                    <td>Rp <?php echo e(number_format($p->nominal, 0, ',', '.')); ?></td>
                    <td>
                        <?php if($p->metode_pembayaran): ?>
                            <span class="badge bg-info"><?php echo e($p->metode_pembayaran->nama); ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Manual</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-info" onclick="showDetail(<?php echo e($p->id); ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-3">
                        <i class="fas fa-info-circle me-2"></i>Belum ada pembayaran yang lunas
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-3">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Menampilkan <?php echo e($lunas->firstItem() ?? 0); ?>-<?php echo e($lunas->lastItem() ?? 0); ?>

            dari <?php echo e($lunas->total()); ?> data
        </div>
        <div>
            <?php echo e($lunas->links('pagination::simple-bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\pembayaran\partials\tabel-lunas.blade.php ENDPATH**/ ?>