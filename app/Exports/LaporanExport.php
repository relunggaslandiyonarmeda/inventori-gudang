<?php

namespace App\Exports;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\BarangRusak;
use App\Models\MasterBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    protected $jenis;
    protected $bulan;
    protected $tahun;

    public function __construct($jenis, $bulan, $tahun)
    {
        $this->jenis = $jenis;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        if ($this->jenis === 'masuk') {
            $data = BarangMasuk::with('masterBarang')
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->orderBy('tanggal', 'asc')
                ->get()
                ->map(function ($item, $index) {
                    return [
                        'No' => $index + 1,
                        'Tanggal' => $item->tanggal->format('d-m-Y'),
                        'Barcode' => $item->barcode,
                        'Nama Barang' => $item->masterBarang->nama_barang ?? '-',
                        'Jumlah' => $item->jumlah_masuk,
                        'Keterangan' => $item->keterangan ?? '-',
                    ];
                });
            return $data;
        } elseif ($this->jenis === 'keluar') {
            $data = BarangKeluar::with('masterBarang')
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->whereRaw('(SELECT COALESCE(SUM(jumlah_retur), 0) FROM barang_retur WHERE barang_retur.barang_keluar_id = barang_keluar.id) < barang_keluar.jumlah_keluar')
                ->orderBy('tanggal', 'asc')
                ->get()
                ->map(function ($item, $index) {
                    return [
                        'No' => $index + 1,
                        'Tanggal' => $item->tanggal->format('d-m-Y'),
                        'Barcode' => $item->barcode,
                        'Nama Barang' => $item->masterBarang->nama_barang ?? '-',
                        'Jumlah' => $item->jumlah_keluar,
                        'Keterangan' => $item->keterangan ?? '-',
                    ];
                });
            return $data;
        } elseif ($this->jenis === 'rak') {
            // Laporan per rak
            $rak = $this->bulan; // Using bulan parameter for rak value
            
            $query = MasterBarang::orderBy('lokasi_rak', 'asc')
                ->orderBy('nama_barang', 'asc');
            
            if ($rak !== 'all') {
                $query->where('lokasi_rak', $rak);
            }
            
            $data = $query->get()->map(function ($item, $index) {
                return [
                    'No' => $index + 1,
                    'Rak' => 'Rak ' . $item->lokasi_rak,
                    'Barcode' => $item->barcode,
                    'Nama Barang' => $item->nama_barang,
                    'Stok' => $item->stok,
                    'Status' => $item->stok > 10 ? 'Tersedia' : ($item->stok > 0 ? 'Terbatas' : 'Habis'),
                ];
            });
            return $data;
        } elseif ($this->jenis === 'rusak') {
            // Laporan barang rusak
            $data = $this->bulan->map(function ($item, $index) {
                return [
                    'No' => $index + 1,
                    'Nomor' => $item->nomor,
                    'Vehicle Group Code' => $item->vehicle_group_code,
                    'Description' => $item->description ?? '-',
                    'Tahun Perolehan' => $item->tahun_perolehan,
                    'Merek' => $item->merek,
                    'Lokasi Unit' => $item->lokasi_unit,
                    'Kondisi Unit' => $item->kondisi_unit == 'hidup' ? 'Hidup' : 'Mati',
                    'Keterangan' => $item->keterangan ?? '-',
                ];
            });
            return $data;
        } else {
            // Gabungan
            $masuks = BarangMasuk::with('masterBarang')
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->orderBy('tanggal', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'Tanggal' => $item->tanggal->format('d-m-Y'),
                        'Jenis' => 'MASUK',
                        'Barcode' => $item->barcode,
                        'Nama Barang' => $item->masterBarang->nama_barang ?? '-',
                        'Jumlah' => $item->jumlah_masuk,
                        'Keterangan' => $item->keterangan ?? '-',
                    ];
                });

            $keluars = BarangKeluar::with('masterBarang')
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->whereRaw('(SELECT COALESCE(SUM(jumlah_retur), 0) FROM barang_retur WHERE barang_retur.barang_keluar_id = barang_keluar.id) < barang_keluar.jumlah_keluar')
                ->orderBy('tanggal', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'Tanggal' => $item->tanggal->format('d-m-Y'),
                        'Jenis' => 'KELUAR',
                        'Barcode' => $item->barcode,
                        'Nama Barang' => $item->masterBarang->nama_barang ?? '-',
                        'Jumlah' => $item->jumlah_keluar,
                        'Keterangan' => $item->keterangan ?? '-',
                    ];
                });

            return $masuks->concat($keluars)->values();
        }
    }

    public function headings(): array
    {
        if ($this->jenis === 'gabungan') {
            return ['Tanggal', 'Jenis', 'Barcode', 'Nama Barang', 'Jumlah', 'Keterangan'];
        } elseif ($this->jenis === 'rak') {
            return ['No', 'Rak', 'Barcode', 'Nama Barang', 'Stok', 'Status'];
        } elseif ($this->jenis === 'rusak') {
            return ['No', 'Nomor', 'Vehicle Group Code', 'Description', 'Tahun Perolehan', 'Merek', 'Lokasi Unit', 'Kondisi Unit', 'Keterangan'];
        }
        return ['No', 'Tanggal', 'Barcode', 'Nama Barang', 'Jumlah', 'Keterangan'];
    }
}
