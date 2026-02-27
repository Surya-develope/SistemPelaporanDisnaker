<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_lowongan',
        'deskripsi_pekerjaan',
        'perusahaan',
        'kategori_pekerjaan',
        'tipe_pekerjaan',
        'sektor_pekerjaan',
        'fungsi_pekerjaan',
        'kode_kbji',
        'minimal_pendidikan',
        'keahlian_diperlukan',
        'kebutuhan_disabilitas',
        'kuota',
        'kuota_sisa',
        'status_lowongan',
        'tanggal_posting',
        'tanggal_kadaluwarsa',
        'bulan',
        'tahun',
    ];
}
