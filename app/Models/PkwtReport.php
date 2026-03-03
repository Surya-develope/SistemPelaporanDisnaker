<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PkwtReport extends Model
{
    protected $fillable = [
        'user_id',
        'bulan',
        'tahun',
        'no_pencatatan',
        'nama_perusahaan',
        'alamat_pimpinan',
        'nama_pekerja',
        'total_pekerja',
        'jabatan',
        'masa_kontrak',
        'file_path',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
