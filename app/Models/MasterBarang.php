<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBarang extends Model
{
    use HasFactory;

    protected $table = 'master_barang';
    protected $primaryKey = 'barcode';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'barcode',
        'nama_barang',
        'stok',
        'lokasi_rak',
        'created_by',
        'updated_by',
    ];

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'barcode', 'barcode');
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'barcode', 'barcode');
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
