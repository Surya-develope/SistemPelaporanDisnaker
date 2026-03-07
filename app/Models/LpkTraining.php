<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpkTraining extends Model
{
    protected $fillable = [
        'nama_lpk',
        'program_pelatihan',
        'jumlah_peserta',
        'jumlah_paket',
        'bulan',
        'tahun',
    ];
}
