<?php

namespace App\Imports;

use App\Models\LpkTraining;
use App\Models\Lpk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LpkTrainingImport implements ToModel, WithHeadingRow
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
        // Skip if LPK name is empty
        if (empty($row['nama_lpklembaga'])) {
            return null;
        }

        // Find the LPK from database to get its ID
        $lpk = Lpk::where('nama_lpk', $row['nama_lpklembaga'])->first();

        // If LPK doesn't exist, we can create it or skip it. Here we try to create an empty one just in case so the training isn't lost.
        if (!$lpk) {
            $lpk = Lpk::create([
                'nama_lpk' => $row['nama_lpklembaga'],
            ]);
        }

        // Clean text (e.g. "32 Orang" -> 32)
        $peserta = (int) filter_var($row['jumlah_peserta'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $paket = (int) filter_var($row['jumlah_paket'] ?? 0, FILTER_SANITIZE_NUMBER_INT);

        return new LpkTraining([
            'lpk_id'            => $lpk->id,
            'program_pelatihan' => $row['program_pelatihan_porglat'] ?? $row['program_pelatihan'] ?? '-',
            'jumlah_peserta'    => $peserta,
            'jumlah_paket'      => $paket,
            'bulan'             => $this->bulan,
            'tahun'             => $this->tahun,
        ]);
    }
}
