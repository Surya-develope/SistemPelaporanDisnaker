<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class PkwtTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'nomor_pencatatan',
            'nama_perusahaan',
            'alamat_pimpinan',
            'nama_pekerja',
            'jumlah',
            'jabatan',
            'masa_kontrak',
            'keterangan'
        ];
    }
}
