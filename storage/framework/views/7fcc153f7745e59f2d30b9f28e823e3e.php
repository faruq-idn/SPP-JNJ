<?php
    use Carbon\Carbon;
?>

<!-- Tab tahun -->
<div class="card-header bg-white py-3 border-bottom">
    <ul class="nav nav-tabs card-header-tabs mb-0">
        <?php $__currentLoopData = $pembayaranPerTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun => $pembayaranBulanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $lunasBulan = $pembayaranBulanan->where('status', 'success')->count();
                $totalBulan = count($pembayaranBulanan);
                $statusClass = $lunasBulan === $totalBulan ? 'success' : ($lunasBulan > 0 ? 'warning' : 'danger');
            ?>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo e($loop->first ? 'active' : ''); ?>"
                   data-bs-toggle="tab"
                   href="#tahun-<?php echo e($tahun); ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span><?php echo e($tahun); ?></span>
                    <span class="badge bg-<?php echo e($statusClass); ?> rounded-pill">
                        <?php echo e($lunasBulan); ?>/<?php echo e($totalBulan); ?>

                    </span>
                </a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>

<div class="tab-content">
    <?php $__currentLoopData = $pembayaranPerTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun => $pembayaranBulanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>"
         id="tahun-<?php echo e($tahun); ?>">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light">
                        <th class="fw-medium">Bulan</th>
                        <th class="fw-medium">Tanggal Bayar</th>
                        <th class="fw-medium">Nominal</th>
                        <th class="fw-medium">Metode</th>
                        <th class="fw-medium">Status</th>
                        <th class="text-center fw-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pembayaranBulanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isLunas = $p->status === 'success';
                        $buttonSize = 'btn-sm';
                        $month = (int)$p->bulan;
                        $monthName = $p->nama_bulan;
                    ?>
                    <tr>
                        <td><?php echo e($monthName); ?></td>
                        <td><?php echo e(isset($p->tanggal_bayar) ? $p->tanggal_bayar->format('d/m/Y') : '-'); ?></td>
                        <td>Rp <?php echo e(number_format($p->nominal, 0, ',', '.')); ?></td>
                        <td>
                            <?php if($isLunas): ?>
                                <span class="badge bg-info"><?php echo e(optional($p->metode_pembayaran)->nama ?? '-'); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                $statusClass = $p->status === 'success' ? 'success' : ($p->status === 'pending' ? 'warning' : 'danger');
                                $statusText = $p->status === 'success' ? 'Lunas' : ($p->status === 'pending' ? 'Pending' : 'Belum Lunas');
                            ?>
                            <span class="badge bg-<?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <!-- Detail Button -->
                                <button type="button"
                                        class="btn <?php echo e($buttonSize); ?> btn-info d-flex align-items-center px-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPembayaran"
                                        onclick="showDetail(
                                            '<?php echo e(isset($p->exists) && $p->exists ? $p->id : ''); ?>',
                                            '<?php echo e($monthName); ?>',
                                            <?php echo e($p->nominal); ?>,
                                            '<?php echo e($tahun); ?>',
                                            '<?php echo e($p->status); ?>',
                                            '<?php echo e(isset($p->tanggal_bayar) ? $p->tanggal_bayar->translatedFormat("d F Y H:i") : "-"); ?>',
                                            '<?php echo e(optional($p->metode_pembayaran)->nama ?? "-"); ?>',
                                            <?php echo e(json_encode($santri->toArray())); ?>

                                        )"
                                        title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Verify/Create Button -->
                                <?php if(!$isLunas): ?>
                                    <button type="button"
                                            class="btn <?php echo e($buttonSize); ?> btn-success d-flex align-items-center px-2"
                                            <?php if(isset($p->exists) && $p->exists): ?>
                                                onclick="verifikasiPembayaran('<?php echo e($p->id); ?>', '<?php echo e($monthName); ?>', <?php echo e($p->nominal); ?>)"
                                                title="Verifikasi Pembayaran"
                                            <?php else: ?>
                                                onclick="tambahPembayaran('<?php echo e($tahun); ?>', '<?php echo e($month); ?>', <?php echo e($p->nominal); ?>, <?php echo e($santri->id); ?>)"
                                                title="Tambah Pembayaran"
                                            <?php endif; ?>>
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\shared\santri\_show_table.blade.php ENDPATH**/ ?>