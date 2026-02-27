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
        Schema::create('pencari_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('no_hp');
            $table->string('tempat_tanggal_lahir');
            $table->text('alamat_domisili');
            $table->string('domisili');
            $table->string('jenis_kelamin');
            $table->string('kondisi_fisik')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('jurusan')->nullable();
            $table->date('tanggal_daftar')->nullable();
            $table->string('status_verifikasi')->default('BELUM DIVERIFIKASI');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencari_kerjas');
    }
};
