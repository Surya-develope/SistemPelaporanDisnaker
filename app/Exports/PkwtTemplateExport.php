<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithEvents;

class PkwtTemplateExport implements WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    use ExportStylingTrait;
    public function headings(): array
    {
        return [
            'NOMOR PENCATATAN',
            'NAMA PERUSAHAAN',
            'ALAMAT PERUSAHAAN',
            'NAMA PEKERJA',
            'JUMLAH',
            'JABATAN',
            'MASA KONTRAK',
            'KETERANGAN'
        ];
    }


}
