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
        ]);

        DB::table('users')->where('id', Auth::id())->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp
        ]);

        return redirect()
            ->route('wali.profil.edit')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
