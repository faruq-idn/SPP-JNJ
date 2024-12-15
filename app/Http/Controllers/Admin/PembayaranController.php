<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('admin.pembayaran.index', ['title' => 'Pembayaran SPP']);
    }
}
