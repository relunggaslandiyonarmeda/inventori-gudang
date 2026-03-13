# Plan: Menu Retur (Return Goods)

## Overview
Menambahkan fitur **Retur** untuk menangani kasus barang yang sudah dicatat di **Barang Keluar** namun kemudian dikembalikan (tidak jadi diambil oleh penerima).

## Problem Description
- Saat ini, ketika barang dicatat sebagai "Keluar", stok otomatis berkurang
- Jika penerima tidak jadi mengambil barang, tidak ada mekanisme untuk mengembalikan ke inventaris
- Perlu fitur untuk mencatat pengembalian barang dan otomatis menambah stok kembali

## Solution Design

### 1. Database - Create `barang_retur` table

```php
// database/migrations/YYYY_MM_DD_create_barang_retur_table.php
Schema::create('barang_retur', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('barang_keluar_id'); // Link to original barang_keluar
    $table->string('barcode', 100);
    $table->integer('jumlah_retur');
    $table->date('tanggal_retur');
    $table->text('keterangan')->nullable();
    $table->timestamps();
    
    $table->foreign('barang_keluar_id')->references('id')->on('barang_keluar')->onDelete('cascade');
    $table->foreign('barcode')->references('barcode')->on('master_barang')->onDelete('cascade');
});
```

### 2. Model - Create `BarangRetur` model

```php
// app/Models/BarangRetur.php
class BarangRetur extends Model
{
    protected $table = 'barang_retur';
    
    protected $fillable = [
        'barang_keluar_id',
        'barcode',
        'jumlah_retur',
        'tanggal_retur',
        'keterangan',
    ];
    
    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }
    
    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'barcode', 'barcode');
    }
}
```

### 3. Routes - Add retur routes

```php
// routes/web.php

// Barang Retur
Route::get('/barang-retur', [InventoriController::class, 'barangRetur'])->name('barang.retur');
Route::post('/barang-retur', [InventoriController::class, 'barangReturStore'])->name('barang.retur.store');
Route::delete('/barang-retur/{id}', [InventoriController::class, 'barangReturDestroy'])->name('barang.retur.destroy');

// Laporan Retur
Route::get('/laporan-retur', [InventoriController::class, 'laporanRetur'])->name('laporan.retur');
Route::get('/laporan-retur/pdf', [InventoriController::class, 'laporanReturPdf'])->name('laporan.retur.pdf');
Route::get('/laporan-retur/excel', [InventoriController::class, 'laporanReturExcel'])->name('laporan.retur.excel');
```

### 4. Controller - Add retur methods

```php
// app/Http/Controllers/InventoriController.php

// Tampilkan halaman retur dengan dropdown barang_keluar yang bisa diretur
public function barangRetur() { ... }

// Simpan retur - otomatis tambahkan ke barang_masuk untuk menambah stok
public function barangReturStore(Request $request) { ... }

// Hapus retur (dan kurangi stok kembali)
public function barangReturDestroy($id) { ... }

// Laporan Retur
public function laporanRetur() { ... }
public function laporanReturPdf() { ... }
public function laporanReturExcel() { ... }
```

### 5. Views - Create retur UI

```
resources/views/
└── barang_retur/
    └── index.blade.php    # Halaman utama retur
```

### 6. Navigation - Add menu to sidebar

Edit `resources/views/layouts/main.blade.php` to add new menu item.

## Implementation Steps

1. **Create Migration** - Create `barang_retur` table
2. **Create Model** - Create `BarangRetur.php` model
3. **Update Controller** - Add retur methods to `InventoriController.php`
4. **Add Routes** - Add retur routes to `web.php`
5. **Create View** - Create `barang_retur/index.blade.php`
6. **Update Navigation** - Add menu item in `main.blade.php`
7. **Add Reports** - Add laporan retur (optional enhancement)

## Key Features

| Feature | Description |
|---------|-------------|
| **Select from barang_keluar** | Dropdown untuk memilih barang yang sudah keluar |
| **Auto stock return** | Saat retur disimpan, stok di master_barang otomatis bertambah |
| **Quantity validation** | Validasi jumlah retur tidak boleh melebihi jumlah keluar |
| **History tracking** | Semua retur dicatat dengan link ke transaksi keluar asli |

## Notes

- Retur akan **menambah stok** kembali ke master_barang (seperti barang masuk)
- 1 retur = 1 record di barang_retur + 1 record di barang_masuk (untuk audit trail)
- User bisa melihat riwayat retur di menu Laporan > Retur

---

**Author:** Plan created via Architect Mode
**Date:** 2026-03-11
