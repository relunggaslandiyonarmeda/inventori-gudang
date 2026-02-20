<?php

namespace App\Exports;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
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
        }
        return ['No', 'Tanggal', 'Barcode', 'Nama Barang', 'Jumlah', 'Keterangan'];
    }
}
