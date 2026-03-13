<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangRetur extends Model
{
    use HasFactory;

    protected $table = 'barang_retur';

    protected $fillable = [
        'barang_keluar_id',
        'barcode',
        'jumlah_retur',
        'tanggal_retur',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_retur' => 'date',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id', 'id');
    }

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'barcode', 'barcode');
    }
}
