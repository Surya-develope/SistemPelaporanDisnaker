<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PkwtReport;
use Illuminate\Support\Facades\Storage;

class PkwtReportController extends Controller
{
    public function index()
    {
        $reports = PkwtReport::with('user')->latest()->get();
        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
            'total_perusahaan' => 'required|integer',
            'total_pekerja' => 'required|integer',
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        // Simpan File
        $path = $request->file('file_excel')->store('reports/pkwt', 'public');

        $report = PkwtReport::create([
            'user_id' => $request->user()->id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'total_perusahaan' => $request->total_perusahaan,
            'total_pekerja' => $request->total_pekerja,
            'file_path' => $path,
        ]);

        return response()->json([
            'message' => 'Laporan PKWT berhasil disimpan',
            'data' => $report
        ], 201);
    }

    public function download($id)
    {
        $report = PkwtReport::findOrFail($id);
        
        if (!Storage::disk('public')->exists($report->file_path)) {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }

        return Storage::disk('public')->download($report->file_path);
    }
}
