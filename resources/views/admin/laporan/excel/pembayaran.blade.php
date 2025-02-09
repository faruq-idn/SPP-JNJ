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
        <p>Periode: {{ Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F') }} {{ $tahun }}</p>
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
            @foreach($pembayaran as $index => $p)
            <tr>
                <td class="text-center text-nowrap">{{ $index + 1 }}</td>
                <td class="text-center text-nowrap">{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                <td class="text-center text-nowrap">{{ str_pad($p->santri->nisn, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $p->santri->nama }}</td>
                <td class="text-center text-nowrap">{{ $p->santri->jenjang }} {{ $p->santri->kelas }}</td>
                <td class="text-center text-nowrap">{{ Carbon\Carbon::createFromFormat('m', $p->bulan)->translatedFormat('F') }}</td>
                <td class="text-center text-nowrap">{{ $p->tahun }}</td>
                <td class="currency text-nowrap">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                <td class="text-center text-nowrap">
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
        <tfoot>
            <tr>
                <td colspan="7" class="text-right" style="font-weight: bold;">Total Pembayaran:</td>
                <td class="currency text-nowrap" style="font-weight: bold;">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }}
    </div>
</body>
</html>
