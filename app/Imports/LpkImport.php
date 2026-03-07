<?php

namespace App\Imports;

use App\Models\Lpk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LpkImport implements ToModel, WithHeadingRow
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
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

        // We use updateOrCreate to prevent duplicates based on the LPK Name
        return Lpk::updateOrCreate(
            ['nama_lpk' => $row['nama_lpk']], // Find by 'nama_lpk' header
            [
                'nama_pimpinan' => $row['nama_pimpinan'] ?? null,
                'tahun_berdiri' => $tahun_berdiri,
                'alamat'        => $row['alamat'] ?? null,
                'status'        => 'aktif', // default assumed active unless otherwise specified
                'bulan'         => (int) ($row['bulan'] ?? date('n')),
                'tahun'         => (int) ($row['tahun'] ?? date('Y')),
            ]
        );
    }
}
