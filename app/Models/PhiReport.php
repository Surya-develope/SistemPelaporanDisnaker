<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhiReport extends Model
{
    protected $fillable = [
        'user_id',
        'bulan',
        'tahun',
        'sisa_bulan_lalu',
        'kasus_masuk',
        'selesai_bipartit',
        'selesai_pb',
        'selesai_anjuran',
        'selesai_lainnya',
        'sisa_kasus_akhir',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }}
