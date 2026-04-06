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
        Schema::table('phi_reports', function (Blueprint $table) {
            $table->dropColumn([
                'sisa_bulan_lalu',
                'kasus_masuk',
                'selesai_bipartit',
                'selesai_pb',
                'selesai_anjuran',
                'selesai_lainnya',
                'sisa_kasus_akhir'
            ]);

            $table->string('nama_perusahaan')->after('tahun')->nullable();
            $table->string('sektor')->nullable()->after('nama_perusahaan');
            $table->string('nama_pekerja')->nullable()->after('sektor');
            $table->integer('jml_org')->nullable()->after('nama_pekerja');
            $table->string('mediator')->nullable()->after('jml_org');
            $table->string('jenis_perselisihan')->nullable()->after('mediator');
            $table->string('nomor_agenda')->nullable()->after('jenis_perselisihan');
            $table->date('tanggal_diterima')->nullable()->after('nomor_agenda');
            $table->date('tanggal_diselesaikan')->nullable()->after('tanggal_diterima');

            $table->enum('status_kasus', ['berjalan', 'selesai'])->default('berjalan')->after('tanggal_diselesaikan');
            $table->string('metode_penyelesaian')->nullable()->comment('Penyelesaian Kasus (Bipartit, dll)')->after('status_kasus');
        });

        Schema::table('phi_reports', function (Blueprint $table) {
            $table->string('file_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phi_reports', function (Blueprint $table) {
            $table->integer('sisa_bulan_lalu')->default(0);
            $table->integer('kasus_masuk')->default(0);
            $table->integer('selesai_bipartit')->default(0);
            $table->integer('selesai_pb')->default(0);
            $table->integer('selesai_anjuran')->default(0);
            $table->integer('selesai_lainnya')->default(0);
            $table->integer('sisa_kasus_akhir')->default(0);
            
            $table->dropColumn([
                'nama_perusahaan', 'sektor', 'nama_pekerja', 'jml_org', 'mediator', 'jenis_perselisihan',
                'nomor_agenda', 'tanggal_diterima', 'tanggal_diselesaikan', 'status_kasus', 'metode_penyelesaian'
            ]);
        });
    }
};
