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
            $table->string('tahun_berdiri')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
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
