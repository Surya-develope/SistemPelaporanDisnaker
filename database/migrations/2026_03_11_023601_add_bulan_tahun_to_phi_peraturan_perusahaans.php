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
        Schema::table('phi_peraturan_perusahaans', function (Blueprint $table) {
            if (!Schema::hasColumn('phi_peraturan_perusahaans', 'bulan')) {
                $table->integer('bulan')->nullable()->after('id');
            }
            if (!Schema::hasColumn('phi_peraturan_perusahaans', 'tahun')) {
                $table->integer('tahun')->nullable()->after('bulan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phi_peraturan_perusahaans', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
        });
    }
};
