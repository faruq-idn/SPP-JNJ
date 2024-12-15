<?php

namespace Database\Seeders;

use App\Models\PembayaranSpp;
use App\Models\Santri;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PembayaranSppSeeder extends Seeder
{
    public function run()
    {
        // Ambil data santri dan petugas
        $santri = Santri::all();
        $petugas = User::where('role', 'petugas')->first();

        // Status pembayaran yang mungkin
        $status = ['pending', 'success', 'failed'];

        // Metode pembayaran yang tersedia
        $metode = ['tunai', 'transfer', 'midtrans'];

        // Generate pembayaran untuk 6 bulan terakhir
        for ($i = 0; $i < 6; $i++) {
            $bulan = Carbon::now()->subMonths($i);

            foreach ($santri as $s) {
                // 80% kemungkinan sudah bayar
                if (rand(1, 100) <= 80) {
                    PembayaranSpp::create([
                        'santri_id' => $s->id,
                        'tanggal_bayar' => $bulan->format('Y-m-d'),
                        'bulan' => $bulan->format('m'),
                        'tahun' => $bulan->format('Y'),
                        'nominal' => 500000, // Nominal default
                        'metode_pembayaran' => $metode[array_rand($metode)],
                        'status' => $status[array_rand($status)],
                        'keterangan' => 'Pembayaran SPP ' . $bulan->format('F Y'),
                        'petugas_id' => $petugas->id
                    ]);
                }
            }
        }

        // Generate beberapa pembayaran hari ini
        foreach ($santri->random(2) as $s) {
            PembayaranSpp::create([
                'santri_id' => $s->id,
                'tanggal_bayar' => now(),
                'bulan' => now()->format('m'),
                'tahun' => now()->format('Y'),
                'nominal' => 500000,
                'metode_pembayaran' => $metode[array_rand($metode)],
                'status' => 'success',
                'keterangan' => 'Pembayaran SPP ' . now()->format('F Y'),
                'petugas_id' => $petugas->id
            ]);
        }

        // Generate beberapa pembayaran pending
        foreach ($santri->random(3) as $s) {
            PembayaranSpp::create([
                'santri_id' => $s->id,
                'tanggal_bayar' => now(),
                'bulan' => now()->format('m'),
                'tahun' => now()->format('Y'),
                'nominal' => 500000,
                'metode_pembayaran' => 'midtrans',
                'status' => 'pending',
                'keterangan' => 'Menunggu pembayaran',
                'petugas_id' => null
            ]);
        }
    }
}
