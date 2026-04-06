<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PhiPengaduanTemplateExport implements WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NOMOR AGENDA',
            'TANGGAL KASUS DITERIMA',
            'NAMA PERUSAHAAN',
            'SEKTOR',
            'NAMA PEKERJA',
            'JML ORG',
            'JENIS PERSELISIHAN',
            'MEDIATOR',
            'PENYELESAIAN KASUS',
            'TANGGAL KASUS DISELESAIKAN'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true]
            ],
        ];
    }
}
