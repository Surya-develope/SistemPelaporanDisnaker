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
        Schema::create('phi_peraturan_perusahaans', function (Blueprint $table) {
            $table->id();
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->string('nama_perusahaan');
            $table->text('alamat_perusahaan')->nullable();
            $table->string('nama_pimpinan')->nullable();
            $table->string('sektor_usaha')->nullable();

            // Pekerja
            $table->integer('pekerja_lk')->default(0);
            $table->integer('pekerja_pr')->default(0);
            $table->integer('total_pekerja')->default(0);

            // Status PP
            $table->enum('status_pp', ['Baru', 'Perpanjangan']);

            // SK & Keterangan
            $table->string('no_sk')->nullable();
            $table->integer('pp_ke')->nullable();
            $table->date('masa_berlaku_awal')->nullable();
            $table->date('masa_berlaku_akhir')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phi_peraturan_perusahaans');
    }
};
