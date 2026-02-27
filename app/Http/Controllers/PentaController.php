<?php

namespace App\Http\Controllers;

use App\Models\LowonganKerja;
use App\Models\PencariKerja;
use App\Models\Penempatan;
use App\Imports\LowonganKerjaImport;
use App\Imports\PencariKerjaImport;
use App\Imports\PenempatanImport;
use Maatwebsite\Excel\Facades\Excel;

class PentaController extends Controller
{
    public function lowongan()
    {
        $lowongans = LowonganKerja::latest('tanggal_posting')->get();
        return view('penta.lowongan', compact('lowongans'));
    }

    public function tenagaKerja()
    {
        $pencaris = PencariKerja::latest('tanggal_daftar')->get();
        return view('penta.tenaga_kerja', compact('pencaris'));
    }

    public function rekap()
    {
        $penempatans = Penempatan::latest('tanggal_diterima')->get();
        return view('penta.rekap', compact('penempatans'));
    }

    public function importIndex()
    {
        return view('penta.import');
    }

    public function importLowongan(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xls,xlsx,csv']);
        Excel::import(new LowonganKerjaImport, $request->file('file'));
        return back()->with('success', 'Data Lowongan Kerja berhasil diimpor!');
    }

    public function importPencari(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xls,xlsx,csv']);
        Excel::import(new PencariKerjaImport, $request->file('file'));
        return back()->with('success', 'Data Pencari Kerja Aktif berhasil diimpor!');
    }

    public function importPenempatan(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xls,xlsx,csv']);
        Excel::import(new PenempatanImport, $request->file('file'));
        return back()->with('success', 'Data Penempatan berhasil diimpor!');
    }
}
