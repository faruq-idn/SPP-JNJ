<?php

namespace App\Http\Controllers\Admin\Exports;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfExportController extends Controller
{
    public function exportPembayaran($pembayaran, $bulan, $tahun)
    {
        $totalPembayaran = $pembayaran->sum('nominal');

        $pdf = PDF::loadView('admin.laporan.pdf.pembayaran',
            compact('pembayaran', 'totalPembayaran', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pembayaran.pdf');
    }

    public function exportTunggakan($santri, $totalTunggakan)
    {
        $pdf = PDF::loadView('admin.laporan.pdf.tunggakan',
            compact('santri', 'totalTunggakan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-tunggakan.pdf');
    }

    public function exportPembayaranSantriTahunan($santri, $tahun, $pembayaran, $totalPembayaran)
    {
        $pdf = PDF::loadView('admin.laporan.pdf.pembayaran_santri_tahunan',
            compact('santri', 'tahun', 'pembayaran', 'totalPembayaran'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('riwayat-pembayaran-santri-'.$santri->id.'-'.$tahun.'.pdf');
    }

    public function exportPembayaranDetail($pembayaran)
    {
        $pdf = PDF::loadView('admin.laporan.pdf.pembayaran_detail', compact('pembayaran'))
            ->setPaper('a5', 'portrait');
        return $pdf->download('detail-pembayaran-'.$pembayaran->id.'.pdf');
    }
}
