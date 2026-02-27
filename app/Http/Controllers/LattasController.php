<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\LpkImport;
use App\Imports\LpkTrainingImport;
use Maatwebsite\Excel\Facades\Excel;

class LattasController extends Controller
{
    // Muka halaman form upload
    public function index()
    {
        return view('lattas.import');
    }

    // Proses data Master LPK
    public function importLpk(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        Excel::import(new LpkImport, $request->file('file'));

        return back()->with('success', 'Data Master LPK berhasil diimpor!');
    }

    // Proses data Pelatihan LPK
    public function importLpkTraining(Request $request)
    {
        $request->validate([
            'file'  => 'required|mimes:xls,xlsx,csv',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
        ]);

        Excel::import(new LpkTrainingImport($request->bulan, $request->tahun), $request->file('file'));

        return back()->with('success', 'Data Pelatihan LPK berhasil diimpor!');
    }
}
