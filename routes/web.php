<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\KategoriSantriController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Wali\DashboardController as WaliDashboard;
use App\Http\Controllers\Wali\TagihanController;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
require __DIR__.'/auth.php';

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Default dashboard redirect (pindahkan ke atas)
    Route::get('/dashboard', function() {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        }
        return redirect()->route('wali.dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Santri routes
        Route::get('/santri/search', [SantriController::class, 'search'])->name('santri.search');
        Route::get('/santri/kelas/{jenjang}/{kelas}', [SantriController::class, 'kelas'])->name('santri.kelas');
        Route::get('/santri/template', [SantriController::class, 'downloadTemplate'])->name('santri.template');
        Route::post('/santri/import', [SantriController::class, 'importExcel'])->name('santri.import');
        Route::resource('santri', SantriController::class);

        // Kategori routes
        Route::resource('kategori', KategoriSantriController::class);
        Route::post('kategori/{kategori}/tarif', [KategoriSantriController::class, 'updateTarif'])
            ->name('kategori.updateTarif');
        Route::delete('/kategori/{kategori}', [KategoriSantriController::class, 'destroy'])
            ->name('kategori.destroy');

        // Pembayaran routes
        Route::controller(PembayaranController::class)->prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

        // User management routes
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Petugas routes
    Route::middleware(['auth', 'role:petugas', 'prevent-back'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');

        // Data Santri
        Route::get('/santri', [SantriController::class, 'index'])->name('santri.index');
        Route::get('/santri/{santri}', [SantriController::class, 'show'])->name('santri.show');

        // Pembayaran routes untuk petugas
        Route::controller(PembayaranController::class)->group(function () {
            Route::get('/pembayaran', 'index')->name('pembayaran.index');
            Route::get('/pembayaran/create', 'create')->name('pembayaran.create');
            Route::post('/pembayaran', 'store')->name('pembayaran.store');
        });

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // Wali routes
    Route::middleware(['auth', 'role:wali', 'prevent-back'])->prefix('wali')->name('wali.')->group(function () {
        Route::get('/dashboard', [WaliDashboard::class, 'index'])->name('dashboard');
        Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan');
        Route::get('/pembayaran', [PembayaranController::class, 'riwayat'])->name('pembayaran');
    });
});
