<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencariKerja extends Model
{
    use HasFactory;

    protected $table = 'pencari_kerjas';

    protected $fillable = [
        'nik',
        'nama',
        'email',
        'no_hp',
        'tempat_tanggal_lahir',
        'alamat_domisili',
        'domisili',
        'jenis_kelamin',
        'kondisi_fisik',
        'pendidikan_terakhir',
        'jurusan',
        'tanggal_daftar',
        'status_verifikasi',
    ];
}
