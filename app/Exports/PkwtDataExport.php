<?php

namespace App\Exports;

use App\Models\PkwtReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class PkwtDataExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $query = PkwtReport::query();

        if ($this->bulan) {
            $query->where('bulan', $this->bulan);
        }

        if ($this->tahun) {
            $query->where('tahun', $this->tahun);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Bulan',
            'Tahun',
            'Nomor Pencatatan',
            'Nama Perusahaan',
            'Alamat Pimpinan',
            'Nama Pekerja',
            'Jumlah Pekerja',
            'Jabatan',
            'Masa Kontrak',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->bulan,
            $row->tahun,
            $row->no_pencatatan,
            $row->nama_perusahaan,
            $row->alamat_pimpinan,
            $row->nama_pekerja,
            $row->total_pekerja,
            $row->jabatan,
            $row->masa_kontrak,
            $row->keterangan,
        ];
    }
}
