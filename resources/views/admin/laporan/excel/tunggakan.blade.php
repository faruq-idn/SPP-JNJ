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
        .warning {
            color: #856404;
            background-color: #fff3cd;
            padding: 10px;
            margin: 15px 0;
            text-align: center;
            border: 1px solid #ffeeba;
            font-weight: bold;
            font-size: 12pt;
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
        <h2>Laporan Tunggakan SPP</h2>
        <p>Filter: 
            {{ request('jenjang') ? 'Jenjang ' . request('jenjang') : 'Semua Jenjang' }}
            {{ request('kelas') ? ', Kelas ' . request('kelas') : '' }}
        </p>
    </div>

    <div class="warning">
        Total {{ count($santri) }} santri memiliki tunggakan dengan total Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
    </div>

    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th class="text-nowrap">No</th>
                <th class="text-nowrap">NIS</th>
                <th>Nama Santri</th>
                <th class="text-nowrap">Kelas</th>
                <th>Kategori</th>
                <th>Wali Santri</th>
                <th class="text-nowrap">No HP</th>
                <th class="text-nowrap">Jumlah Bulan</th>
                <th class="text-nowrap">Total Tunggakan</th>
                <th>Bulan Tunggakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($santri as $index => $s)
            <tr>
                <td class="text-center text-nowrap">{{ $index + 1 }}</td>
                <td class="text-center text-nowrap">{{ str_pad($s->nisn, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $s->nama }}</td>
                <td class="text-center text-nowrap">{{ $s->jenjang }} {{ $s->kelas }}</td>
                <td>{{ $s->kategori->nama }}</td>
                <td>{{ $s->wali->name ?? '-' }}</td>
                <td class="text-center text-nowrap">{{ $s->wali->no_hp ?? '-' }}</td>
                <td class="text-center text-nowrap">{{ $s->tunggakan_count }} bulan</td>
                <td class="currency text-nowrap">Rp {{ number_format($s->pembayaran->sum('nominal'), 0, ',', '.') }}</td>
                <td>{{ implode(', ', $s->pembayaran->pluck('bulan')->map(function($bulan) {
                    return Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F');
                })->toArray()) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right" style="font-weight: bold;">Total Tunggakan:</td>
                <td class="currency text-nowrap" style="font-weight: bold;">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        * Data ini merupakan tunggakan SPP per tanggal {{ now()->translatedFormat('d F Y') }}
        <br>
        Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }}
    </div>
</body>
</html>
