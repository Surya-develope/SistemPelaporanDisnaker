<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhiReport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericDataExport;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class PhiPengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = PhiReport::latest();
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $pengaduans = $query->get();

        return View::make('phi.pengaduan', compact('pengaduans'));
    }

    public function export(Request $request)
    {
        $query = PhiReport::latest();
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, $row->bulan, $row->tahun, $row->sisa_bulan_lalu, $row->kasus_masuk, $row->selesai_bipartit, $row->selesai_pb, $row->selesai_anjuran, $row->selesai_lainnya, $row->sisa_kasus_akhir, $row->created_at
            ];
        }

        $headings = ['No', 'Bulan', 'Tahun', 'Sisa Bulan Lalu', 'Kasus Masuk', 'Selesai Bipartit', 'Selesai PB', 'Selesai Anjuran', 'Selesai Lainnya', 'Sisa Kasus Akhir', 'Tanggal Dibuat'];
        return Excel::download(new GenericDataExport($exportData, $headings), 'pengaduan_kasus_phi.xlsx');
    }

    public function store(Request $request)
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

    public function update(Request $request, $id)
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

    public function destroy($id)
    {
        $pengaduan = PhiReport::findOrFail($id);
        if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
            Storage::disk('public')->delete($pengaduan->file_path);
        }
        $pengaduan->delete();
        return Redirect::back()->with('success', 'Data Pengaduan Kasus berhasil dihapus!');
    }
}
