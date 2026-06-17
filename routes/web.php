<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoriController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth Routes (without middleware - needed for login page)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes - require authentication
Route::middleware(['admin.auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [InventoriController::class, 'dashboard'])->name('dashboard');

    // Admin Only Routes
    Route::middleware(['admin.role'])->group(function () {
        // User Management
        Route::get('/users', [InventoriController::class, 'users'])->name('users.index');
        Route::post('/users', [InventoriController::class, 'usersStore'])->name('users.store');
        Route::put('/users/{id}', [InventoriController::class, 'usersUpdate'])->name('users.update');
        Route::delete('/users/{id}', [InventoriController::class, 'usersDestroy'])->name('users.destroy');

        Route::get('/backup-database', [InventoriController::class, 'backupDatabase'])->name('backup.database');
        Route::post('/backup-database', [InventoriController::class, 'createDatabaseBackup'])->name('backup.database.create');
        Route::get('/backup-database/download/{filename}', [InventoriController::class, 'downloadDatabaseBackup'])->name('backup.database.download');
        Route::delete('/backup-database/{filename}', [InventoriController::class, 'destroyDatabaseBackup'])->name('backup.database.destroy');

        // Master Barang
        Route::get('/master-barang', [InventoriController::class, 'masterBarang'])->name('master.barang');
        Route::post('/master-barang', [InventoriController::class, 'masterBarangStore'])->name('master.barang.store');
        Route::put('/master-barang/{barcode}', [InventoriController::class, 'masterBarangUpdate'])->name('master.barang.update');
        Route::delete('/master-barang/{barcode}', [InventoriController::class, 'masterBarangDestroy'])->name('master.barang.destroy');
        Route::get('/master-barang/riwayat', [InventoriController::class, 'masterBarangRiwayat'])->name('master.barang.riwayat');
        Route::put('/master-barang/restore/{barcode}', [InventoriController::class, 'masterBarangRestore'])->name('master.barang.restore');

        // Barang Retur
        Route::get('/barang-retur', [InventoriController::class, 'barangRetur'])->name('barang.retur');
        Route::post('/barang-retur', [InventoriController::class, 'barangReturStore'])->name('barang.retur.store');
        Route::delete('/barang-retur/{id}', [InventoriController::class, 'barangReturDestroy'])->name('barang.retur.destroy');
        Route::get('/barang-retur/riwayat', [InventoriController::class, 'barangReturRiwayat'])->name('barang.retur.riwayat');

        // Barang Rusak
        Route::get('/barang-rusak', [InventoriController::class, 'barangRusak'])->name('barang.rusak');
        Route::post('/barang-rusak', [InventoriController::class, 'barangRusakStore'])->name('barang.rusak.store');
        Route::put('/barang-rusak/{id}', [InventoriController::class, 'barangRusakUpdate'])->name('barang.rusak.update');
        Route::delete('/barang-rusak/{id}', [InventoriController::class, 'barangRusakDestroy'])->name('barang.rusak.destroy');
        Route::get('/barang-rusak/riwayat', [InventoriController::class, 'barangRusakRiwayat'])->name('barang.rusak.riwayat');



        // Laporan Barang Rusak
        Route::get('/laporan-rusak', [InventoriController::class, 'laporanRusak'])->name('laporan.rusak');
        Route::get('/laporan-rusak/pdf', [InventoriController::class, 'laporanRusakPdf'])->name('laporan.rusak.pdf');
        Route::get('/laporan-rusak/excel', [InventoriController::class, 'laporanRusakExcel'])->name('laporan.rusak.excel');
        Route::get('/laporan-rusak/csv', [InventoriController::class, 'laporanRusakCsv'])->name('laporan.rusak.csv');
    });

    // Shared Routes (Admin & User)
    // Barang Masuk
    Route::get('/barang-masuk', [InventoriController::class, 'barangMasuk'])->name('barang.masuk');
    Route::post('/barang-masuk', [InventoriController::class, 'barangMasukStore'])->name('barang.masuk.store');
    Route::post('/barang-masuk-manual', [InventoriController::class, 'barangMasukManual'])->name('barang.masuk.manual');
    Route::get('/barang-masuk/riwayat', [InventoriController::class, 'barangMasukRiwayat'])->name('barang.masuk.riwayat');

    // Barang Keluar
    Route::get('/barang-keluar', [InventoriController::class, 'barangKeluar'])->name('barang.keluar');
    Route::post('/barang-keluar', [InventoriController::class, 'barangKeluarStore'])->name('barang.keluar.store');
    Route::post('/barang-keluar-manual', [InventoriController::class, 'barangKeluarManual'])->name('barang.keluar.manual');
    Route::post('/barang-keluar-quick-scan', [InventoriController::class, 'barangKeluarQuickScan'])->name('barang.keluar.quick.scan');
    Route::post('/barang-keluar/scanner-input', [InventoriController::class, 'barangKeluarScannerInput'])->name('barang.keluar.scanner.input');
    Route::get('/barang-keluar/riwayat', [InventoriController::class, 'barangKeluarRiwayat'])->name('barang.keluar.riwayat');

    // Laporan
    Route::get('/laporan', [InventoriController::class, 'laporan'])->name('laporan.index');

    // Laporan per Rak
    Route::get('/laporan-rak', [InventoriController::class, 'laporanRak'])->name('laporan.rak');
    Route::get('/laporan-rak/pdf', [InventoriController::class, 'laporanRakPdf'])->name('laporan.rak.pdf');
    Route::get('/laporan-rak/excel', [InventoriController::class, 'laporanRakExcel'])->name('laporan.rak.excel');
    Route::get('/laporan-rak/csv', [InventoriController::class, 'laporanRakCsv'])->name('laporan.rak.csv');

    // Laporan Masuk
    Route::get('/laporan-masuk', [InventoriController::class, 'laporanMasuk'])->name('laporan.masuk');
    Route::get('/laporan-masuk/pdf', [InventoriController::class, 'laporanMasukPdf'])->name('laporan.masuk.pdf');
    Route::get('/laporan-masuk/excel', [InventoriController::class, 'laporanMasukExcel'])->name('laporan.masuk.excel');
    Route::get('/laporan-masuk/csv', [InventoriController::class, 'laporanMasukCsv'])->name('laporan.masuk.csv');

    // Laporan Keluar
    Route::get('/laporan-keluar', [InventoriController::class, 'laporanKeluar'])->name('laporan.keluar');
    Route::get('/laporan-keluar/pdf', [InventoriController::class, 'laporanKeluarPdf'])->name('laporan.keluar.pdf');
    Route::get('/laporan-keluar/excel', [InventoriController::class, 'laporanKeluarExcel'])->name('laporan.keluar.excel');
    Route::get('/laporan-keluar/csv', [InventoriController::class, 'laporanKeluarCsv'])->name('laporan.keluar.csv');

    // Laporan Gabungan
    Route::get('/laporan-gabungan', [InventoriController::class, 'laporanGabungan'])->name('laporan.gabungan');
    Route::get('/laporan-gabungan/pdf', [InventoriController::class, 'laporanGabunganPdf'])->name('laporan.gabungan.pdf');
    Route::get('/laporan-gabungan/excel', [InventoriController::class, 'laporanGabunganExcel'])->name('laporan.gabungan.excel');
    Route::get('/laporan-gabungan/csv', [InventoriController::class, 'laporanGabunganCsv'])->name('laporan.gabungan.csv');

    // Search API
    Route::get('/search-barang', [InventoriController::class, 'searchBarang'])->name('search.barang');

    // Global Search API
    Route::get('/search-global', [InventoriController::class, 'globalSearch'])->name('global.search');

    // Profile Routes (accessible by all logged in users)
    Route::get('/profile', [InventoriController::class, 'profile'])->name('profile');
    Route::post('/profile/update-photo', [InventoriController::class, 'profileUpdatePhoto'])->name('profile.update.photo');
    Route::post('/profile/update-password', [InventoriController::class, 'profileUpdatePassword'])->name('profile.update.password');
});

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});
