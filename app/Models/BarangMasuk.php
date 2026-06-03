<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'barcode',
        'jumlah_masuk',
        'tanggal',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'barcode', 'barcode');
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
