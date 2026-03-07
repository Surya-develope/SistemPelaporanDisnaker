<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

function createTemplate($filename, $headers) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set headers
    $col = 1;
    foreach ($headers as $header) {
        $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1';
        $sheet->setCellValue($cell, $header);
        
        // Style header (blue background, white text, bold)
        $sheet->getStyle($cell)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F81BD']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ]);
        
        // Auto width
        $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        $col++;
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
    echo "Created: $filename\n";
}

$lpkMasterHeaders = [
    'Nama LPK',
    'Nama Pimpinan',
    'Tahun Berdiri',
    'Alamat',
    'Bulan',
    'Tahun'
];

$lpkTrainingHeaders = [
    'Nama LPK',
    'Program Pelatihan',
    'Jumlah Peserta',
    'Jumlah Paket',
    'Bulan',
    'Tahun'
];

createTemplate(__DIR__ . '/storage/app/public/template_master_lpk.xlsx', $lpkMasterHeaders);
createTemplate(__DIR__ . '/storage/app/public/template_training_lpk.xlsx', $lpkTrainingHeaders);
