<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpkTraining extends Model
{
    protected $fillable = [
        'lpk_id',
        'program_pelatihan',
        'jumlah_peserta',
        'jumlah_paket',
        'bulan',
        'tahun',
    ];

    public function lpk()
    {
        return $this->belongsTo(Lpk::class);
    }
}
