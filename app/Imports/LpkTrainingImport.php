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
        if (!array_key_exists('nama_lpk', $row) && !array_key_exists('program_pelatihan', $row)) {
            throw new \Exception('Format file salah! Pastikan file Excel yang diunggah sesuai dengan template.');
        }
        // Skip if LPK name is empty
        if (empty($row['nama_lpk'])) {
            return null;
        }

        // Clean text (e.g. "32 Orang" -> 32)
        $peserta = (int) filter_var($row['jumlah_peserta'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $paket = (int) filter_var($row['jumlah_paket'] ?? 0, FILTER_SANITIZE_NUMBER_INT);

        return new LpkTraining([
            'nama_lpk'          => $row['nama_lpk'],
            'program_pelatihan' => $row['program_pelatihan'] ?? '-',
            'jumlah_peserta'    => $peserta,
            'jumlah_paket'      => $paket,
            'bulan'             => $this->bulan,
            'tahun'             => $this->tahun,
        ]);
    }
}
