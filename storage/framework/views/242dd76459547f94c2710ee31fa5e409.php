<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<style>
.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}
</style>
<?php echo $__env->make('layouts.partials.dropdown-santri', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container-fluid p-2 p-md-4 mb-5 pb-5">
    <div class="row g-2 g-md-3">
        <?php if($santri): ?>
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body p-3 p-md-4">
                    <h5 class="card-title fw-bold text-primary mb-3">Informasi Santri</h5>
                    <div class="vstack gap-3">
                        
                        <!-- Informasi Santri -->
                        <div class="card shadow-sm rounded-3 border-0">
                            <div class="card-body p-2 p-md-3">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                    <div class="d-flex flex-column gap-1">
                                        <h5 class="fw-bold mb-0 fs-4"><?php echo e($santri->nama); ?></h5>
                                        <div class="text-muted fs-6">NIS: <?php echo e(str_pad($santri->nisn, 5, '0', STR_PAD_LEFT)); ?></div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <span class="badge bg-<?php echo e($santri->status_color); ?> px-3 py-2 fs-6"><?php echo e(ucfirst($santri->status)); ?></span>
                                    </div>
                                </div>

                                <div class="row g-2 g-md-3">
                                    <div class="col-12 col-md-6">
                                        <div class="vstack gap-3">
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-info bg-opacity-10 text-info">
                                                        <i class="fas fa-graduation-cap"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="text-muted small">Jenjang & Kelas</div>
                                                        <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                                                            <span class="badge bg-info fs-7 fs-md-6"><?php echo e($santri->jenjang); ?></span>
                                                            <span class="badge bg-primary fs-7 fs-md-6">Kelas <?php echo e($santri->kelas); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-success bg-opacity-10 text-success">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Tanggal Masuk</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1"><?php echo e($santri->tanggal_masuk->format('d F Y')); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-info bg-opacity-10 text-info">
                                                        <i class="fas fa-graduation-cap"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Tahun Tamat</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1"><?php echo e($santri->tahun_tamat ?: '-'); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Jenis Kelamin</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1"><?php echo e($santri->jenis_kelamin); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-warning bg-opacity-10 text-warning">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Alamat</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1"><?php echo e($santri->alamat ?: '-'); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="vstack gap-3">
                                            <div class="info-item p-2 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="icon-circle bg-success bg-opacity-10 text-success">
                                                        <i class="fas fa-layer-group"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Kategori Santri</div>
                                                        <div class="fw-semibold fs-7 fs-md-6 mt-1"><?php echo e($santri->kategori->nama); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-item p-3 bg-light rounded-3">
                                                <div class="d-flex align-items-start gap-2 mb-3">
                                                    <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                                                        <i class="fas fa-money-bill"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Tarif SPP Bulanan</div>
                                                        <div class="fw-bold text-primary fs-6 mt-1">
                                                            <?php if($santri->kategori->tarifTerbaru): ?>
                                                                Rp <?php echo e(number_format($santri->kategori->tarifTerbaru->nominal, 0, ',', '.')); ?>

                                                            <?php else: ?>
                                                                <span class="text-muted">Belum diatur</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if($santri->kategori->tarifTerbaru): ?>
                                                <div class="rounded-3 bg-white p-2">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <div class="p-2 rounded bg-success bg-opacity-10">
                                                                <div class="text-muted small">Makan</div>
                                                                <div class="fw-semibold">Rp <?php echo e(number_format($santri->kategori->tarifTerbaru->biaya_makan, 0, ',', '.')); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="p-2 rounded bg-info bg-opacity-10">
                                                                <div class="text-muted small">Asrama</div>
                                                                <div class="fw-semibold">Rp <?php echo e(number_format($santri->kategori->tarifTerbaru->biaya_asrama, 0, ',', '.')); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="p-2 rounded bg-warning bg-opacity-10">
                                                                <div class="text-muted small">Listrik</div>
                                                                <div class="fw-semibold">Rp <?php echo e(number_format($santri->kategori->tarifTerbaru->biaya_listrik, 0, ',', '.')); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="p-2 rounded bg-danger bg-opacity-10">
                                                                <div class="text-muted small">Kesehatan</div>
                                                                <div class="fw-semibold">Rp <?php echo e(number_format($santri->kategori->tarifTerbaru->biaya_kesehatan, 0, ',', '.')); ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Status SPP</h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    
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
                        <h6 class="mb-0">Total Tunggakan Keseluruhan</h6>
                        <div class="text-danger fw-bold fs-5">
                            Rp <?php echo e(number_format($total_tunggakan ?? 0, 0, ',', '.')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Daftar Tunggakan</h5>
                            <?php if($pembayaran_terbaru->isNotEmpty()): ?>
                            <small class="text-muted">Segera lunasi pembayaran untuk menghindari denda</small>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('wali.tagihan')); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-list me-1"></i>Lihat Semua Tagihan
                        </a>
                    </div>
                </div>
                <div class="card-body p-3 p-md-4">
                    <?php if($pembayaran_terbaru->isNotEmpty()): ?>
                    <div class="vstack gap-3">
                        <?php $__currentLoopData = $pembayaran_terbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembayaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="tunggakan-item bg-light rounded-3 p-2 p-md-3 border">
                            <div class="row g-2 g-md-3 align-items-center">
                                <div class="col-7 col-sm-4">
                                    <div class="vstack">
                                        <span class="fw-bold text-primary fs-6 fs-md-5"><?php echo e($pembayaran->nama_bulan); ?></span>
                                        <span class="text-muted fs-7"><?php echo e($pembayaran->tahun); ?></span>
                                    </div>
                                </div>
                                
                                <div class="col-5 col-sm-5 text-end text-sm-start">
                                    <div class="vstack">
                                        <span class="fw-bold fs-7 fs-md-6">Rp <?php echo e(number_format($pembayaran->nominal, 0, ',', '.')); ?></span>
                                        <span class="text-danger small d-none d-sm-inline">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Jatuh tempo: 10 <?php echo e($pembayaran->nama_bulan); ?> <?php echo e($pembayaran->tahun); ?>

                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-sm-3">
                                    <button class="btn btn-primary btn-sm btn-md-lg w-100" onclick="showDetailPembayaran(<?php echo e($pembayaran->id); ?>, '<?php echo e($pembayaran->nama_bulan); ?>', <?php echo e($pembayaran->nominal); ?>, 'unpaid', '-', '-', '<?php echo e($pembayaran->tahun); ?>')">
                                        <i class="fas fa-money-bill me-1"></i>Bayar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success fa-3x"></i>
                        </div>
                        <h5 class="text-success mb-2">Tidak Ada Tunggakan</h5>
                        <p class="text-muted small mb-0">Terima kasih atas ketepatan waktu Anda dalam pembayaran SPP.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php echo $__env->make('layouts.partials.modal-detail-pembayaran', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.wali', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\wali\dashboard.blade.php ENDPATH**/ ?>