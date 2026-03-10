<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLokasiUnit extends Model
{
    use HasFactory;

    protected $table = 'master_lokasi_unit';

    protected $fillable = [
        'lokasi',
    ];
}
