<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PkwtReport;
use App\Models\PhiReport;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class PhiController extends Controller
{
    // ----- PKWT -----

    public function pkwt(Request $request)
    {
        $query = PkwtReport::latest();
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $pkwts = $query->get();

        return view('phi.pkwt', compact('pkwts'));
    }

    public function storePkwt(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'total_perusahaan' => 'required|integer|min:0',
            'total_pekerja' => 'required|integer|min:0',
            'file' => 'required|file|mimes:pdf,xls,xlsx,doc,docx,csv|max:5120',
        ]);

        $path = $request->file('file')->store('reports/pkwt', 'public');
        $user = User::first(); 

        PkwtReport::create([
            'user_id' => $user ? $user->id : 1,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'total_perusahaan' => $request->total_perusahaan,
            'total_pekerja' => $request->total_pekerja,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Data PKWT berhasil ditambahkan!');
    }

    public function updatePkwt(Request $request, $id)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'total_perusahaan' => 'required|integer|min:0',
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

        return back()->with('success', 'Data PKWT berhasil diperbarui!');
    }

    public function destroyPkwt($id)
    {
        $pkwt = PkwtReport::findOrFail($id);
        if ($pkwt->file_path && Storage::disk('public')->exists($pkwt->file_path)) {
            Storage::disk('public')->delete($pkwt->file_path);
        }
        $pkwt->delete();
        return back()->with('success', 'Data PKWT berhasil dihapus!');
    }

    // ----- PENGADUAN KASUS -----

    public function pengaduan(Request $request)
    {
        $query = PhiReport::latest();
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $pengaduans = $query->get();

        return view('phi.pengaduan', compact('pengaduans'));
    }

    public function storePengaduan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'sisa_bulan_lalu' => 'required|integer|min:0',
            'kasus_masuk' => 'required|integer|min:0',
            'selesai_bipartit' => 'required|integer|min:0',
            'selesai_pb' => 'required|integer|min:0',
            'selesai_anjuran' => 'required|integer|min:0',
            'selesai_lainnya' => 'required|integer|min:0',
            'sisa_kasus_akhir' => 'required|integer|min:0',
            'file' => 'required|file|mimes:pdf,xls,xlsx,doc,docx,csv|max:5120',
        ]);

        $path = $request->file('file')->store('reports/pengaduan', 'public');
        $user = User::first(); 

        PhiReport::create([
            'user_id' => $user ? $user->id : 1,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'sisa_bulan_lalu' => $request->sisa_bulan_lalu,
            'kasus_masuk' => $request->kasus_masuk,
            'selesai_bipartit' => $request->selesai_bipartit,
            'selesai_pb' => $request->selesai_pb,
            'selesai_anjuran' => $request->selesai_anjuran,
            'selesai_lainnya' => $request->selesai_lainnya,
            'sisa_kasus_akhir' => $request->sisa_kasus_akhir,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Data Pengaduan Kasus berhasil ditambahkan!');
    }

    public function updatePengaduan(Request $request, $id)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'sisa_bulan_lalu' => 'required|integer|min:0',
            'kasus_masuk' => 'required|integer|min:0',
            'selesai_bipartit' => 'required|integer|min:0',
            'selesai_pb' => 'required|integer|min:0',
            'selesai_anjuran' => 'required|integer|min:0',
            'selesai_lainnya' => 'required|integer|min:0',
            'sisa_kasus_akhir' => 'required|integer|min:0',
            'file' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx,csv|max:5120',
        ]);

        $pengaduan = PhiReport::findOrFail($id);
        $data = $request->except(['file']);

        if ($request->hasFile('file')) {
            if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
                Storage::disk('public')->delete($pengaduan->file_path);
            }
            $data['file_path'] = $request->file('file')->store('reports/pengaduan', 'public');
        }

        $pengaduan->update($data);

        return back()->with('success', 'Data Pengaduan Kasus berhasil diperbarui!');
    }

    public function destroyPengaduan($id)
    {
        $pengaduan = PhiReport::findOrFail($id);
        if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
            Storage::disk('public')->delete($pengaduan->file_path);
        }
        $pengaduan->delete();
        return back()->with('success', 'Data Pengaduan Kasus berhasil dihapus!');
    }
}
