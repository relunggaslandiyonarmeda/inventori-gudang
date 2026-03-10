<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    use HasFactory;

    protected $table = 'barang_rusak';

    protected $fillable = [
        'nomor',
        'vehicle_group_code',
        'description',
        'tahun_perolehan',
        'merek',
        'foto',
        'lokasi_unit',
        'kondisi_unit',
        'keterangan',
    ];

    protected $casts = [
        'tahun_perolehan' => 'integer',
    ];

    /**
     * Get the master barang that matches the merek (brand)
     */
    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'merek', 'nama_barang');
    }
}
