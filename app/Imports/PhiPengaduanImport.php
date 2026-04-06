<?php

namespace App\Imports;

use App\Models\PhiReport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PhiPengaduanImport implements ToModel, WithHeadingRow
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
        if (empty($row['nama_perusahaan'])) {
            return null;
        }

        $tanggal_diterima = null;
        if (!empty($row['tanggal_kasus_diterima'])) {
            $tanggal_diterima = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_kasus_diterima'])->format('Y-m-d');
        }

        $tanggal_diselesaikan = null;
        if (!empty($row['tanggal_kasus_diselesaikan'])) {
            $tanggal_diselesaikan = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_kasus_diselesaikan'])->format('Y-m-d');
        }

        $metode_penyelesaian = trim($row['penyelesaian_kasus'] ?? '');
        $status_kasus = $metode_penyelesaian ? 'selesai' : 'berjalan';

        // Check if user manually inputted "berjalan"
        if (strtolower($metode_penyelesaian) == 'berjalan') {
            $status_kasus = 'berjalan';
            $metode_penyelesaian = null;
        }

        return new PhiReport([
            'user_id' => auth()->id() ?? 1,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'nama_perusahaan' => trim($row['nama_perusahaan'] ?? ''),
            'sektor' => trim($row['sektor'] ?? ''),
            'nama_pekerja' => trim($row['nama_pekerja'] ?? ''),
            'jml_org' => (int)($row['jml_org'] ?? $row['jumlah_orang'] ?? 0),
            'mediator' => trim($row['mediator'] ?? ''),
            'jenis_perselisihan' => trim($row['jenis_perselisihan'] ?? ''),
            'nomor_agenda' => trim($row['nomor_agenda'] ?? ''),
            'tanggal_diterima' => $tanggal_diterima,
            'tanggal_diselesaikan' => $tanggal_diselesaikan,
            'status_kasus' => $status_kasus,
            'metode_penyelesaian' => $metode_penyelesaian,
        ]);
    }
}
