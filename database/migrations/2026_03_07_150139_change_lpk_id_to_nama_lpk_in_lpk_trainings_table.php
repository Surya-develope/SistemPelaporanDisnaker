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
        Schema::table('lpk_trainings', function (Blueprint $table) {
            $table->dropForeign(['lpk_id']);
            $table->dropColumn('lpk_id');
            $table->string('nama_lpk')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpk_trainings', function (Blueprint $table) {
            $table->dropColumn('nama_lpk');
            $table->foreignId('lpk_id')->constrained('lpks')->onDelete('cascade');
        });
    }
};
