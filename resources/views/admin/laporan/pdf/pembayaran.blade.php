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
        <p>Periode: {{ request('tanggal_awal') ?? 'Semua' }} s/d {{ request('tanggal_akhir') ?? 'Semua' }}</p>
        <p>Status: {{ request('status') ? ucfirst(request('status')) : 'Semua Status' }}</p>
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
            @foreach($pembayaran as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                <td>{{ str_pad($p->santri->nisn, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $p->santri->nama }}</td>
                <td>{{ $p->santri->jenjang }} {{ $p->santri->kelas }}</td>
                <td>{{ Carbon\Carbon::createFromFormat('m', $p->bulan)->translatedFormat('F') }}</td>
                <td>{{ $p->tahun }}</td>
                <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                <td>
                    @if($p->status == 'success')
                        Lunas
                    @elseif($p->status == 'pending')
                        Pending
                    @else
                        Belum Lunas
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p><strong>Total Pembayaran: Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong></p>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }}</p>
    </div>
</body>
</html>
