<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhiReport extends Model
{
    protected $fillable = [
        'user_id',
        'bulan',
        'tahun',
        'nama_perusahaan',
        'sektor',
        'nama_pekerja',
        'jml_org',
        'mediator',
        'jenis_perselisihan',
        'nomor_agenda',
        'tanggal_diterima',
        'tanggal_diselesaikan',
        'status_kasus',
        'metode_penyelesaian',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
