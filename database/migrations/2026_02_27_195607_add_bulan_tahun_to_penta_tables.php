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
        Schema::table('pencari_kerjas', function (Blueprint $table) {
            $table->integer('bulan')->nullable()->after('status_verifikasi');
            $table->integer('tahun')->nullable()->after('bulan');
        });
        
        Schema::table('lowongan_kerjas', function (Blueprint $table) {
            $table->integer('bulan')->nullable()->after('tanggal_kadaluwarsa');
            $table->integer('tahun')->nullable()->after('bulan');
        });
        
        Schema::table('penempatans', function (Blueprint $table) {
            $table->integer('bulan')->nullable()->after('tanggal_diterima');
            $table->integer('tahun')->nullable()->after('bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencari_kerjas', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
        });
        
        Schema::table('lowongan_kerjas', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
        });

        Schema::table('penempatans', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
        });
    }
};
