<?php

namespace App\Imports;

use App\Models\PencariKerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PencariKerjaImport implements ToModel, WithHeadingRow
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
        if (!array_key_exists('nik', $row) && !array_key_exists('nama', $row)) {
            throw new \Exception('Format file salah! Pastikan file Excel yang diunggah sesuai dengan template.');
        }
        if (empty($row['nik']) || empty($row['nama'])) {
            return null;
        }

        // Check if NIK already exists to avoid unique constraint crash
        $existing = PencariKerja::where('nik', $row['nik'])->first();
        if ($existing) {
            return null; // Skip duplicates
        }

        $jk_input = strtoupper(trim($row['jenis_kelamin'] ?? ''));
        if (in_array($jk_input, ['P', 'PEREMPUAN', 'WANITA'])) {
            $jk = 'P';
        } else {
            $jk = 'L'; // default to L
        }

        $status_input = strtoupper(trim($row['status_verifikasi'] ?? ''));
        if (str_contains($status_input, 'BELUM')) {
            $status = 'BELUM DIVERIFIKASI';
        } elseif (str_contains($status_input, 'SUDAH') || str_contains($status_input, 'DIVERIFIKASI') || $status_input === 'VERIFIED' || $status_input === 'AKTIF') {
            $status = 'DIVERIFIKASI';
        } else {
            $status = 'BELUM DIVERIFIKASI';
        }

        return new PencariKerja([
            'nik'                  => $row['nik'],
            'nama'                 => $row['nama'],
            'email'                => $row['email'] ?? null,
            'no_hp'                => $row['no_hp'] ?? '-',
            'tempat_tanggal_lahir' => $row['tempat_tanggal_lahir'] ?? '-',
            'alamat_domisili'      => $row['alamat_domisili'] ?? '-',
            'domisili'             => $row['domisili'] ?? '-',
            'jenis_kelamin'        => $jk,
            'kondisi_fisik'        => $row['kondisi_fisik'] ?? null,
            'pendidikan_terakhir'  => $row['pendidikan_terakhir'] ?? null,
            'jurusan'              => $row['jurusan'] ?? null,
            'tanggal_daftar'       => $this->parseDate($row['tanggal_daftar'] ?? null),
            'status_verifikasi'    => $status,
            'bulan'                => $this->bulan,
            'tahun'                => $this->tahun,
        ]);
    }

    private function parseDate($dateValue)
    {
        if (empty($dateValue)) return null;

        if (is_numeric($dateValue)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Replace slashes with dashes so PHP strtotime reads it as d-m-Y instead of m/d/Y
        $dateStr = str_replace('/', '-', $dateValue);
        $time = strtotime($dateStr);
        return $time ? date('Y-m-d', $time) : null;
    }
}
