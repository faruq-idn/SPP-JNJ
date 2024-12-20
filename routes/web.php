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
use App\Http\Controllers\Wali\WaliDashboard;
use App\Http\Controllers\Wali\TagihanController;
use App\Http\Controllers\Wali\PembayaranController as WaliPembayaranController;
use App\Models\User;
use App\Http\Controllers\Wali\ProfilController;

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
        Route::get('/santri/{santri}/pembayaran', [SantriController::class, 'pembayaran'])->name('santri.pembayaran');
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
            Route::get('/{pembayaran}/print', 'print')->name('print');
            Route::delete('/hapus-tagihan', 'hapusTagihan')->name('hapus-tagihan');
            Route::get('/check-status', 'checkStatus')->name('check-status');
        });

        // Laporan routes
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/pembayaran', [LaporanController::class, 'pembayaran'])->name('pembayaran');
            Route::get('/tunggakan', [LaporanController::class, 'tunggakan'])->name('tunggakan');
            Route::get('/pembayaran/export', [LaporanController::class, 'exportPembayaran'])->name('export.pembayaran');
            Route::get('/tunggakan/export', [LaporanController::class, 'exportTunggakan'])->name('export.tunggakan');
        });

        // User management routes
        Route::resource('users', UserController::class)->except(['show']);

        // Ajax search routes
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

        // Pembayaran routes
        Route::get('/pembayaran/{pembayaran}', [PembayaranController::class, 'show'])->name('pembayaran.show');

        // Generate tagihan routes
        Route::post('/pembayaran/generate-tagihan', [PembayaranController::class, 'generateTagihan'])
            ->name('pembayaran.generate-tagihan');

        // Kenaikan kelas routes
        Route::post('/santri/kenaikan-kelas', [SantriController::class, 'kenaikanKelas'])
            ->name('santri.kenaikan-kelas');
    });

    // Petugas routes
    Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
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
    Route::middleware(['auth', 'role:wali'])->prefix('wali')->name('wali.')->group(function () {
        // Dashboard wali
        Route::get('/dashboard', [WaliDashboard::class, 'index'])->name('dashboard');
        Route::post('/change-santri', [WaliDashboard::class, 'changeSantri'])->name('change-santri');
        Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan');
        Route::get('/hubungkan', [WaliDashboard::class, 'hubungkan'])->name('hubungkan');
        Route::post('/pembayaran', [WaliPembayaranController::class, 'store'])->name('pembayaran.store');
        Route::post('/pembayaran/notification', [WaliPembayaranController::class, 'notification'])
            ->name('pembayaran.notification')
            ->withoutMiddleware(['auth', 'role:wali']);

        // Profil routes
        Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    });

    // Midtrans Notification Handler
    Route::post('payment-notification', [PembayaranController::class, 'notification'])
        ->name('payment.notification')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    // Midtrans Notification Handler (tanpa middleware auth dan csrf)
    Route::post('payment/notification', [WaliPembayaranController::class, 'notification'])
        ->name('payment.notification')
        ->withoutMiddleware([
            \App\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\Authenticate::class
        ]);
});
