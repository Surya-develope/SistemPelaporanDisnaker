<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penempatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'judul_lowongan',
        'kode_kbji',
        'nama_perusahaan',
        'pendidikan_terakhir_pelamar',
        'pendidikan_minimal_loker',
        'domisili_pelamar',
        'domisili_lowongan',
        'tanggal_melamar',
        'tanggal_diterima',
        'bulan',
        'tahun',
    ];
}
