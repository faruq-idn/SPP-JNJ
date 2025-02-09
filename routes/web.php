<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriSantriController;
use App\Http\Controllers\Admin\KenaikanKelasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PembayaranController;
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

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Kategori Santri
    Route::resource('kategori', KategoriSantriController::class);
    
    // Base Santri Resource
    Route::resource('santri', SantriController::class);

    // Additional Santri Routes - grouped by functionality
    Route::prefix('santri')->name('santri.')->group(function () {
        // Search & Export routes
        Route::get('search', [SantriController::class, 'search'])->name('search');
        Route::post('import', [SantriExportController::class, 'importExcel'])->name('import');
        Route::get('template/download', [SantriExportController::class, 'downloadTemplate'])->name('template.download');
        Route::get('export', [SantriExportController::class, 'export'])->name('export');
        
        // Kenaikan Kelas Routes
        Route::get('riwayat', [KenaikanKelasController::class, 'riwayat'])->name('riwayat');
        Route::post('kenaikan-kelas', [KenaikanKelasController::class, 'kenaikanKelas'])->name('kenaikan-kelas');
        Route::post('batal-kenaikan-kelas', [KenaikanKelasController::class, 'batalKenaikanKelas'])->name('batal-kenaikan-kelas');
        
        // Verifikasi Pembayaran Route
        Route::post('pembayaran/{id}/verifikasi', [SantriController::class, 'verifikasiPembayaran'])->name('pembayaran.verifikasi');
        
        // Class route (harus di akhir untuk menghindari konflik)
        Route::get('{jenjang}/{kelas}', [SantriController::class, 'kelas'])
            ->name('kelas')
            ->where([
                'jenjang' => 'smp|sma',
                'kelas' => '[0-9]{1,2}[A-B]'
            ]);
    });
    
    // User Management
    Route::get('users/search', [UserController::class, 'search'])->name('users.search');
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
    Route::get('pembayaran/success', [WaliPembayaranController::class, 'success'])->name('pembayaran.success');
    Route::get('pembayaran/error', [WaliPembayaranController::class, 'error'])->name('pembayaran.error');
    Route::get('pembayaran/{pembayaran}', [WaliPembayaranController::class, 'show'])->name('pembayaran.show');
    
    // Hubungkan Santri
    Route::get('hubungkan', [WaliDashboardController::class, 'hubungkan'])->name('hubungkan');
});
