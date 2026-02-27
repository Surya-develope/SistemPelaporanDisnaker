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
        Schema::create('lpks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lpk');
            $table->string('nama_pimpinan')->nullable();
            $table->integer('tahun_berdiri')->nullable();
            $table->text('alamat')->nullable();
            $table->string('status')->default('aktif'); // aktif/tidak aktif
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpks');
    }
};
