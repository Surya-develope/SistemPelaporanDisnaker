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
            $table->string('no_hp')->nullable()->change();
            $table->string('tempat_tanggal_lahir')->nullable()->change();
            $table->text('alamat_domisili')->nullable()->change();
            $table->string('domisili')->nullable()->change();
            $table->string('jenis_kelamin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencari_kerjas', function (Blueprint $table) {
            $table->string('no_hp')->nullable(false)->change();
            $table->string('tempat_tanggal_lahir')->nullable(false)->change();
            $table->text('alamat_domisili')->nullable(false)->change();
            $table->string('domisili')->nullable(false)->change();
            $table->string('jenis_kelamin')->nullable(false)->change();
        });
    }
};
