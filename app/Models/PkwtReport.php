<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PkwtReport extends Model
{
    protected $fillable = [
        'user_id',
        'bulan',
        'tahun',
        'total_perusahaan',
        'total_pekerja',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }}
