<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lpk extends Model
{
    protected $fillable = [
        'nama_lpk',
        'nama_pimpinan',
        'tahun_berdiri',
        'alamat',
        'status',
    ];

    public function lpkTrainings()
    {
        return $this->hasMany(LpkTraining::class);
    }
}
