<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\KategoriSantriController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Wali\DashboardController as WaliDashboard;
use App\Http\Controllers\Wali\TagihanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
require __DIR__.'/auth.php';

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Santri routes
        Route::resource('santri', SantriController::class);
        Route::get('/santri/search', [SantriController::class, 'search'])->name('santri.search');

        // Kategori routes
        Route::resource('kategori', KategoriSantriController::class);
        Route::post('kategori/{kategori}/tarif', [KategoriSantriController::class, 'updateTarif'])
            ->name('kategori.updateTarif');

        // Menu lain
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    // Petugas routes
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');

        // Data Santri
        Route::get('/santri', [SantriController::class, 'index'])->name('santri.index');
        Route::get('/santri/{santri}', [SantriController::class, 'show'])->name('santri.show');

        // Pembayaran
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
        Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // Wali routes
    Route::middleware(['role:wali'])->prefix('wali')->name('wali.')->group(function () {
        Route::get('/dashboard', [WaliDashboard::class, 'index'])->name('dashboard');
        Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan');
        Route::get('/pembayaran', [PembayaranController::class, 'riwayat'])->name('pembayaran');
    });

    // Redirect sesuai role setelah login
    Route::get('/dashboard', function() {
        $user = \Illuminate\Support\Facades\Auth::user();
        if($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        else if($user->role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        }
        return redirect()->route('wali.dashboard');
    })->name('dashboard');
});
