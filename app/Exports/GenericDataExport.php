<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericDataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $headings;

    public function __construct($data, $headings)
    {
        $this->data = collect($data);
        $this->headings = $headings;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]], // Make header row bold
        ];
    }
}
