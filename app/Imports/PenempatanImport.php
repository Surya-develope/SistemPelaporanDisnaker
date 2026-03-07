<?php

namespace App\Imports;

use App\Models\Penempatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenempatanImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
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
            'tanggal_melamar'             => isset($row['tanggal_melamar']) && strtotime($row['tanggal_melamar']) ? date('Y-m-d', strtotime($row['tanggal_melamar'])) : null,
            'tanggal_diterima'            => isset($row['tanggal_diterima']) && strtotime($row['tanggal_diterima']) ? date('Y-m-d', strtotime($row['tanggal_diterima'])) : null,
            'bulan'                       => $row['bulan'] ?? date('n'),
            'tahun'                       => $row['tahun'] ?? date('Y'),
        ]);
    }
}
