

<?php $__env->startSection('title', 'Laporan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan</h1>
        <div class="btn-group" role="group">
            <a href="<?php echo e(route('petugas.laporan.pembayaran')); ?>" class="btn btn-primary">
                <i class="fas fa-money-bill me-1"></i> Pembayaran
            </a>
            <a href="<?php echo e(route('petugas.laporan.tunggakan')); ?>" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle me-1"></i> Tunggakan
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pembayaran Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?php echo e(number_format($totalPembayaranBulanIni, 0, ',', '.')); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Santri Sudah Bayar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($santriLunas); ?> Santri
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Tunggakan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?php echo e(number_format($totalTunggakan, 0, ',', '.')); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Santri Belum Bayar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($santriNunggak); ?> Santri
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pembayaran Per Bulan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="pembayaranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Per Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const ctxPembayaran = document.getElementById('pembayaranChart');
    new Chart(ctxPembayaran, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartData['labels']); ?>,
            datasets: [{
                label: 'Total Pembayaran',
                data: <?php echo e(json_encode($chartData['data'])); ?>,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });

    const ctxKategori = document.getElementById('kategoriChart');
    new Chart(ctxKategori, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chartKategori['labels']); ?>,
            datasets: [
                {
                    label: 'Lunas',
                    data: <?php echo e(json_encode($chartKategori['data']['lunas'])); ?>,
                    backgroundColor: '#1cc88a'
                },
                {
                    label: 'Belum Lunas',
                    data: <?php echo e(json_encode($chartKategori['data']['belum_lunas'])); ?>,
                    backgroundColor: '#f6c23e'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\petugas\laporan\index.blade.php ENDPATH**/ ?>