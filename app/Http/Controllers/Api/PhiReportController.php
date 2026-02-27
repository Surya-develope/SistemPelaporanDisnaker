<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PhiReport;
use App\Models\PkwtReport;
use Illuminate\Support\Facades\Storage;

class PhiReportController extends Controller
{
    public function index()
    {
        $reports = PhiReport::with('user')->latest()->get();
        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
            'sisa_bulan_lalu' => 'required|integer',
            'kasus_masuk' => 'required|integer',
            'selesai_bipartit' => 'required|integer',
            'selesai_pb' => 'required|integer',
            'selesai_anjuran' => 'required|integer',
            'selesai_lainnya' => 'required|integer',
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        // Hitung sisa kasus akhir otomatis
        $total_selesai = $request->selesai_bipartit + $request->selesai_pb + $request->selesai_anjuran + $request->selesai_lainnya;
        $sisa_kasus_akhir = ($request->sisa_bulan_lalu + $request->kasus_masuk) - $total_selesai;

        // Simpan File
        $path = $request->file('file_excel')->store('reports/phi', 'public');

        $report = PhiReport::create([
            'user_id' => $request->user()->id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'sisa_bulan_lalu' => $request->sisa_bulan_lalu,
            'kasus_masuk' => $request->kasus_masuk,
            'selesai_bipartit' => $request->selesai_bipartit,
            'selesai_pb' => $request->selesai_pb,
            'selesai_anjuran' => $request->selesai_anjuran,
            'selesai_lainnya' => $request->selesai_lainnya,
            'sisa_kasus_akhir' => $sisa_kasus_akhir,
            'file_path' => $path,
        ]);

        return response()->json([
            'message' => 'Laporan PHI berhasil disimpan',
            'data' => $report
        ], 201);
    }

    public function download($id)
    {
        $report = PhiReport::findOrFail($id);
        
        if (!Storage::disk('public')->exists($report->file_path)) {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }

        return Storage::disk('public')->download($report->file_path);
    }
}
