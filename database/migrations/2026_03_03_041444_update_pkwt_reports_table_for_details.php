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
        Schema::table('pkwt_reports', function (Blueprint $table) {
            $table->string('no_pencatatan')->nullable()->after('tahun');
            $table->string('nama_perusahaan')->nullable()->after('no_pencatatan');
            $table->text('alamat_pimpinan')->nullable()->after('nama_perusahaan');
            $table->text('nama_pekerja')->nullable()->after('alamat_pimpinan');
            $table->string('jabatan')->nullable()->after('nama_pekerja');
            $table->string('masa_kontrak')->nullable()->after('jabatan');
            $table->text('keterangan')->nullable()->after('file_path');

            // Kolom lama dibuat nullable agar tidak error saat transisi
            $table->integer('total_perusahaan')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pkwt_reports', function (Blueprint $table) {
            $table->dropColumn(['no_pencatatan', 'nama_perusahaan', 'alamat_pimpinan', 'nama_pekerja', 'jabatan', 'masa_kontrak', 'keterangan']);
        });
    }
};
