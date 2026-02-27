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
        Schema::create('lowongan_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_lowongan');
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->string('perusahaan');
            $table->string('kategori_pekerjaan')->nullable();
            $table->string('tipe_pekerjaan')->nullable();
            $table->string('sektor_pekerjaan')->nullable();
            $table->string('fungsi_pekerjaan')->nullable();
            $table->string('kode_kbji')->nullable();
            $table->string('minimal_pendidikan')->nullable();
            $table->string('keahlian_diperlukan')->nullable();
            $table->string('kebutuhan_disabilitas')->nullable();
            $table->integer('kuota')->default(0);
            $table->integer('kuota_sisa')->default(0);
            $table->string('status_lowongan')->default('open');
            $table->date('tanggal_posting')->nullable();
            $table->dateTime('tanggal_kadaluwarsa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongan_kerjas');
    }
};
