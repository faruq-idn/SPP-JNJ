<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Tunggakan SPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .total {
            margin-top: 20px;
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
        .warning {
            color: #856404;
            background-color: #fff3cd;
            padding: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Tunggakan SPP</h2>
        <p>Filter: Status <?php echo e(ucfirst(request('status', 'aktif'))); ?>

            <?php if(request('status') == 'aktif'): ?>
                <?php echo e(request('jenjang') ? ', Jenjang ' . request('jenjang') : ''); ?>

                <?php echo e(request('kelas') ? ', Kelas ' . request('kelas') : ''); ?>

            <?php endif; ?>
        </p>
    </div>

    <div class="warning">
        Total <?php echo e(count($santri)); ?> santri memiliki tunggakan dengan total Rp <?php echo e(number_format($totalTunggakan, 0, ',', '.')); ?>

    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Santri</th>
                <th>Kelas</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Wali Santri</th>
                <th>No HP</th>
                <th>Jumlah Bulan</th>
                <th>Total Tunggakan</th>
                <th>Bulan Tunggakan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $santri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e(str_pad($s->nisn, 5, '0', STR_PAD_LEFT)); ?></td>
                <td><?php echo e($s->nama); ?></td>
                <td><?php echo e($s->jenjang); ?> <?php echo e($s->kelas); ?></td>
                <td><?php echo e($s->kategori->nama); ?></td>
                <td style="color: <?php echo e($s->status == 'aktif' ? '#28a745' : ($s->status == 'lulus' ? '#17a2b8' : '#dc3545')); ?>">
                    <?php echo e(ucfirst($s->status)); ?>

                </td>
                <td><?php echo e($s->wali->name ?? '-'); ?></td>
                <td><?php echo e($s->wali->no_hp ?? '-'); ?></td>
                <td><?php echo e($s->jumlah_bulan_tunggakan); ?> bulan</td>
                <td>Rp <?php echo e(number_format($s->total_tunggakan, 0, ',', '.')); ?></td>
                <td>
                    <?php
                        $bulanTunggakan = $s->pembayaran
                            ->filter(function($p) {
                                return in_array($p->status, ['failed', 'pending', 'unpaid']) && $p->nominal > 0;
                            })
                            ->sortBy(function($p) {
                                return sprintf('%04d%02d', $p->tahun, $p->bulan);
                            })
                            ->map(function($p) {
                                return Carbon\Carbon::create($p->tahun, $p->bulan)
                                    ->translatedFormat('F Y');
                            })->values();
                    ?>
                    <?php echo e($bulanTunggakan->implode(', ')); ?>

                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="total">
        <p><strong>Total Tunggakan: Rp <?php echo e(number_format($totalTunggakan, 0, ',', '.')); ?></strong></p>
    </div>

    <div class="footer">
        <p>* Data ini merupakan tunggakan SPP per tanggal <?php echo e(now()->translatedFormat('d F Y')); ?></p>
        <p>Dicetak pada: <?php echo e(now()->translatedFormat('l, d F Y H:i')); ?></p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\laporan\pdf\tunggakan.blade.php ENDPATH**/ ?>