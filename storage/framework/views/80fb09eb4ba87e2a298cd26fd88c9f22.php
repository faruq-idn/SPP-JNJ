<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1em;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            vertical-align: middle;
        }
        th {
            background-color: #E7E7E7;
            font-weight: bold;
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            padding: 15px 0 10px;
            text-transform: uppercase;
            color: #333;
        }
        .header p {
            font-size: 12pt;
            margin: 0;
            padding-bottom: 10px;
            color: #666;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .text-nowrap {
            white-space: nowrap !important;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin: 10px 0;
            font-size: 12pt;
        }
        .footer {
            text-align: right;
            font-style: italic;
            margin-top: 20px;
            font-size: 10pt;
        }
        td.currency {
            text-align: right;
        }
        .column-fit {
            width: 1%;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Pembayaran SPP</h2>
        <p>Periode: <?php echo e(Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F')); ?> <?php echo e($tahun); ?></p>
    </div>

    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th class="text-nowrap">No</th>
                <th class="text-nowrap">Tanggal</th>
                <th class="text-nowrap">NISN</th>
                <th>Nama Santri</th>
                <th class="text-nowrap">Kelas</th>
                <th class="text-nowrap">Bulan</th>
                <th class="text-nowrap">Tahun</th>
                <th class="text-nowrap">Nominal</th>
                <th class="text-nowrap">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center text-nowrap"><?php echo e($index + 1); ?></td>
                <td class="text-center text-nowrap"><?php echo e($p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-'); ?></td>
                <td class="text-center text-nowrap"><?php echo e(str_pad($p->santri->nisn, 5, '0', STR_PAD_LEFT)); ?></td>
                <td><?php echo e($p->santri->nama); ?></td>
                <td class="text-center text-nowrap"><?php echo e($p->santri->jenjang); ?> <?php echo e($p->santri->kelas); ?></td>
                <td class="text-center text-nowrap"><?php echo e(Carbon\Carbon::createFromFormat('m', $p->bulan)->translatedFormat('F')); ?></td>
                <td class="text-center text-nowrap"><?php echo e($p->tahun); ?></td>
                <td class="currency text-nowrap">Rp <?php echo e(number_format($p->nominal, 0, ',', '.')); ?></td>
                <td class="text-center text-nowrap">
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
        <tfoot>
            <tr>
                <td colspan="7" class="text-right" style="font-weight: bold;">Total Pembayaran:</td>
                <td class="currency text-nowrap" style="font-weight: bold;">Rp <?php echo e(number_format($totalPembayaran, 0, ',', '.')); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: <?php echo e(now()->translatedFormat('l, d F Y H:i')); ?>

    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\laporan\excel\pembayaran.blade.php ENDPATH**/ ?>