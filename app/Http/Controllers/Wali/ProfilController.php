<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('wali.profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'email' => $request->email
        ]);

        return redirect()->route('wali.profil')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
