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
            $dataPkwt[] = [$no++, $row->bulan, $row->tahun, $row->nama_perusahaan, $row->no_pencatatan, $row->tanggal_pencatatan, $row->total_pekerja, $row->nama_pekerja, $row->jabatan];
        }
        $sheets[] = new GenericDataExport($dataPkwt, ['No', 'Bulan', 'Tahun', 'Nama Perusahaan', 'No Pencatatan', 'Tanggal', 'Total Pekerja', 'Nama Pekerja', 'Jabatan'], 'Laporan PKWT');

        // 5. Kasus PHI
        $phis = \App\Models\PhiReport::where('tahun', $t)->latest('created_at')->get();
        $dataPhi = [];
        $no = 1;
        foreach ($phis as $row) {
            $dataPhi[] = [$no++, $row->bulan, $row->tahun, $row->sisa_bulan_lalu, $row->kasus_masuk, $row->kasus_selesai, $row->sisa_kasus_akhir];
        }
        $sheets[] = new GenericDataExport($dataPhi, ['No', 'Bulan', 'Tahun', 'Sisa Bulan Lalu', 'Kasus Masuk', 'Kasus Selesai', 'Sisa Kasus Akhir'], 'Kasus PHI');

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
