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
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="content">
        <table>
            <tr>
                <td width="200">No. Pembayaran</td>
                <td>: {{ $pembayaran->id }}</td>
            </tr>
            <tr>
                <td>Tanggal Pembayaran</td>
                <td>: {{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Nama Santri</td>
                <td>: {{ $pembayaran->santri->nama }}</td>
            </tr>
            <tr>
                <td>NISN</td>
                <td>: {{ $pembayaran->santri->nisn }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>: {{ $pembayaran->nama_bulan }} {{ $pembayaran->tahun }}</td>
            </tr>
            <tr>
                <td>Nominal</td>
                <td>: Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td>: {{ $pembayaran->metode_pembayaran->nama ?? 'Manual' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>{{ now()->format('d/m/Y H:i') }}</p>
        <p>Petugas: {{ auth()->user()->name }}</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
