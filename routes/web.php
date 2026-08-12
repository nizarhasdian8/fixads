<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\BahanMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanBahanBakuController;
use App\Http\Controllers\PermintaanBahanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// ==================== AUTH ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==================== DASHBOARD (kedua role) ====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== PESANAN ====================
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');

    Route::middleware('role:cio_marketing')->group(function () {
        Route::get('/pesanan/create', [PesananController::class, 'create'])->name('pesanan.create');
        Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
        
        // Route Cek Duplikat via AJAX
        Route::post('/pesanan/check-duplicate', [PesananController::class, 'checkDuplicate'])->name('pesanan.check-duplicate');
        
        // Route Download PDF Pesanan
        Route::get('/pesanan/pdf', [PesananController::class, 'downloadPdf'])->name('pesanan.pdf');
        
        // Route Hapus Pesanan
        Route::delete('/pesanan/{pesanan}', [PesananController::class, 'destroy'])->name('pesanan.destroy');
    });

    // Route dengan parameter {pesanan} HARUS didaftarkan setelah /pesanan/create, /pesanan/pdf, dll.
    Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');

    // Update status bisa diakses CIO Production (Update Produksi) & CIO Marketing (Diterima Pelanggan)
    Route::put('/pesanan/{pesanan}/status', [PesananController::class, 'updateStatus'])
        ->middleware('role:cio_marketing,cio_production')
        ->name('pesanan.update-status');

    // ==================== DATA PRODUK (CIO Marketing) ====================
    Route::middleware('role:cio_marketing')->group(function () {
        Route::resource('produk', ProdukController::class)->except(['show']);
    });

    // ==================== MONITORING STOK ====================
    Route::get('/bahan-baku', [BahanBakuController::class, 'index'])->name('bahan-baku.index');

    Route::middleware('role:cio_marketing')->group(function () {
        Route::get('/bahan-baku/create', [BahanBakuController::class, 'create'])->name('bahan-baku.create');
        Route::post('/bahan-baku', [BahanBakuController::class, 'store'])->name('bahan-baku.store');
    });

    // ==================== PERMINTAAN BAHAN ====================
    Route::get('/pengajuan', [PermintaanBahanController::class, 'index'])->name('pengajuan.index');
    
    // Route Download PDF Permintaan Bahan (Bisa diakses Marketing & Production)
    Route::get('/pengajuan/pdf', [PermintaanBahanController::class, 'downloadPdf'])->name('pengajuan.pdf');

    Route::middleware('role:cio_production')->group(function () {
        Route::get('/pengajuan/create', [PermintaanBahanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PermintaanBahanController::class, 'store'])->name('pengajuan.store');
    });

    // Route dengan parameter {pengajuan} HARUS didaftarkan setelah /pengajuan/create dan /pengajuan/pdf.
    Route::get('/pengajuan/{pengajuan}', [PermintaanBahanController::class, 'show'])->name('pengajuan.show');

    Route::middleware('role:cio_marketing')->group(function () {
        Route::put('/pengajuan/{pengajuan}/status', [PermintaanBahanController::class, 'updateStatus'])->name('pengajuan.update-status');
    });

    // ==================== BAHAN MASUK (CIO Marketing) ====================
    Route::middleware('role:cio_marketing')->group(function () {
        Route::get('/bahan-masuk', [BahanMasukController::class, 'index'])->name('bahan-masuk.index');
        Route::get('/bahan-masuk/create', [BahanMasukController::class, 'create'])->name('bahan-masuk.create');
        Route::post('/bahan-masuk', [BahanMasukController::class, 'store'])->name('bahan-masuk.store');
    });

    // ==================== LAPORAN HARIAN & BULANAN BAHAN BAKU (CIO Production) ====================
    Route::middleware('role:cio_production')->group(function () {
        // Laporan Harian
        Route::get('/laporan-bahan', [LaporanBahanBakuController::class, 'harian'])->name('laporan.harian');
        Route::get('/laporan-bahan/pdf', [LaporanBahanBakuController::class, 'downloadPdf'])->name('laporan.harian.pdf');

        // Laporan Bulanan
        Route::get('/laporan-bulanan', [LaporanBahanBakuController::class, 'bulanan'])->name('laporan.bulanan');
        Route::get('/laporan-bulanan/pdf', [LaporanBahanBakuController::class, 'downloadPdfBulanan'])->name('laporan.bulanan.pdf');
    });
});