<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProfilController extends Controller
{
    public function edit()
    {
        $wali = Auth::user();
        return view('wali.profil.edit', compact('wali'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'no_hp' => 'required|string|max:15'
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'no_hp.required' => 'Nomor HP harus diisi'
        ]);

        DB::table('users')->where('id', Auth::id())->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
