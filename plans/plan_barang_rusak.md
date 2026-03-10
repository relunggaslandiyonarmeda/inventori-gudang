# Rencana Fitur Baru: Barang Rusak (Damaged Goods)

## 📋 Ringkasan Permintaan
Menambahkan modul baru untuk mencatat barang-barang rusak dengan fitur:
- Input data barang rusak
- Foto barang (kamera/galeri)
- Laporan export PDF, Excel, CSV
- Tema sesuai dengan proyek existing

---

## 🎯 Fitur yang Akan Dibangun

### 1. Halaman Barang Rusak
- **Nomor**: Auto-generated, contoh: BR-001
  - Saat generate nomor, hitung total data + 1 (misal: 0 data → BR-001, 5 data → BR-006)
  - Setelah generate, nomor bersifat STATIS/TETAP
  - JIKA data BR-001 dihapus, maka BR-002 TIDAK berubah menjadi BR-001
  - Nomor baru akan mengikuti nomor terakhir yang ada (tidak ada gap filling)
  - Contoh: Ada BR-001, BR-002, BR-003. Jika BR-002 dihapus, tetap ada BR-001 dan BR-003. Data baru akan menjadi BR-004.
- **Vehicle Group Code**: 
  - Awalnya input text
  - Setelah tersimpan, menjadi dropdown dengan opsi data tersimpan
  - Bisa menambah data baru via text field
- **Description**: Sama pattern dengan vehicle group code
- **Tahun Perolehan**: Sama pattern
- **Merek**: Dropdown mengambil data dari `master_barang.nama_barang`
- **Foto**: 
  - Camera capture
  - Pilih dari penyimpanan
  - Simpan di folder storage/app/public/foto_barang_rusak/
- **Lokasi Unit**: Sama pattern dengan vehicle group code
- **Kondisi Unit**: Radio button (Hidup / Mati)
- **Keterangan**: Textarea
- **Aksi**: Update, Delete

### 2. Laporan Barang Rusak
- **Header PDF/Excel**:
  ```
  PT. UNION SAMPOERNA TRIPUTRA PERSADA
  Lampiran data peralatan IT diajukan untuk discrap
  ```
- **Export**: PDF, Excel, CSV
- **Filter**: Sesuai kebutuhan (tanggal, kondisi, dll)

---

## 🗂️ Struktur Database

### Tabel: barang_rusak
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT | Primary key |
| nomor | VARCHAR(20) | Auto-generated (BR-001, BR-002, ...) |
| vehicle_group_code | VARCHAR(100) | Dari input/dropdown |
| description | TEXT | Deskripsi barang |
| tahun_perolehan | YEAR | Tahun perolehan |
| merek | VARCHAR(100) | Dari master_barang |
| foto | VARCHAR(255) | Path foto |
| lokasi_unit | VARCHAR(100) | Lokasi unit |
| kondisi_unit | ENUM('hidup','mati') | Kondisi radio button |
| keterangan | TEXT | Keterangan tambahan |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: master_vehicle_group (reference)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT | Primary key |
| kode | VARCHAR(100) | Vehicle group code |
| created_at | TIMESTAMP | |

### Tabel: master_lokasi_unit (reference)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT | Primary key |
| lokasi | VARCHAR(100) | Nama lokasi |
| created_at | TIMESTAMP | |

---

## 📝 Langkah Implementasi

### Step 1: Database Migration
- [ ] Buat migration `barang_rusak`
- [ ] Buat migration `master_vehicle_group`
- [ ] Buat migration `master_lokasi_unit`

### Step 2: Models
- [ ] Buat model `BarangRusak`
- [ ] Buat model `MasterVehicleGroup`
- [ ] Buat model `MasterLokasiUnit`
- [ ] Update relasi di `MasterBarang` (optional)

### Step 3: Routes
- [ ] Tambah route untuk halaman barang rusak
- [ ] Tambah route untuk CRUD operations
- [ ] Tambah route untuk laporan (PDF, Excel, CSV)

### Step 4: Controller
- [ ] Tambah method `barangRusak` - tampilkan halaman
- [ ] Tambah method `barangRusakStore` - simpan data
- [ ] Tambah method `barangRusakUpdate` - update data
- [ ] Tambah method `barangRusakDestroy` - hapus data (reorder nomor)
- [ ] Tambah method `barangRusakPdf` - export PDF
- [ ] Tambah method `barangRusakExcel` - export Excel
- [ ] Tambah method `barangRusakCsv` - export CSV

### Step 5: Views
- [ ] Buat `resources/views/barang_rusak/index.blade.php`
- [ ] Include modal untuk add/edit
- [ ] Include modal untuk konfirmasi delete
- [ ] Include JavaScript untuk camera/gallery upload

### Step 6: Sidebar Menu
- [ ] Tambah menu "Barang Rusak" di `layouts/main.blade.php`

### Step 7: Laporan View
- [ ] Buat `resources/views/laporan/rusak.blade.php`
- [ ] Buat `resources/views/laporan/pdf/rusak.blade.php`

---

## 🎨 UI/UX Pattern (Sesuai Tema Existing)

### Warna Theme:
- Primary: `#4f46e5` (Indigo)
- Sidebar: `#1e1b4b` (Dark Indigo)
- Background: `#f1f5f9` (Light Gray)
- Cards: White with shadow

### Components:
- Card dengan border-radius 12px
- Bootstrap 5 Icons
- Modal untuk form input
- DataTables untuk tabel data
- Alerts untuk feedback

---

## 📦 Dependencies
- [x] Laravel 12.x - sudah ada
- [x] barryvdh/laravel-dompdf - sudah ada
- [x] maatwebsite/excel - sudah ada
- [ ] Tidak perlu package tambahan

---

## ⚠️ Catatan Penting

1. **Nomor STATIS**: Nomor barang rusak TIDAK berubah meskipun data dihapus. Contoh: BR-001 dihapus, BR-002 tetap BR-002, data baru menjadi BR-003
2. **Dynamic dropdown**: Saat simpan, masukkan ke tabel reference jika belum ada
3. **Foto storage**: Gunakan Laravel Storage untuk simplicity
4. **Kondisi unit**: Radio button dengan value 'hidup' dan 'mati'

---

## 📄 Spesifikasi PDF Report

### Layout Halaman:
- **Jumlah data per halaman**: Minimal 15 item
- **Ukuran font**: 10-11pt untuk readability
- **Spacing antar kolom**: Rapi dan evenly distributed
- **Gambar/Foto**:
  - Tidak gepeng (stretch)
  - Proporsional dengan aspect ratio asli
  - Ukuran konsisten (misal: 100x100 px)
  - Resolusi jelas dan tidak blur

### Header PDF:
```
PT. UNION SAMPOERNA TRIPUTRA PERSADA
Lampiran data peralatan IT diajukan untuk discrap
```

### Struktur Tabel PDF:
| No (1,2,3...) | Vehicle Group Code | Description | Tahun Perolehan | Merek | Foto Unit | Lokasi Unit | Kondisi Unit | Keterangan |
|---|---|---|---|---|---|---|---|---|

- Table menggunakan border untuk kejelasan
- Header bold dengan background color
- Foto menggunakan tag `<img>` dengan attribute `style="object-fit: contain"`
- "No" adalah nomor urut baris (1, 2, 3, ...) BUKAN nomor barang rusak (BR-001)
- Kolom: No (urut), Vehicle Group Code, Description, Tahun Perolehan, Merek, Foto Unit, Lokasi Unit, Kondisi Unit, Keterangan
