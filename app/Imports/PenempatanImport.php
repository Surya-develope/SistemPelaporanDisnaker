<?php

namespace App\Imports;

use App\Models\Penempatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenempatanImport implements ToModel, WithHeadingRow
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function model(array $row)
    {
        if (!array_key_exists('nama', $row) && !array_key_exists('nama_perusahaan', $row)) {
            throw new \Exception('Format file salah! Pastikan file Excel yang diunggah sesuai dengan template.');
        }
        if (empty($row['nama']) || empty($row['nama_perusahaan'])) {
            return null;
        }

        return new Penempatan([
            'nama'                        => $row['nama'],
            'email'                       => $row['email'] ?? null,
            'judul_lowongan'              => $row['judul_lowongan'] ?? '-',
            'kode_kbji'                   => $row['kode_kbji'] ?? null,
            'nama_perusahaan'             => $row['nama_perusahaan'],
            'pendidikan_terakhir_pelamar' => $row['pendidikan_terakhir_pelamar'] ?? null,
            'pendidikan_minimal_loker'    => $row['pendidikan_minimal_loker'] ?? null,
            'domisili_pelamar'            => $row['domisili_pelamar'] ?? null,
            'domisili_lowongan'           => $row['domisili_lowongan'] ?? null,
            'tanggal_melamar'             => $this->parseDate($row['tanggal_melamar'] ?? null),
            'tanggal_diterima'            => $this->parseDate($row['tanggal_diterima'] ?? null),
            'bulan'                       => $this->bulan,
            'tahun'                       => $this->tahun,
        ]);
    }

    private function parseDate($dateValue, $format = 'Y-m-d')
    {
        if (empty($dateValue)) return null;

        if (is_numeric($dateValue)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue)->format($format);
            } catch (\Exception $e) {
                return null;
            }
        }

        // Replace slashes with dashes so PHP strtotime reads it as d-m-Y instead of m/d/Y
        $dateStr = str_replace('/', '-', $dateValue);
        $time = strtotime($dateStr);
        return $time ? date($format, $time) : null;
    }
}
