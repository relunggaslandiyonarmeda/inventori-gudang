<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_barang', function (Blueprint $table) {
            $table->string('barcode', 100)->primary();
            $table->string('nama_barang', 255);
            $table->integer('stok')->default(0);
            $table->enum('lokasi_rak', ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'O']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_barang');
    }
};
