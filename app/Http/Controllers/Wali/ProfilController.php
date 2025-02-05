<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:15'],
            'email' => [
                'required', 
                'email:rfc', 
                function ($attribute, $value, $fail) {
                    if (!app()->environment('local', 'testing')) {
                        // Periksa DNS hanya di production
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('Format email tidak valid.');
                        }
                        
                        $domain = substr(strrchr($value, "@"), 1);
                        if ($domain !== 'example.com' && !checkdnsrr($domain, 'MX')) {
                            $fail('Domain email tidak valid.');
                        }
                    } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        // Di local/testing, hanya validasi format basic
                        $fail('Format email tidak valid.');
                    }
                },
                'unique:users,email,' . $user->id
            ],
        ], [
            'no_hp.regex' => 'Format nomor HP tidak valid. Gunakan hanya angka, spasi, tanda plus (+), dan tanda hubung (-).',
            'no_hp.min' => 'Nomor HP minimal 10 digit.',
            'no_hp.max' => 'Nomor HP maksimal 15 digit.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'email' => $request->email
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
