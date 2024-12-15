<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSantri;
use App\Models\RiwayatTarifSpp;
use Illuminate\Http\Request;

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
        // Cek jika kategori adalah Reguler
        if (strtolower($kategori->nama) === 'reguler') {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori Reguler tidak dapat dihapus!');
        }

        // Cek jika kategori masih digunakan oleh santri
        if ($kategori->santri()->exists()) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh santri!');
        }

        // Hapus riwayat tarif terlebih dahulu
        $kategori->riwayatTarif()->delete();

        // Hapus kategori
        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
