<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriSantriController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\SantriExportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Wali\DashboardController as WaliDashboardController;
use App\Http\Controllers\Wali\PembayaranController as WaliPembayaranController;
use App\Http\Controllers\Wali\ProfilController;
use App\Http\Controllers\Wali\TagihanController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Generate Tagihan Route
Route::post('/admin/pembayaran/generate-tagihan', [PembayaranController::class, 'generateTagihan'])
    ->name('admin.pembayaran.generate-tagihan');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Kategori Santri
    Route::resource('kategori', KategoriSantriController::class);
    
    // Santri
    Route::resource('santri', SantriController::class);
    Route::post('santri/update-status/{santri}', [SantriController::class, 'updateStatus'])->name('santri.update-status');
    Route::post('santri/kenaikan-kelas', [SantriController::class, 'kenaikanKelas'])->name('santri.kenaikan-kelas');
    Route::post('santri/batal-kenaikan-kelas', [SantriController::class, 'batalKenaikanKelas'])->name('santri.batal-kenaikan-kelas');
    Route::get('santri/{jenjang}/{kelas}', [SantriController::class, 'kelas'])->name('santri.kelas');
    Route::get('santri/search', [SantriController::class, 'search'])->name('santri.search');
    
    // Santri Import/Export
    Route::post('santri/import', [SantriExportController::class, 'importExcel'])->name('santri.import');
    Route::get('santri/template/download', [SantriExportController::class, 'downloadTemplate'])->name('santri.template.download');
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Pembayaran
    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('pembayaran/check-status', [PembayaranController::class, 'checkStatus'])->name('pembayaran.check-status');
    Route::get('pembayaran/{pembayaran}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::delete('pembayaran/hapus-tagihan', [PembayaranController::class, 'hapusTagihan'])->name('pembayaran.hapus-tagihan');

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/pembayaran', [LaporanController::class, 'pembayaran'])->name('laporan.pembayaran');
    Route::get('laporan/tunggakan', [LaporanController::class, 'tunggakan'])->name('laporan.tunggakan');
    Route::get('laporan/export/pembayaran', [LaporanController::class, 'exportPembayaran'])->name('laporan.export.pembayaran');
    Route::get('laporan/export/tunggakan', [LaporanController::class, 'exportTunggakan'])->name('laporan.export.tunggakan');
});

// Wali Routes
Route::prefix('wali')->middleware(['auth', 'role:wali'])->name('wali.')->group(function () {
    Route::get('dashboard', [WaliDashboardController::class, 'index'])->name('dashboard');
    Route::post('change-santri', [WaliDashboardController::class, 'changeSantri'])->name('change-santri');
    
    // Profil
    Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');
    
    // Tagihan & Pembayaran
    Route::get('tagihan', [TagihanController::class, 'index'])->name('tagihan');
    Route::post('pembayaran', [WaliPembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('pembayaran/{pembayaran}', [WaliPembayaranController::class, 'show'])->name('pembayaran.show');
    
    // Hubungkan Santri
    Route::get('hubungkan', [WaliDashboardController::class, 'hubungkan'])->name('hubungkan');
});
