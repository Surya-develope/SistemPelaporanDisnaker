<?php

namespace App\Imports;

use App\Models\PhiPeraturanPerusahaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class PeraturanPerusahaanImport implements ToModel, WithHeadingRow
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
        // Parse date values correctly, handling both "YYYY-MM-DD" text and Excel serial numbers
        $masa_berlaku_awal = $this->transformDate($row['masa_berlaku_mulai'] ?? null);
        $masa_berlaku_akhir = $this->transformDate($row['masa_berlaku_berakhir'] ?? null);

        // Parse status PP
        $status_raw = strtolower(trim($row['status_pp'] ?? ''));
        $status_pp = 'Baru';
        if (str_contains($status_raw, 'perpanjangan') || $status_raw == 'p') {
            $status_pp = 'Perpanjangan';
        }

        return new PhiPeraturanPerusahaan([
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'nama_perusahaan' => trim($row['nama_perusahaan'] ?? ''),
            'sektor_usaha' => trim($row['sektor_usaha'] ?? ''),
            'alamat_perusahaan' => trim($row['alamat_dan_pimpinan'] ?? ''),
            'pekerja_lk' => (int)($row['pekerja_laki_laki'] ?? 0),
            'pekerja_pr' => (int)($row['pekerja_perempuan'] ?? 0),
            'status_pp' => $status_pp,
            'no_sk' => trim($row['nomor_sk_pp'] ?? ''),
            'pp_ke' => isset($row['pp_ke']) ? (int)$row['pp_ke'] : null,
            'masa_berlaku_awal' => $masa_berlaku_awal,
            'masa_berlaku_akhir' => $masa_berlaku_akhir,
            'keterangan' => trim($row['keterangan_tambahan'] ?? ''),
        ]);
    }

    /**
     * Mengubah format tanggal Excel menjadi Y-m-d MySQL.
     */
    private function transformDate($value, $format = 'Y-m-d')
    {
        if (!$value) {
            return null;
        }

        try {
            // Excel serial date format (misal 44197)
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format($format);
            }

            // String format
            return Carbon::parse($value)->format($format);
        }
        catch (\Exception $e) {
            return null;
        }
    }
}
