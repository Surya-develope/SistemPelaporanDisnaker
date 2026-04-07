<?php

namespace App\Imports;

use App\Models\Lpk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LpkImport implements ToModel, WithHeadingRow
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
        if (!array_key_exists('nama_lpk', $row) && !array_key_exists('nama_pimpinan', $row)) {
            throw new \Exception('Format file salah! Pastikan file Excel yang diunggah sesuai dengan template.');
        }
        // Skip if the 'nama_lpk' column is empty
        if (empty($row['nama_lpk'])) {
            return null;
        }

        // Sanitize tahun_berdiri to be a valid year or null
        $tahun_berdiri = null;
        if (!empty($row['tahun_berdiri'])) {
            $cleaned = filter_var($row['tahun_berdiri'], FILTER_SANITIZE_NUMBER_INT);
            if (!empty($cleaned)) {
                $tahun_berdiri = (int) $cleaned;
            }
        }

        // Handle status mapping robustly
        $statusRaw = $row['status'] ?? $row['status_lpk'] ?? 'aktif';
        // Trim and lowercase: ' Tidak Aktif ' -> 'tidak aktif'
        $statusFixed = trim(strtolower($statusRaw));
        if ($statusFixed !== 'aktif' && $statusFixed !== 'tidak aktif') {
            // Default to aktif if unknown value
            $statusFixed = 'aktif';
        }

        // We use updateOrCreate to prevent duplicates based on the LPK Name
        return Lpk::updateOrCreate(
            ['nama_lpk' => $row['nama_lpk']], // Find by 'nama_lpk' header
            [
                'nama_pimpinan' => $row['nama_pimpinan'] ?? null,
                'tahun_berdiri' => $tahun_berdiri,
                'alamat'        => $row['alamat'] ?? null,
                'status'        => $statusFixed,
                'bulan'         => $this->bulan,
                'tahun'         => $this->tahun,
            ]
        );
    }
}
