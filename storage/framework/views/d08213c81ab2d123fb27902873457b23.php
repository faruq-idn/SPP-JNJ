
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Kategori & Tarif SPP</h6>
    </div>
    <div class="card-body">
        
        <div class="table-responsive mb-4">
            <table class="table">
                <tr>
                    <th width="30%">Kategori</th>
                    <td><?php echo e($santri->kategori->nama); ?></td>
                </tr>
                <tr>
                    <th>Tarif SPP</th>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <?php if($santri->kategori->tarifTerbaru): ?>
                                <span>Rp <?php echo e(number_format($santri->kategori->tarifTerbaru->nominal, 0, ',', '.')); ?></span>
                                <span class="badge bg-info">per bulan</span>
                            <?php else: ?>
                                <span class="text-muted">Belum diatur</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        
        <?php $__currentLoopData = $pembayaranPerTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun => $pembayaranBulanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $totalBulan = count($pembayaranBulanan);
                $lunasBulan = $pembayaranBulanan->where('status', 'success')->count();
                $isLunas = $lunasBulan === $totalBulan;
                $presentase = ($lunasBulan / $totalBulan) * 100;
                $statusClass = $isLunas ? 'success' : ($presentase > 50 ? 'warning' : 'danger');
            ?>
            <div class="p-3 rounded-3 bg-<?php echo e($statusClass); ?> bg-opacity-10 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="mb-1">Status SPP <?php echo e($tahun); ?></h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-<?php echo e($statusClass); ?>">
                                <?php echo e($lunasBulan); ?>/<?php echo e($totalBulan); ?> Bulan
                            </span>
                            <?php if($isLunas): ?>
                                <span class="badge bg-success">Lunas</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Belum Lunas</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted mb-1">Tunggakan</div>
                        <div class="fw-bold text-danger">
                            Rp <?php echo e(number_format($totalTunggakanPerTahun[$tahun] ?? 0, 0, ',', '.')); ?>

                        </div>
                    </div>
                </div>
                <div class="progress" style="height: 8px">
                    <div class="progress-bar bg-<?php echo e($statusClass); ?>" 
                         role="progressbar" 
                         style="width: <?php echo e($presentase); ?>%" 
                         aria-valuenow="<?php echo e($presentase); ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <div class="d-flex justify-content-between align-items-center p-3 bg-danger bg-opacity-10 rounded-3">
            <h6 class="mb-0">Total Tunggakan Keseluruhan:</h6>
            <div class="text-danger fw-bold fs-5">
                Rp <?php echo e(number_format($totalTunggakan, 0, ',', '.')); ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\shared\santri\_kategori_tarif.blade.php ENDPATH**/ ?>