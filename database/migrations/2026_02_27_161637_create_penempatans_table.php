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
        Schema::create('penempatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('judul_lowongan');
            $table->string('kode_kbji')->nullable();
            $table->string('nama_perusahaan');
            $table->string('pendidikan_terakhir_pelamar')->nullable();
            $table->string('pendidikan_minimal_loker')->nullable();
            $table->string('domisili_pelamar')->nullable();
            $table->string('domisili_lowongan')->nullable();
            $table->date('tanggal_melamar')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penempatans');
    }
};
