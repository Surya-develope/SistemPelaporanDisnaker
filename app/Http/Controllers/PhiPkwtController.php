<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PkwtReport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PkwtTemplateExport;
use App\Exports\PkwtDataExport;
use App\Imports\PkwtImport;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class PhiPkwtController extends Controller
{
    public function index(Request $request)
    {
        $query = PkwtReport::latest();
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $pkwts = $query->get();

        return View::make('phi.pkwt', compact('pkwts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'no_pencatatan' => 'nullable|string',
            'nama_perusahaan' => 'nullable|string',
            'total_pekerja' => 'required|integer|min:0',
            'file' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx,csv|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('reports/pkwt', 'public');
        }

        PkwtReport::create([
            'user_id' => $request->session()->get('user_id') ?? 1,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'no_pencatatan' => $request->no_pencatatan,
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_pimpinan' => $request->alamat_pimpinan,
            'nama_pekerja' => $request->nama_pekerja,
            'total_pekerja' => $request->total_pekerja,
            'jabatan' => $request->jabatan,
            'masa_kontrak' => $request->masa_kontrak,
            'keterangan' => $request->keterangan,
            'file_path' => $path,
        ]);

        return Redirect::back()->with('success', 'Data PKWT berhasil ditambahkan!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file' => 'required|file|mimes:xls,xlsx,csv|max:5120',
        ]);

        Excel::import(
            new PkwtImport($request->bulan, $request->tahun),
            $request->file('file')
        );

        return Redirect::back()->with('success', 'Data PKWT berhasil diimpor dari Excel!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new PkwtTemplateExport, 'template_pkwt_phi.xlsx');
    }

    public function export(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $filename = 'rekap_pkwt_';
        $filename .= $bulan ? $bulan . '_' : '';
        $filename .= $tahun ? $tahun : date('Y');
        $filename .= '.xlsx';

        return Excel::download(new PkwtDataExport($bulan, $tahun), $filename);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'total_pekerja' => 'required|integer|min:0',
            'file' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx,csv|max:5120',
        ]);

        $pkwt = PkwtReport::findOrFail($id);
        $data = $request->except(['file']);

        if ($request->hasFile('file')) {
            if ($pkwt->file_path && Storage::disk('public')->exists($pkwt->file_path)) {
                Storage::disk('public')->delete($pkwt->file_path);
            }
            $data['file_path'] = $request->file('file')->store('reports/pkwt', 'public');
        }

        $pkwt->update($data);

        return Redirect::back()->with('success', 'Data PKWT berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pkwt = PkwtReport::findOrFail($id);
        if ($pkwt->file_path && Storage::disk('public')->exists($pkwt->file_path)) {
            Storage::disk('public')->delete($pkwt->file_path);
        }
        $pkwt->delete();
        return Redirect::back()->with('success', 'Data PKWT berhasil dihapus!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->ids, true);
        if (is_array($ids)) {
            $pkwts = PkwtReport::whereIn('id', $ids)->get();
            foreach ($pkwts as $pkwt) {
                /** @var \App\Models\PkwtReport $pkwt */
                if ($pkwt->file_path && Storage::disk('public')->exists($pkwt->file_path)) {
                    Storage::disk('public')->delete($pkwt->file_path);
                }
                $pkwt->delete();
            }
        }
        return Redirect::back()->with('success', count($ids) . ' Data PKWT berhasil dihapus!');
    }
}
