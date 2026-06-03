<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_barang', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('barang_retur', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('barang_rusak', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('master_barang', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('barang_retur', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('barang_rusak', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
