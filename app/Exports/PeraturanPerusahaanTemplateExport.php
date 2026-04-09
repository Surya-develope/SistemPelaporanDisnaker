<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithEvents;

class PeraturanPerusahaanTemplateExport implements WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    use ExportStylingTrait;
    public function headings(): array
    {
        return [
            'NAMA PERUSAHAAN',
            'SEKTOR USAHA',
            'ALAMAT DAN PIMPINAN',
            'PEKERJA LAKI LAKI',
            'PEKERJA PEREMPUAN',
            'STATUS PP',
            'NOMOR SK PP',
            'PP KE',
            'MASA BERLAKU MULAI',
            'MASA BERLAKU BERAKHIR',
            'KETERANGAN TAMBAHAN'
        ];
    }


}
