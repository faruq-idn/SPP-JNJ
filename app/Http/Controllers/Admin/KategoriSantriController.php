<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSantri;
use App\Models\RiwayatTarifSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriSantriController extends Controller
{
    public function index()
    {
        $kategori = KategoriSantri::with(['tarifTerbaru'])->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'nominal_spp' => 'required|numeric|min:0'
        ]);

        $kategori = KategoriSantri::create([
            'nama' => $validated['nama'],
            'keterangan' => $validated['keterangan']
        ]);

        RiwayatTarifSpp::create([
            'kategori_id' => $kategori->id,
            'nominal' => $validated['nominal_spp'],
            'berlaku_mulai' => now(),
            'keterangan' => 'Tarif awal'
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori santri berhasil ditambahkan');
    }

    public function edit(KategoriSantri $kategori)
    {
        $kategori->load('riwayatTarif');
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriSantri $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori santri berhasil diperbarui');
    }

    public function updateTarif(Request $request, KategoriSantri $kategori)
    {
        $validated = $request->validate([
            'nominal' => 'required|numeric|min:0',
            'berlaku_mulai' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        RiwayatTarifSpp::where('kategori_id', $kategori->id)
            ->whereNull('berlaku_sampai')
            ->update(['berlaku_sampai' => $validated['berlaku_mulai']]);

        RiwayatTarifSpp::create([
            'kategori_id' => $kategori->id,
            'nominal' => $validated['nominal'],
            'berlaku_mulai' => $validated['berlaku_mulai'],
            'keterangan' => $validated['keterangan']
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Tarif SPP berhasil diperbarui');
    }

    public function destroy(KategoriSantri $kategori)
    {
        try {
            DB::beginTransaction();

            // Cek apakah ini kategori Reguler (konfirmasi 3x sudah di handle di frontend)
            if (strtolower($kategori->nama) === 'reguler') {
                // Jika ada santri yang masih menggunakan kategori Reguler
                if ($kategori->santri()->exists()) {
                    return back()->with('error', 'Tidak dapat menghapus kategori Reguler karena masih digunakan oleh santri');
                }
            } else {
                // Untuk kategori non-Reguler, pindahkan santri ke Reguler
                $kategoriReguler = KategoriSantri::where('nama', 'Reguler')->first();
                if (!$kategoriReguler) {
                    return back()->with('error', 'Kategori Reguler tidak ditemukan');
                }

                // Hitung jumlah santri yang akan dipindahkan
                $jumlahSantri = $kategori->santri()->count();

                if ($jumlahSantri > 0) {
                    // Pindahkan semua santri ke kategori Reguler
                    $kategori->santri()->update([
                        'kategori_id' => $kategoriReguler->id
                    ]);
                }
            }

            // Hapus riwayat tarif
            $kategori->riwayatTarif()->delete();

            // Hapus kategori
            $kategori->delete();

            DB::commit();

            $message = 'Kategori berhasil dihapus.';
            if (isset($jumlahSantri) && $jumlahSantri > 0) {
                $message .= " {$jumlahSantri} santri dipindahkan ke kategori Reguler.";
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
