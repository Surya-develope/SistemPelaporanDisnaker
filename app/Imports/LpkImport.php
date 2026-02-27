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
        // Skip if the 'nama_lpks' column is empty
        if (empty($row['nama_lpks'])) {
            return null;
        }

        // We use updateOrCreate to prevent duplicates based on the LPK Name
        return Lpk::updateOrCreate(
            ['nama_lpk' => $row['nama_lpks']], // Find by 'nama_lpks' header
            [
                'nama_pimpinan' => $row['nama pimpinan'] ?? null,
                'tahun_berdiri' => $row['tahun_berdiri'] ?? null,
                'alamat'        => $row['alamat'] ?? null,
                'status'        => 'aktif', // default assumed active unless otherwise specified
            ]
        );
    }
}
