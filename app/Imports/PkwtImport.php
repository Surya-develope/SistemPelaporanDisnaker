<?php

namespace App\Imports;

use App\Models\PkwtReport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PkwtImport implements ToModel, WithHeadingRow
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new PkwtReport([
            'user_id' => session('user_id') ?? 1,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'no_pencatatan' => trim($row['nomor_pencatatan'] ?? $row['no_pencatatan'] ?? ''),
            'nama_perusahaan' => trim($row['nama_perusahaan'] ?? $row['perusahaan'] ?? ''),
            'alamat_pimpinan' => trim($row['alamat_pimpinan'] ?? $row['alamat'] ?? ''),
            'nama_pekerja' => trim($row['nama_pekerja'] ?? ''),
            'total_pekerja' => (int)($row['jumlah'] ?? $row['total_pekerja'] ?? 0),
            'jabatan' => trim($row['jabatan'] ?? ''),
            'masa_kontrak' => trim($row['masa_kontrak'] ?? ''),
            'keterangan' => trim($row['keterangan'] ?? ''),
        ]);
    }
}
