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
        Schema::create('barang_retur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_keluar_id');
            $table->string('barcode', 100);
            $table->integer('jumlah_retur');
            $table->date('tanggal_retur');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('barang_keluar_id')->references('id')->on('barang_keluar')->onDelete('cascade');
            $table->foreign('barcode')->references('barcode')->on('master_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_retur');
    }
};
