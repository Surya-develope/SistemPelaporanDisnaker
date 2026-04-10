<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DashboardExport implements WithMultipleSheets
{
    protected $tahun;

    public function __construct($tahun = null)
    {
        $this->tahun = $tahun ?? date('Y');
    }

    public function sheets(): array
    {
        $sheets = [];
        $t = $this->tahun;

        // 1. Pencari Kerja
        $pencaris = \App\Models\PencariKerja::where('tahun', $t)->latest('tanggal_daftar')->get();
        $dataPencari = [];
        $no = 1;
        foreach ($pencaris as $row) {
            $dataPencari[] = [$no++, $row->bulan, $row->tahun, $row->nik, $row->nama_lengkap, $row->jenis_kelamin, $row->pendidikan_terakhir, $row->umur, $row->kategori_keahlian, $row->status_bekerja, $row->tanggal_daftar];
        }
        $sheets[] = new GenericDataExport($dataPencari, ['No', 'Bulan', 'Tahun', 'NIK', 'Nama Lengkap', 'Jenis Kelamin', 'Pendidikan', 'Umur', 'Keahlian', 'Status', 'Tanggal Daftar'], 'Pencari Kerja');

        // 2. Lowongan Kerja
        $lowongans = \App\Models\LowonganKerja::where('tahun', $t)->latest('tanggal_posting')->get();
        $dataLowongan = [];
        $no = 1;
        foreach ($lowongans as $row) {
            $dataLowongan[] = [$no++, $row->bulan, $row->tahun, $row->judul_lowongan, $row->perusahaan, $row->tipe_pekerjaan, $row->kuota, $row->kuota_sisa, $row->status_lowongan, $row->tanggal_posting];
        }
        $sheets[] = new GenericDataExport($dataLowongan, ['No', 'Bulan', 'Tahun', 'Judul Lowongan', 'Perusahaan', 'Tipe', 'Kuota', 'Sisa Kuota', 'Status', 'Tanggal Posting'], 'Lowongan Kerja');

        // 3. Penempatan
        $penempatans = \App\Models\Penempatan::where('tahun', $t)->latest('tanggal_diterima')->get();
        $dataPenempatan = [];
        $no = 1;
        foreach ($penempatans as $row) {
            $dataPenempatan[] = [$no++, $row->bulan, $row->tahun, $row->nik, $row->nama_pekerja, $row->perusahaan, $row->jabatan, $row->status_penempatan, $row->tanggal_diterima];
        }
        $sheets[] = new GenericDataExport($dataPenempatan, ['No', 'Bulan', 'Tahun', 'NIK', 'Nama Pekerja', 'Perusahaan', 'Jabatan', 'Status', 'Tanggal Diterima'], 'Penempatan');

        // 4. Laporan PKWT
        $pkwts = \App\Models\PkwtReport::where('tahun', $t)->latest('created_at')->get();
        $dataPkwt = [];
        $no = 1;
        foreach ($pkwts as $row) {
            $dataPkwt[] = [
                $no++, 
                $row->bulan, 
                $row->tahun, 
                $row->no_pencatatan ?? '-', 
                $row->nama_perusahaan ?? '-',
                $row->alamat_perusahaan ?? '-', 
                $row->nama_pekerja ?? '-', 
                $row->total_pekerja ?? 0, 
                $row->jabatan ?? '-', 
                $row->masa_kontrak ?? '-',
                $row->keterangan ?? '-'
            ];
        }
        $sheets[] = new GenericDataExport($dataPkwt, ['NO', 'BULAN', 'TAHUN', 'NOMOR PENCATATAN', 'NAMA PERUSAHAAN', 'ALAMAT PERUSAHAAN', 'NAMA PEKERJA', 'JUMLAH PEKERJA', 'JABATAN', 'MASA KONTRAK', 'KETERANGAN'], 'Laporan PKWT');

        // 4.5 Peraturan Perusahaan
        $peraturans = \App\Models\PhiPeraturanPerusahaan::where('tahun', $t)->latest('created_at')->get();
        $dataPp = [];
        $no = 1;
        foreach ($peraturans as $row) {
            $masa_berlaku = ($row->masa_berlaku_awal && $row->masa_berlaku_akhir) 
                            ? \Carbon\Carbon::parse($row->masa_berlaku_awal)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($row->masa_berlaku_akhir)->format('d/m/Y') 
                            : '-';
            $dataPp[] = [$no++, $row->bulan, $row->tahun, $row->nama_perusahaan, $row->sektor_usaha, $row->total_pekerja, $row->status_pp, $row->no_sk, $row->pp_ke, $masa_berlaku];
        }
        $sheets[] = new GenericDataExport($dataPp, ['No', 'Bulan', 'Tahun', 'Nama Perusahaan', 'Sektor Usaha', 'Total Pekerja', 'Status PP', 'No SK PP', 'PP Ke', 'Masa Berlaku'], 'Peraturan Perusahaan');

        // 6. Kasus PHI
        $phis = \App\Models\PhiReport::where('tahun', $t)->latest('created_at')->get();
        $dataPhi = [];
        $no = 1;
        foreach ($phis as $row) {
            $dataPhi[] = [
                $no++, 
                $row->nomor_agenda ?? '-',
                $row->tanggal_diterima ? \Carbon\Carbon::parse($row->tanggal_diterima)->format('Y-m-d') : '-',
                $row->nama_perusahaan, 
                $row->sektor ?? '-',
                $row->nama_pekerja ?? '-',
                $row->jml_org ?? 0, 
                $row->jenis_perselisihan ?? '-', 
                $row->mediator ?? '-',
                $row->metode_penyelesaian ?? '-',
                $row->tanggal_diselesaikan ? \Carbon\Carbon::parse($row->tanggal_diselesaikan)->format('Y-m-d') : '-'
            ];
        }
        $headingsPhi = [
            'NO', 'NOMOR AGENDA', 'TANGGAL KASUS DITERIMA', 'NAMA PERUSAHAAN', 'SEKTOR', 
            'NAMA PEKERJA', 'JML ORG', 'JENIS PERSELISIHAN', 'MEDIATOR', 'PENYELESAIAN KASUS', 'TANGGAL KASUS DISELESAIKAN'
        ];
        $sheets[] = new GenericDataExport($dataPhi, $headingsPhi, 'Kasus PHI');

        // 6. LPK Aktif
        $lpkAktif = \App\Models\Lpk::where('status', 'aktif')->whereYear('created_at', '<=', $t)->oldest('nama_lpk')->get();
        $dataLpkAktif = [];
        $no = 1;
        foreach ($lpkAktif as $row) {
            $dataLpkAktif[] = [$no++, $row->bulan, $row->tahun, $row->nama_lpk, $row->nama_pimpinan, $row->alamat, $row->no_izin, $row->izin_berlaku_sampai];
        }
        $sheets[] = new GenericDataExport($dataLpkAktif, ['No', 'Bulan', 'Tahun', 'Nama LPK', 'Nama Pimpinan', 'Alamat', 'No Izin', 'Berlaku Sampai'], 'LPK Aktif');

        // 7. LPK Nonaktif
        $lpkNon = \App\Models\Lpk::where('status', 'tidak aktif')->whereYear('created_at', '<=', $t)->oldest('nama_lpk')->get();
        $dataLpkNon = [];
        $no = 1;
        foreach ($lpkNon as $row) {
            $dataLpkNon[] = [$no++, $row->bulan, $row->tahun, $row->nama_lpk, $row->nama_pimpinan, $row->alamat, $row->no_izin, $row->izin_berlaku_sampai];
        }
        $sheets[] = new GenericDataExport($dataLpkNon, ['No', 'Bulan', 'Tahun', 'Nama LPK', 'Nama Pimpinan', 'Alamat', 'No Izin', 'Berlaku Sampai'], 'LPK Tidak Aktif');

        // 8. Pelatihan
        $pelatihans = \App\Models\LpkTraining::where('tahun', $t)->latest('created_at')->get();
        $dataPelatihan = [];
        $no = 1;
        foreach ($pelatihans as $row) {
            $namaLpk = $row->nama_lpk ? $row->nama_lpk : '-';
            $dataPelatihan[] = [$no++, $row->bulan, $row->tahun, $namaLpk, $row->program_pelatihan, $row->jumlah_peserta, $row->jumlah_paket];
        }
        $sheets[] = new GenericDataExport($dataPelatihan, ['No', 'Bulan', 'Tahun', 'Nama LPK', 'Program Pelatihan', 'Jumlah Peserta', 'Jumlah Paket'], 'Pelatihan');

        return $sheets;
    }
}
