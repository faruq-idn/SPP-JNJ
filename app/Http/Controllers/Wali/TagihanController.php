<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    public function index()
    {
        $santri = Santri::where('wali_id', Auth::id())->first();
        return view('wali.tagihan.index', compact('santri'));
    }

}
