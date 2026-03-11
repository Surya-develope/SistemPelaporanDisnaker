<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhiPeraturanPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'phi_peraturan_perusahaans';

    protected $fillable = [
        'bulan',
        'tahun',
        'nama_perusahaan',
        'alamat_perusahaan',
        'nama_pimpinan',
        'sektor_usaha',
        'pekerja_lk',
        'pekerja_pr',
        'total_pekerja',
        'status_pp',
        'no_sk',
        'pp_ke',
        'masa_berlaku_awal',
        'masa_berlaku_akhir',
        'keterangan'
    ];

    /**
     * Set the total_pekerja automatically when pekerja_lk or pekerja_pr are set.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total_pekerja = ($model->pekerja_lk ?? 0) + ($model->pekerja_pr ?? 0);
        });
    }
}
