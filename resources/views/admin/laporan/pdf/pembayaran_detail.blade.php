<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Detail Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 12px; }
        .header h3 { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px; vertical-align: top; }
        .label { width: 40%; color: #555; }
        .total { text-align: right; margin-top: 10px; }
        .muted { color: #777; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 11px; }
        .bg-success { background-color: #28a745; color: #fff; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-danger { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <div class="header">
        <h3>Bukti Pembayaran SPP</h3>
        <div class="muted">Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }}</div>
    </div>

    <table>
        <tr>
            <td class="label">Nama Santri</td>
            <td>: {{ $pembayaran->santri->nama }}</td>
        </tr>
        <tr>
            <td class="label">NISN</td>
            <td>: {{ $pembayaran->santri->nisn }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td>: {{ $pembayaran->santri->jenjang }} {{ $pembayaran->santri->kelas }}</td>
        </tr>
        <tr>
            <td class="label">Periode</td>
            <td>: {{ \Carbon\Carbon::create(null, $pembayaran->bulan, 1)->translatedFormat('F') }} {{ $pembayaran->tahun }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Bayar</td>
            <td>: {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Metode Pembayaran</td>
            <td>: {{ optional($pembayaran->metode_pembayaran)->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nominal</td>
            <td>: Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>:
                @if($pembayaran->status === 'success')
                    <span class="badge bg-success">Lunas</span>
                @elseif($pembayaran->status === 'pending')
                    <span class="badge bg-warning">Pending</span>
                @else
                    <span class="badge bg-danger">Belum Lunas</span>
                @endif
            </td>
        </tr>
        @if($pembayaran->keterangan)
        <tr>
            <td class="label">Keterangan</td>
            <td>: {{ $pembayaran->keterangan }}</td>
        </tr>
        @endif
    </table>

    <div class="total">
        <strong>Total Dibayar: Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</strong>
    </div>
</body>
</html>


