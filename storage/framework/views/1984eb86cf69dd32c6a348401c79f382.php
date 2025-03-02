<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pembayaran SPP</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Pembayaran SPP</h2>
        <p>Periode: <?php echo e(Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F')); ?> <?php echo e($tahun); ?></p>
        <?php if(request('status')): ?>
            <p>Status: <?php echo e(ucfirst(request('status'))); ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIS</th>
                <th>Nama Santri</th>
                <th>Kelas</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-'); ?></td>
                <td><?php echo e(str_pad($p->santri->nisn, 5, '0', STR_PAD_LEFT)); ?></td>
                <td><?php echo e($p->santri->nama); ?></td>
                <td><?php echo e($p->santri->jenjang); ?> <?php echo e($p->santri->kelas); ?></td>
                <td><?php echo e(Carbon\Carbon::createFromFormat('m', $p->bulan)->translatedFormat('F')); ?></td>
                <td><?php echo e($p->tahun); ?></td>
                <td>Rp <?php echo e(number_format($p->nominal, 0, ',', '.')); ?></td>
                <td>
                    <?php if($p->status == 'success'): ?>
                        Lunas
                    <?php elseif($p->status == 'pending'): ?>
                        Pending
                    <?php else: ?>
                        Belum Lunas
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="total">
        <p><strong>Total Pembayaran: Rp <?php echo e(number_format($totalPembayaran, 0, ',', '.')); ?></strong></p>
    </div>

    <div class="footer">
        <p>Dicetak pada: <?php echo e(now()->translatedFormat('l, d F Y H:i')); ?></p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\laporan\pdf\pembayaran.blade.php ENDPATH**/ ?>