<?php

namespace App\Console\Commands;

use App\Models\Santri;
use App\Models\PembayaranSpp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class GenerateTagihanBulanan extends Command
{
    protected $signature = 'tagihan:generate {--bulan= : Format YYYY-MM}';
    protected $description = 'Generate tagihan SPP bulanan untuk santri aktif';

    public function handle()
    {
        try {
            $this->info('Memulai generate tagihan bulanan...');

            // Parse periode dari input
            $date = $this->option('bulan')
                ? Carbon::createFromFormat('Y-m', $this->option('bulan'))
                : Carbon::now();

            $bulan = str_pad($date->format('n'), 2, '0', STR_PAD_LEFT);
            $tahun = $date->format('Y');

            // Ambil semua santri aktif
            $santri_aktif = Santri::with(['kategori.tarifTerbaru'])
                ->where('status', 'aktif')
                ->get();

            if ($santri_aktif->isEmpty()) {
                $this->warn('Tidak ada santri aktif yang ditemukan.');
                return 0;
            }

            $total_tagihan = 0;
            $errors = [];

            foreach ($santri_aktif as $santri) {
                try {
                    // Log data santri
                    $this->line("Memproses santri: {$santri->nama} (ID: {$santri->id})");

                    // Validasi data santri
                    if (!$santri->kategori?->tarifTerbaru) {
                        $kategoriNama = $santri->kategori ? $santri->kategori->nama : '-';
                        throw new \Exception("Tarif SPP belum diatur untuk kategori {$kategoriNama}");
                    }

                    // Log nominal tarif
                    $nominal = $santri->kategori->tarifTerbaru->nominal;
                    $this->line("Nominal tarif: Rp " . number_format($nominal, 0, ',', '.'));

                    // Cek tagihan yang sudah ada
                    $tagihan_exists = PembayaranSpp::where('santri_id', $santri->id)
                        ->where('bulan', $bulan)
                        ->where('tahun', $tahun)
                        ->exists();

                    if ($tagihan_exists) {
                        $this->line("Tagihan sudah ada untuk {$santri->nama} periode {$bulan}/{$tahun}");
                        continue;
                    }

                    // Buat tagihan baru
                    $tagihan = PembayaranSpp::create([
                        'santri_id' => $santri->id,
                        'nominal' => $nominal,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'status' => 'pending',
                        'keterangan' => 'Tagihan SPP ' . $date->translatedFormat('F Y')
                    ]);

                    // Verifikasi tagihan berhasil dibuat
                    if (!$tagihan || !$tagihan->exists) {
                        throw new \Exception("Gagal menyimpan tagihan ke database");
                    }

                    $total_tagihan++;
                    $this->info("Berhasil membuat tagihan #{$tagihan->id} untuk {$santri->nama}");

                } catch (\Exception $e) {
                    $error_msg = "Error pada santri {$santri->nama} (ID: {$santri->id}): " . $e->getMessage();
                    $errors[] = $error_msg;
                    $this->error($error_msg);

                    // Log stack trace untuk debugging
                    Log::channel('tagihan')->error($error_msg, [
                        'exception' => $e,
                        'santri_id' => $santri->id,
                        'periode' => "{$bulan}/{$tahun}"
                    ]);
                }
            }

            // Log hasil
            $log_message = "Generate Tagihan {$bulan}/{$tahun}: Berhasil membuat {$total_tagihan} tagihan baru.";
            Log::channel('tagihan')->info($log_message);

            if (!empty($errors)) {
                Log::channel('tagihan')->error("Generate Tagihan Errors:", $errors);
                $this->error("Terdapat " . count($errors) . " error saat generate tagihan.");
            }

            $this->info($log_message);
            return 0;

        } catch (\Exception $e) {
            $error_message = "Gagal generate tagihan: " . $e->getMessage();
            Log::channel('tagihan')->error($error_message);
            $this->error($error_message);
            return 1;
        }
    }
}
