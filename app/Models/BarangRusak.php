<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangRusak extends Model
{
    use HasFactory, SoftDeletes;

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
        'created_by',
        'updated_by',
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

    /**
     * Get the user who created this record
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated this record
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
