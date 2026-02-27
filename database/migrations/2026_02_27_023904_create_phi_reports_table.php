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
        Schema::create('phi_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('sisa_bulan_lalu');
            $table->integer('kasus_masuk');
            $table->integer('kasus_selesai');
            $table->integer('sisa_kasus_akhir');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phi_reports');
    }
};
