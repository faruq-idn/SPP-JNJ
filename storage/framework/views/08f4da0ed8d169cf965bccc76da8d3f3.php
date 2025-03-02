<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pembayaran SPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>BUKTI PEMBAYARAN SPP</h2>
        <p><?php echo e(config('app.name')); ?></p>
    </div>

    <div class="content">
        <table>
            <tr>
                <td width="200">No. Pembayaran</td>
                <td>: <?php echo e($pembayaran->id); ?></td>
            </tr>
            <tr>
                <td>Tanggal Pembayaran</td>
                <td>: <?php echo e($pembayaran->tanggal_bayar->format('d/m/Y H:i')); ?></td>
            </tr>
            <tr>
                <td>Nama Santri</td>
                <td>: <?php echo e($pembayaran->santri->nama); ?></td>
            </tr>
            <tr>
                <td>NISN</td>
                <td>: <?php echo e($pembayaran->santri->nisn); ?></td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>: <?php echo e($pembayaran->nama_bulan); ?> <?php echo e($pembayaran->tahun); ?></td>
            </tr>
            <tr>
                <td>Nominal</td>
                <td>: Rp <?php echo e(number_format($pembayaran->nominal, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td>: <?php echo e($pembayaran->metode_pembayaran->nama ?? 'Manual'); ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p><?php echo e(now()->format('d/m/Y H:i')); ?></p>
        <p>Petugas: <?php echo e(auth()->user()->name); ?></p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\pembayaran\print.blade.php ENDPATH**/ ?>