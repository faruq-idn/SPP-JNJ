<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th>Periode Tagihan</th>
                <th>Santri</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Tanggal Generate</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $belumLunas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="fw-bold"><?php echo e($p->nama_bulan); ?> <?php echo e($p->tahun); ?></div>
                    </td>
                    <td>
                        <div class="fw-bold"><?php echo e($p->santri->nama); ?></div>
                        <small class="text-muted"><?php echo e($p->santri->nisn); ?></small>
                    </td>
                    <td>Rp <?php echo e(number_format($p->nominal, 0, ',', '.')); ?></td>
                    <td>
                        <?php if($p->status == 'pending'): ?>
                            <span class="badge bg-warning">Belum Bayar</span>
                        <?php elseif($p->status == 'failed'): ?>
                            <span class="badge bg-danger">Gagal</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <small><?php echo e($p->created_at->format('d/m/Y H:i')); ?></small>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info" onclick="showDetail(<?php echo e($p->id); ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-3">
                        <i class="fas fa-info-circle me-2"></i>Tidak ada tagihan yang belum lunas
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
            Menampilkan <?php echo e($belumLunas->firstItem() ?? 0); ?>-<?php echo e($belumLunas->lastItem() ?? 0); ?>

            dari <?php echo e($belumLunas->total()); ?> data
        </div>
        <div>
            <?php echo e($belumLunas->links('pagination::simple-bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\pembayaran\partials\tabel-belum-lunas.blade.php ENDPATH**/ ?>