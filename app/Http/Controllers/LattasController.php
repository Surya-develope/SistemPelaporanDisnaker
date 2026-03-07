<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\LpkImport;
use App\Imports\LpkTrainingImport;
use App\Models\Lpk;
use App\Models\LpkTraining;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LattasController extends Controller
{
    // Data Pelatihan
    public function pelatihan(Request $request)
    {
        $query = LpkTraining::with('lpk');

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $trainings = $query->get();
        return \Illuminate\Support\Facades\View::make('lattas.pelatihan', compact('trainings'));
    }

    public function exportPelatihan(Request $request)
    {
        $query = LpkTraining::with('lpk');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $namaLpk = $row->lpk ? $row->lpk->nama_lpk : '-';
            $exportData[] = [
                $no++, $row->bulan, $row->tahun, $namaLpk, $row->program_pelatihan, $row->jumlah_peserta, $row->jumlah_paket
            ];
        }

        $headings = ['No', 'Bulan', 'Tahun', 'Nama LPK', 'Program Pelatihan', 'Jumlah Peserta', 'Jumlah Paket'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GenericDataExport($exportData, $headings), 'program_pelatihan.xlsx');
    }

    // Rekap LPK Aktif
    public function lpkAktif(Request $request)
    {
        $query = Lpk::where('status', 'aktif');

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $lpks = $query->get();
        return \Illuminate\Support\Facades\View::make('lattas.lpk_aktif', compact('lpks'));
    }

    public function exportLpkAktif(Request $request)
    {
        $query = Lpk::where('status', 'aktif');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, $row->nama_lpk, $row->nama_pimpinan, $row->tahun_berdiri, $row->alamat, $row->status
            ];
        }

        $headings = ['No', 'Nama LPK', 'Pimpinan', 'Tahun Berdiri', 'Alamat', 'Status'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GenericDataExport($exportData, $headings), 'lpk_aktif.xlsx');
    }

    // Rekap LPK Non Aktif
    public function lpkNonaktif(Request $request)
    {
        $query = Lpk::where('status', 'tidak aktif');

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $lpks = $query->get();
        return \Illuminate\Support\Facades\View::make('lattas.lpk_nonaktif', compact('lpks'));
    }

    public function exportLpkNonaktif(Request $request)
    {
        $query = Lpk::where('status', 'tidak aktif');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, $row->nama_lpk, $row->nama_pimpinan, $row->tahun_berdiri, $row->alamat, $row->status
            ];
        }

        $headings = ['No', 'Nama LPK', 'Pimpinan', 'Tahun Berdiri', 'Alamat', 'Status'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GenericDataExport($exportData, $headings), 'lpk_tidak_aktif.xlsx');
    }

    // Muka halaman form upload
    public function index()
    {
        return \Illuminate\Support\Facades\View::make('lattas.import');
    }

    // Proses data Master LPK
    public function importLpk(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        Excel::import(new LpkImport(), $request->file('file'));

        return Redirect::back()->with('success', 'Data Master LPK berhasil diimpor!');
    }

    // Proses data Pelatihan LPK
    public function importLpkTraining(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        Excel::import(new LpkTrainingImport(), $request->file('file'));

        return Redirect::back()->with('success', 'Data Pelatihan LPK berhasil diimpor!');
    }

    // Update LPK
    public function updateLpk(Request $request, $id)
    {
        $request->validate([
            'nama_lpk' => 'required|string|max:255',
            'nama_pimpinan' => 'nullable|string|max:255',
            'tahun_berdiri' => 'nullable|string|max:4',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        $lpk = Lpk::findOrFail($id);
        $lpk->update($request->all());

        return Redirect::back()->with('success', 'Data LPK berhasil diperbarui!');
    }

    // Update LpkTraining
    public function updateLpkTraining(Request $request, $id)
    {
        $request->validate([
            'program_pelatihan' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:0',
            'jumlah_paket' => 'required|integer|min:0',
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2000|max:2100',
        ]);

        $training = LpkTraining::findOrFail($id);
        $training->update($request->only('program_pelatihan', 'jumlah_peserta', 'jumlah_paket', 'bulan', 'tahun'));

        return Redirect::back()->with('success', 'Data Pelatihan LPK berhasil diperbarui!');
    }

    // Hapus LPK (akan menghapus semua data LpkTraining yang terkait berkat relasi cascade di DB/Model)
    public function destroyLpk($id)
    {
        $lpk = Lpk::findOrFail($id);
        $lpk->delete();
        return Redirect::back()->with('success', 'Data LPK dan semua riwayat pelatihannya berhasil dihapus!');
    }

    // Hapus LpkTraining
    public function destroyLpkTraining($id)
    {
        $training = LpkTraining::findOrFail($id);
        $training->delete();
        return Redirect::back()->with('success', 'Data Pelatihan LPK berhasil dihapus!');
    }
}
