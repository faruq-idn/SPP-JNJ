<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriSantriController;
use App\Http\Controllers\Admin\KenaikanKelasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\SantriExportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Wali\DashboardController as WaliDashboardController;
use App\Http\Controllers\Wali\PembayaranController as WaliPembayaranController;
use App\Http\Controllers\Wali\ProfilController;
use App\Http\Controllers\Wali\TagihanController;
use App\Http\Controllers\Petugas\SantriController as PetugasSantriController;
use App\Http\Controllers\Petugas\PembayaranController as PetugasPembayaranController;


// Public Routes
Route::get('/', function () {
    if (Auth::check()) {
        switch (Auth::user()->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
                break;
            case 'petugas':
                return redirect()->route('petugas.dashboard');
                break;
            case 'wali':
                return redirect()->route('wali.dashboard');
                break;
        }
    }
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
    Route::get('kategori/list', [KategoriSantriController::class, 'list'])->name('kategori.list');
    Route::get('kategori/{kategori}/get-data', [KategoriSantriController::class, 'getData'])->name('kategori.getData');
    Route::post('kategori/{kategori}/update-tarif', [KategoriSantriController::class, 'updateTarif'])->name('kategori.updateTarif');
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
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::get('/create', [PembayaranController::class, 'create'])->name('create');
        Route::post('/', [PembayaranController::class, 'store'])->name('store');
        Route::get('/check-status', [PembayaranController::class, 'checkStatus'])->name('check-status');
        Route::get('/{pembayaran}', [PembayaranController::class, 'show'])->name('show');
        Route::post('/{id}/verifikasi', [PembayaranController::class, 'verifikasi'])->name('verifikasi');
        Route::post('/generate-tagihan', [PembayaranController::class, 'generateTagihan'])->name('generate-tagihan');
        Route::delete('/hapus-tagihan', [PembayaranController::class, 'hapusTagihan'])->name('hapus-tagihan');
    });

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/pembayaran', [LaporanController::class, 'pembayaran'])->name('laporan.pembayaran');
    Route::get('laporan/tunggakan', [LaporanController::class, 'tunggakan'])->name('laporan.tunggakan');
});

// Petugas Routes
Route::prefix('petugas')->middleware(['auth', 'role:petugas'])->name('petugas.')->group(function () {
    Route::get('dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    
    // Profil
    Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');
    
    // Santri
    Route::get('santri/{santri}', [PetugasSantriController::class, 'show'])->name('santri.show');
    
    // Pembayaran
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PetugasPembayaranController::class, 'index'])->name('index');
        Route::get('/{pembayaran}', [PetugasPembayaranController::class, 'show'])->name('show');
        Route::post('/{id}/verifikasi', [PetugasPembayaranController::class, 'verifikasi'])->name('verifikasi');
    });
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
