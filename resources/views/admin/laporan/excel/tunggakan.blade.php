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
            table-layout: auto;
            margin-bottom: 1em;
        }
        th, td {
            border: 1px solid black;
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
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Tunggakan SPP</h2>
        <p>Filter: Status {{ ucfirst(request('status', 'aktif')) }}
            @if(request('status') == 'aktif')
                {{ request('jenjang') ? ', Jenjang ' . request('jenjang') : '' }}
                {{ request('kelas') ? ', Kelas ' . request('kelas') : '' }}
            @endif
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
                <th>Status</th>
                <th>Wali Santri</th>
                <th class="text-nowrap">No HP</th>
                <th class="text-nowrap">Jumlah Bulan</th>
                <th>Bulan Tunggakan</th>
                <th class="text-nowrap">Total Tunggakan</th>
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
                <td class="text-center" style="color: {{ $s->status == 'aktif' ? '#28a745' : ($s->status == 'lulus' ? '#17a2b8' : '#dc3545') }}">
                    {{ ucfirst($s->status) }}
                </td>
                <td>{{ $s->wali->name ?? '-' }}</td>
                <td class="text-center text-nowrap">{{ $s->wali->no_hp ?? '-' }}</td>
                <td class="text-center text-nowrap">{{ $s->jumlah_bulan_tunggakan }} bulan</td>
                <td>
                    @php
                        $bulanBelumLunas = collect();
                        $bulanMasuk = Carbon\Carbon::parse($s->tanggal_masuk)->startOfMonth();
                        $bulanSekarang = Carbon\Carbon::now()->startOfMonth();
                        $pembayaranLunas = $s->pembayaran
                            ->where('status', 'success')
                            ->map(fn($p) => $p->bulan . '-' . $p->tahun)
                            ->toArray();
                        
                        while ($bulanMasuk <= $bulanSekarang) {
                            $key = $bulanMasuk->format('m-Y');
                            if (!in_array($key, $pembayaranLunas)) {
                                $bulanBelumLunas->push($bulanMasuk->translatedFormat('F Y'));
                            }
                            $bulanMasuk->addMonth();
                        }
                    @endphp
                    {{ $bulanBelumLunas->implode(', ') }}
                </td>
                <td class="currency text-center text-nowrap">
                    Rp {{ number_format($s->total_tunggakan, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="text-right" style="font-weight: bold; border: 1px solid black;">Total Tunggakan:</td>
                <td class="currency text-nowrap" style="font-weight: bold; border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</td>
                <td style="border: 1px solid black;"></td>
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
