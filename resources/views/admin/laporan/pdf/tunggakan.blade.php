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
        <p>Filter: 
            {{ request('jenjang') ? 'Jenjang ' . request('jenjang') : 'Semua Jenjang' }}
            {{ request('kelas') ? ', Kelas ' . request('kelas') : '' }}
        </p>
    </div>

    <div class="warning">
        Total {{ count($santri) }} santri memiliki tunggakan dengan total Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Santri</th>
                <th>Kelas</th>
                <th>Kategori</th>
                <th>Wali Santri</th>
                <th>No HP</th>
                <th>Jumlah Bulan</th>
                <th>Total Tunggakan</th>
                <th>Bulan Tunggakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($santri as $index => $s)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ str_pad($s->nisn, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->jenjang }} {{ $s->kelas }}</td>
                <td>{{ $s->kategori->nama }}</td>
                <td>{{ $s->wali->name ?? '-' }}</td>
                <td>{{ $s->wali->no_hp ?? '-' }}</td>
                <td>{{ $s->tunggakan_count }} bulan</td>
                <td>Rp {{ number_format($s->pembayaran->sum('nominal'), 0, ',', '.') }}</td>
                <td>
                    {{ implode(', ', $s->pembayaran->pluck('bulan')->map(function($bulan) {
                        return Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F');
                    })->toArray()) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p><strong>Total Tunggakan: Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</strong></p>
    </div>

    <div class="footer">
        <p>* Data ini merupakan tunggakan SPP per tanggal {{ now()->translatedFormat('d F Y') }}</p>
        <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }}</p>
    </div>
</body>
</html>
