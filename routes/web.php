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

// Protected Routes - require admin authentication
Route::middleware(['admin.auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [InventoriController::class, 'dashboard'])->name('dashboard');

    // Master Barang
    Route::get('/master-barang', [InventoriController::class, 'masterBarang'])->name('master.barang');
    Route::post('/master-barang', [InventoriController::class, 'masterBarangStore'])->name('master.barang.store');
    Route::put('/master-barang/{barcode}', [InventoriController::class, 'masterBarangUpdate'])->name('master.barang.update');
    Route::delete('/master-barang/{barcode}', [InventoriController::class, 'masterBarangDestroy'])->name('master.barang.destroy');

    // Barang Masuk
    Route::get('/barang-masuk', [InventoriController::class, 'barangMasuk'])->name('barang.masuk');
    Route::post('/barang-masuk', [InventoriController::class, 'barangMasukStore'])->name('barang.masuk.store');
    Route::post('/barang-masuk-manual', [InventoriController::class, 'barangMasukManual'])->name('barang.masuk.manual');

    // Barang Keluar
    Route::get('/barang-keluar', [InventoriController::class, 'barangKeluar'])->name('barang.keluar');
    Route::post('/barang-keluar', [InventoriController::class, 'barangKeluarStore'])->name('barang.keluar.store');
    Route::post('/barang-keluar-manual', [InventoriController::class, 'barangKeluarManual'])->name('barang.keluar.manual');

    // Barang Retur
    Route::get('/barang-retur', [InventoriController::class, 'barangRetur'])->name('barang.retur');
    Route::post('/barang-retur', [InventoriController::class, 'barangReturStore'])->name('barang.retur.store');
    Route::delete('/barang-retur/{id}', [InventoriController::class, 'barangReturDestroy'])->name('barang.retur.destroy');

    // Laporan
    Route::get('/laporan', [InventoriController::class, 'laporan'])->name('laporan.index');

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

    // Laporan Barang per Rak
    Route::get('/laporan-rak', [InventoriController::class, 'laporanRak'])->name('laporan.rak');
    Route::get('/laporan-rak/pdf', [InventoriController::class, 'laporanRakPdf'])->name('laporan.rak.pdf');
    Route::get('/laporan-rak/excel', [InventoriController::class, 'laporanRakExcel'])->name('laporan.rak.excel');
    Route::get('/laporan-rak/csv', [InventoriController::class, 'laporanRakCsv'])->name('laporan.rak.csv');

    // Barang Rusak
    Route::get('/barang-rusak', [InventoriController::class, 'barangRusak'])->name('barang.rusak');
    Route::post('/barang-rusak', [InventoriController::class, 'barangRusakStore'])->name('barang.rusak.store');
    Route::put('/barang-rusak/{id}', [InventoriController::class, 'barangRusakUpdate'])->name('barang.rusak.update');
    Route::delete('/barang-rusak/{id}', [InventoriController::class, 'barangRusakDestroy'])->name('barang.rusak.destroy');

    // Laporan Barang Rusak
    Route::get('/laporan-rusak', [InventoriController::class, 'laporanRusak'])->name('laporan.rusak');
    Route::get('/laporan-rusak/pdf', [InventoriController::class, 'laporanRusakPdf'])->name('laporan.rusak.pdf');
    Route::get('/laporan-rusak/excel', [InventoriController::class, 'laporanRusakExcel'])->name('laporan.rusak.excel');
    Route::get('/laporan-rusak/csv', [InventoriController::class, 'laporanRusakCsv'])->name('laporan.rusak.csv');

    // Search API
    Route::get('/search-barang', [InventoriController::class, 'searchBarang'])->name('search.barang');
});

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});
