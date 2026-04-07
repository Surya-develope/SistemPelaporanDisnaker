<?php

namespace App\Imports;

use App\Models\LowonganKerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LowonganKerjaImport implements ToModel, WithHeadingRow
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
        if (!array_key_exists('perusahaan', $row) && !array_key_exists('judul_lowongan', $row)) {
            throw new \Exception('Format file salah! Pastikan file Excel yang diunggah sesuai dengan template.');
        }
        if (empty($row['judul_lowongan']) || empty($row['perusahaan'])) {
            return null;
        }

        // Handle numeric values
        $kuota = (int) filter_var($row['kuota'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $kuota_sisa = isset($row['kuota_sisa']) ? (int) filter_var($row['kuota_sisa'], FILTER_SANITIZE_NUMBER_INT) : $kuota;

        // Handle dates (Excel sometimes sends numeric dates, but we rely on simple parsing or assume it's text for now)
        // If it's complex Excel date, Maatwebsite has formatting tools.
        
        return new LowonganKerja([
            'judul_lowongan'        => $row['judul_lowongan'],
            'deskripsi_pekerjaan'   => $row['deskripsi_pekerjaan'] ?? null,
            'perusahaan'            => $row['perusahaan'],
            'kategori_pekerjaan'    => $row['kategori_pekerjaan'] ?? null,
            'tipe_pekerjaan'        => $row['tipe_pekerjaan'] ?? null,
            'sektor_pekerjaan'      => $row['sektor_pekerjaan'] ?? null,
            'fungsi_pekerjaan'      => $row['fungsi_pekerjaan'] ?? null,
            'kode_kbji'             => $row['kode_kbji'] ?? null,
            'minimal_pendidikan'    => $row['minimal_pendidikan'] ?? null,
            'keahlian_diperlukan'   => $row['keahlian_diperlukan'] ?? null,
            'kebutuhan_disabilitas' => $row['kebutuhan_disabilitas'] ?? null,
            'kuota'                 => $kuota,
            'kuota_sisa'            => $kuota_sisa,
            'status_lowongan'       => $row['status_lowongan'] ?? 'open',
            'tanggal_posting'       => $this->parseDate($row['tanggal_posting'] ?? null),
            'tanggal_kadaluwarsa'   => $this->parseDate($row['tanggal_kadaluwarsa'] ?? null, 'Y-m-d H:i:s'),
            'bulan'                 => $this->bulan,
            'tahun'                 => $this->tahun,
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
