<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PkwtReport;
use App\Models\PhiReport;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PkwtTemplateExport;
use App\Exports\PkwtDataExport;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class PhiController extends Controller
{
    // ----- PKWT -----

    public function pkwt(Request $request)
    {
        $query = PkwtReport::latest();
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $pkwts = $query->get();

        return \Illuminate\Support\Facades\View::make('phi.pkwt', compact('pkwts'));
    }

    public function storePkwt(Request $request)
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

    public function importPkwt(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file' => 'required|file|mimes:xls,xlsx,csv|max:5120',
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(
            new \App\Imports\PkwtImport($request->bulan, $request->tahun),
            $request->file('file')
        );

        return Redirect::back()->with('success', 'Data PKWT berhasil diimpor dari Excel!');
    }

    public function downloadPkwtTemplate()
    {
        return Excel::download(new PkwtTemplateExport, 'template_pkwt_phi.xlsx');
    }

    public function exportPkwt(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $filename = 'rekap_pkwt_';
        $filename .= $bulan ? $bulan . '_' : '';
        $filename .= $tahun ? $tahun : date('Y');
        $filename .= '.xlsx';

        return Excel::download(new PkwtDataExport($bulan, $tahun), $filename);
    }

    public function updatePkwt(Request $request, $id)
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

    public function destroyPkwt($id)
    {
        $pkwt = PkwtReport::findOrFail($id);
        if ($pkwt->file_path && Storage::disk('public')->exists($pkwt->file_path)) {
            Storage::disk('public')->delete($pkwt->file_path);
        }
        $pkwt->delete();
        return Redirect::back()->with('success', 'Data PKWT berhasil dihapus!');
    }

    // ----- PENGADUAN KASUS -----

    public function pengaduan(Request $request)
    {
        $query = PhiReport::latest();
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $pengaduans = $query->get();

        return View::make('phi.pengaduan', compact('pengaduans'));
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
            'file' => 'required|file|mimes:pdf,xls,xlsx,doc,docx,csv|max:5120',
        ]);

        $path = $request->file('file')->store('reports/pengaduan', 'public');

        // Kalkulasi otomatis Sisa Kasus Akhir: (Sisa Lalu + Masuk) - Total Selesai
        $totalSelesai = $request->selesai_bipartit + $request->selesai_pb + $request->selesai_anjuran + $request->selesai_lainnya;
        $sisaKasusAkhir = ($request->sisa_bulan_lalu + $request->kasus_masuk) - $totalSelesai;

        PhiReport::create([
            'user_id' => $request->session()->get('user_id'),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'sisa_bulan_lalu' => $request->sisa_bulan_lalu,
            'kasus_masuk' => $request->kasus_masuk,
            'selesai_bipartit' => $request->selesai_bipartit,
            'selesai_pb' => $request->selesai_pb,
            'selesai_anjuran' => $request->selesai_anjuran,
            'selesai_lainnya' => $request->selesai_lainnya,
            'sisa_kasus_akhir' => $sisaKasusAkhir,
            'file_path' => $path,
        ]);

        return Redirect::back()->with('success', 'Data Pengaduan Kasus berhasil ditambahkan!');
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
            'file' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx,csv|max:5120',
        ]);

        $pengaduan = PhiReport::findOrFail($id);
        $data = $request->except(['file', 'sisa_kasus_akhir']);

        if ($request->hasFile('file')) {
            if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
                Storage::disk('public')->delete($pengaduan->file_path);
            }
            $data['file_path'] = $request->file('file')->store('reports/pengaduan', 'public');
        }

        // Kalkulasi ulang otomatis jika ada update data angka
        $totalSelesai = $request->selesai_bipartit + $request->selesai_pb + $request->selesai_anjuran + $request->selesai_lainnya;
        $data['sisa_kasus_akhir'] = ($request->sisa_bulan_lalu + $request->kasus_masuk) - $totalSelesai;

        $pengaduan->update($data);

        return Redirect::back()->with('success', 'Data Pengaduan Kasus berhasil diperbarui!');
    }

    public function destroyPengaduan($id)
    {
        $pengaduan = PhiReport::findOrFail($id);
        if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
            Storage::disk('public')->delete($pengaduan->file_path);
        }
        $pengaduan->delete();
        return Redirect::back()->with('success', 'Data Pengaduan Kasus berhasil dihapus!');
    }
}
