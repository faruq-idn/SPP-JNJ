<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Riwayat Pembayaran SPP Santri</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 16px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f5f5f5; }
        .meta { margin-bottom: 10px; }
        .meta table { width: auto; border: none; }
        .meta td { border: none; padding: 2px 6px; }
        .footer { margin-top: 12px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Riwayat Pembayaran SPP</h2>
        <p>Tahun: {{ $tahun }}</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td><strong>Nama Santri</strong></td>
                <td>: {{ $santri->nama }}</td>
            </tr>
            <tr>
                <td><strong>NISN</strong></td>
                <td>: {{ $santri->nisn }}</td>
            </tr>
            <tr>
                <td><strong>Kelas</strong></td>
                <td>: {{ $santri->jenjang }} {{ $santri->kelas }}</td>
            </tr>
            <tr>
                <td><strong>Kategori</strong></td>
                <td>: {{ optional($santri->kategori)->nama ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 28px;">No</th>
                <th>Bulan</th>
                <th>Tanggal Bayar</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayaran as $index => $p)
                @php
                    $bulanNama = \Carbon\Carbon::create(null, (int)$p->bulan, 1)->translatedFormat('F');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $bulanNama }}</td>
                    <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                    <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                    <td>{{ optional($p->metode_pembayaran)->nama ?? '-' }}</td>
                    <td>
                        @if($p->status === 'success')
                            Lunas
                        @elseif($p->status === 'pending')
                            Pending
                        @else
                            Belum Lunas
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Total Pembayaran Lunas: Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong></p>
        <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }}</p>
    </div>
</body>
</html>


