<?php

namespace App\Imports;

use App\Models\PencariKerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PencariKerjaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nik']) || empty($row['nama'])) {
            return null;
        }

        // Check if NIK already exists to avoid unique constraint crash
        $existing = PencariKerja::where('nik', $row['nik'])->first();
        if ($existing) {
            return null; // Skip duplicates
        }

        return new PencariKerja([
            'nik'                  => $row['nik'],
            'nama'                 => $row['nama'],
            'email'                => $row['email'] ?? null,
            'no_hp'                => $row['no_hp'] ?? '-',
            'tempat_tanggal_lahir' => $row['tempat_tanggal_lahir'] ?? '-',
            'alamat_domisili'      => $row['alamat_domisili'] ?? '-',
            'domisili'             => $row['domisili'] ?? '-',
            'jenis_kelamin'        => strtoupper($row['jenis_kelamin'] ?? 'L'),
            'kondisi_fisik'        => $row['kondisi_fisik'] ?? null,
            'pendidikan_terakhir'  => $row['pendidikan_terakhir'] ?? null,
            'jurusan'              => $row['jurusan'] ?? null,
            'tanggal_daftar'       => isset($row['tanggal_daftar']) && strtotime($row['tanggal_daftar']) ? date('Y-m-d', strtotime($row['tanggal_daftar'])) : null,
            'status_verifikasi'    => strtoupper($row['status_verifikasi'] ?? 'BELUM DIVERIFIKASI'),
        ]);
    }
}
