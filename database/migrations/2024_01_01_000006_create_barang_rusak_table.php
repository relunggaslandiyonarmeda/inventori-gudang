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
        Schema::create('barang_rusak', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 20)->unique(); // BR-001, BR-002, etc.
            $table->string('vehicle_group_code', 100);
            $table->text('description')->nullable();
            $table->year('tahun_perolehan');
            $table->string('merek', 100); // From master_barang
            $table->string('foto', 255)->nullable(); // Path to photo
            $table->string('lokasi_unit', 100);
            $table->enum('kondisi_unit', ['hidup', 'mati']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_rusak');
    }
};
