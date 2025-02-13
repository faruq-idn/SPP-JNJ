<?php

namespace Database\Seeders;

use App\Models\PembayaranSpp;
use App\Models\Santri;
use App\Models\User;
use App\Models\MetodePembayaran;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PembayaranSppSeeder extends Seeder
{
    public function run()
    {
        $petugas = User::where('role', 'petugas')->first();
        $santris = Santri::with(['kategori.tarifTerbaru'])->get();
        $now = Carbon::now();

        // Get metode pembayaran
        $metodeTunai = MetodePembayaran::where('kode', 'MANUAL_TUNAI')->first()->id;
        $metodeTransfer = MetodePembayaran::where('kode', 'MANUAL_TRANSFER')->first()->id;
        $metodeMidtrans = MetodePembayaran::where('kode', 'MIDTRANS')->first()->id;
        
        foreach ($santris as $santri) {
            $tanggalMasuk = Carbon::parse($santri->tanggal_masuk);
            $nominal = $santri->kategori->tarifTerbaru->nominal ?? 500000;

            // Jika santri sudah lulus, generate pembayaran sampai bulan kelulusan
            if ($santri->status === 'lulus') {
                $bulanAkhir = Carbon::create($santri->tahun_tamat, 6, 1); // Juni tahun kelulusan
            } else {
                $bulanAkhir = $now;
            }

            // Generate data pembayaran dari bulan masuk sampai sekarang/lulus
            $currentDate = $tanggalMasuk->copy()->startOfMonth();
            while ($currentDate <= $bulanAkhir) {
                // Skip jika sudah ada pembayaran
                $existingPayment = PembayaranSpp::where('santri_id', $santri->id)
                    ->where('bulan', $currentDate->format('m'))
                    ->where('tahun', $currentDate->format('Y'))
                    ->exists();

                if (!$existingPayment) {
                    // Tentukan status pembayaran
                    // Semakin lama jarak dengan bulan sekarang, semakin besar kemungkinan sudah lunas
                    $monthsAgo = $currentDate->diffInMonths($now);
                    $chanceOfPayment = $monthsAgo > 6 ? 90 : (90 - ($monthsAgo * 10));
                    
                    if ($santri->status === 'lulus') {
                        $chanceOfPayment = 100; // Santri lulus pasti sudah lunas
                    }

                    $isPaid = rand(1, 100) <= $chanceOfPayment;
                    $status = $isPaid ? PembayaranSpp::STATUS_SUCCESS : PembayaranSpp::STATUS_FAILED;

                    // Generate pembayaran
                    PembayaranSpp::create([
                        'santri_id' => $santri->id,
                        'tanggal_bayar' => $status === PembayaranSpp::STATUS_SUCCESS 
                            ? $currentDate->copy()->addDays(rand(1, 20))->format('Y-m-d')
                            : null,
                        'bulan' => $currentDate->format('m'),
                        'tahun' => $currentDate->format('Y'),
                        'nominal' => $nominal,
                        'metode_pembayaran_id' => $status === PembayaranSpp::STATUS_SUCCESS 
                            ? (rand(1, 100) <= 70 ? $metodeTunai : $metodeTransfer)
                            : null,
                        'status' => $status,
                        'keterangan' => $status === PembayaranSpp::STATUS_SUCCESS 
                            ? 'Pembayaran SPP ' . $currentDate->translatedFormat('F Y')
                            : 'Belum dibayar'
                    ]);
                }

                $currentDate->addMonth();
            }
        }

        // Generate beberapa pembayaran online pending
        $activeSantris = Santri::where('status', 'aktif')->get()->random(5);
        foreach ($activeSantris as $santri) {
            $pembayaran = PembayaranSpp::where('santri_id', $santri->id)
                ->where('status', PembayaranSpp::STATUS_FAILED)
                ->first();

            if ($pembayaran) {
                $orderId = 'ORDER-' . strtoupper(uniqid());
                $pembayaran->update([
                    'metode_pembayaran_id' => $metodeMidtrans,
                    'status' => PembayaranSpp::STATUS_PENDING,
                    'order_id' => $orderId,
                    'snap_token' => 'fake-snap-token-' . $orderId,
                    'payment_type' => 'bank_transfer',
                    'keterangan' => 'Menunggu pembayaran via bank transfer'
                ]);
            }
        }
    }
}
