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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'staf', 'pejabat', 'penta') DEFAULT 'staf'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'staf', 'pejabat') DEFAULT 'staf'");
    }
};
