<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LowonganKerja;
use App\Models\PencariKerja;
use App\Models\Penempatan;
use App\Imports\LowonganKerjaImport;
use App\Imports\PencariKerjaImport;
use App\Imports\PenempatanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PentaController extends Controller
{
    public function lowongan(Request $request)
    {
        $query = LowonganKerja::latest('tanggal_posting');
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $lowongans = $query->get();
        return View::make('penta.lowongan', compact('lowongans'));
    }

    public function exportLowongan(Request $request)
    {
        $query = LowonganKerja::latest('tanggal_posting');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, $row->bulan, $row->tahun, $row->judul_lowongan, $row->perusahaan, $row->tipe_pekerjaan, $row->kuota, $row->kuota_sisa, $row->status_lowongan, $row->tanggal_posting
            ];
        }

        $headings = ['No', 'Bulan', 'Tahun', 'Judul Lowongan', 'Perusahaan', 'Tipe', 'Kuota', 'Sisa Kuota', 'Status', 'Tanggal Posting'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GenericDataExport($exportData, $headings), 'lowongan_kerja.xlsx');
    }

    public function tenagaKerja(Request $request)
    {
        $query = PencariKerja::latest('tanggal_daftar');
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $pencaris = $query->get();
        return View::make('penta.tenaga_kerja', compact('pencaris'));
    }

    public function exportTenagaKerja(Request $request)
    {
        $query = PencariKerja::latest('tanggal_daftar');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, $row->bulan, $row->tahun, $row->nik, $row->nama_lengkap, $row->jenis_kelamin, $row->pendidikan_terakhir, $row->umur, $row->kategori_keahlian, $row->status_bekerja, $row->tanggal_daftar
            ];
        }

        $headings = ['No', 'Bulan', 'Tahun', 'NIK', 'Nama Lengkap', 'Jenis Kelamin', 'Pendidikan', 'Umur', 'Keahlian', 'Status', 'Tanggal Daftar'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GenericDataExport($exportData, $headings), 'pencari_kerja.xlsx');
    }

    public function rekap(Request $request)
    {
        $query = Penempatan::latest('tanggal_diterima');
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $penempatans = $query->get();
        return View::make('penta.rekap', compact('penempatans'));
    }

    public function exportRekap(Request $request)
    {
        $query = Penempatan::latest('tanggal_diterima');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, $row->bulan, $row->tahun, $row->nik, $row->nama_pekerja, $row->perusahaan, $row->jabatan, $row->status_penempatan, $row->tanggal_diterima
            ];
        }

        $headings = ['No', 'Bulan', 'Tahun', 'NIK', 'Nama Pekerja', 'Perusahaan', 'Jabatan', 'Status', 'Tanggal Diterima'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GenericDataExport($exportData, $headings), 'penempatan_kerja.xlsx');
    }

    public function importIndex()
    {
        return View::make('penta.import');
    }

    public function importLowongan(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);
        Excel::import(new LowonganKerjaImport(), $request->file('file'));
        return Redirect::back()->with('success', 'Data Lowongan Kerja berhasil diimpor!');
    }

    public function importPencari(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);
        Excel::import(new PencariKerjaImport(), $request->file('file'));
        return Redirect::back()->with('success', 'Data Pencari Kerja Aktif berhasil diimpor!');
    }

    public function importPenempatan(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);
        Excel::import(new PenempatanImport(), $request->file('file'));
        return Redirect::back()->with('success', 'Data Penempatan berhasil diimpor!');
    }

    public function updateLowongan(Request $request, $id)
    {
        $request->validate([
            'judul_lowongan' => 'required|string',
            'perusahaan' => 'required|string',
            'tipe_pekerjaan' => 'nullable|string',
            'kuota' => 'required|integer|min:0',
            'kuota_sisa' => 'required|integer|min:0',
            'status_lowongan' => 'required|string',
        ]);

        $lowongan = LowonganKerja::findOrFail($id);
        $lowongan->update($request->all());

        return Redirect::back()->with('success', 'Data Lowongan Kerja berhasil diperbarui!');
    }

    public function updatePencari(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'domisili' => 'required|string',
            'pendidikan_terakhir' => 'nullable|string',
            'status_verifikasi' => 'required|string',
        ]);

        $pencari = PencariKerja::findOrFail($id);
        $pencari->update($request->all());

        return Redirect::back()->with('success', 'Data Pencari Kerja Aktif berhasil diperbarui!');
    }

    public function updatePenempatan(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'judul_lowongan' => 'required|string',
            'nama_perusahaan' => 'required|string',
            'pendidikan_terakhir_pelamar' => 'required|string',
        ]);

        $penempatan = Penempatan::findOrFail($id);
        $penempatan->update($request->all());

        return Redirect::back()->with('success', 'Data Penempatan berhasil diperbarui!');
    }

    public function destroyLowongan($id)
    {
        $lowongan = LowonganKerja::findOrFail($id);
        $lowongan->delete();
        return Redirect::back()->with('success', 'Data Lowongan Kerja berhasil dihapus!');
    }

    public function destroyPencari($id)
    {
        $pencari = PencariKerja::findOrFail($id);
        $pencari->delete();
        return Redirect::back()->with('success', 'Data Pencari Kerja berhasil dihapus!');
    }

    public function destroyPenempatan($id)
    {
        $penempatan = Penempatan::findOrFail($id);
        $penempatan->delete();
        return Redirect::back()->with('success', 'Data Penempatan berhasil dihapus!');
    }
}
