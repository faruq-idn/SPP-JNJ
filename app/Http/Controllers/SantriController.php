<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\KategoriSantri;
use App\Models\User;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index()
    {
        $santri = Santri::with(['wali', 'kategori'])->latest()->paginate(10);
        return view('admin.santri.index', compact('santri'));
    }

    public function create()
    {
        $kategori = KategoriSantri::all();
        $wali = User::where('role', 'wali')->get();
        return view('admin.santri.create', compact('kategori', 'wali'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|unique:santri',
            'nama' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'wali_id' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'jenjang' => 'required',
            'kelas' => 'required',
            'kategori_id' => 'required|exists:kategori_santri,id'
        ]);

        Santri::create($validated);
        return redirect()->route('admin.santri.index')->with('success', 'Data santri berhasil ditambahkan');
    }

    public function edit(Santri $santri)
    {
        $kategori = KategoriSantri::all();
        $wali = User::where('role', 'wali')->get();
        return view('admin.santri.edit', compact('santri', 'kategori', 'wali'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nisn' => 'required|unique:santri,nisn,'.$santri->id,
            'nama' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'wali_id' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'jenjang' => 'required',
            'kelas' => 'required',
            'kategori_id' => 'required|exists:kategori_santri,id'
        ]);

        $santri->update($validated);
        return redirect()->route('admin.santri.index')->with('success', 'Data santri berhasil diperbarui');
    }

    public function destroy(Santri $santri)
    {
        $santri->delete();
        return redirect()->route('admin.santri.index')->with('success', 'Data santri berhasil dihapus');
    }
}
