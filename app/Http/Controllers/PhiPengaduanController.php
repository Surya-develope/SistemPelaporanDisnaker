<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhiReport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericDataExport;
use App\Imports\PhiPengaduanImport;
use App\Exports\PhiPengaduanTemplateExport;
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
        if ($request->filled('status_kasus'))
            $query->where('status_kasus', $request->status_kasus);
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
        if ($request->filled('status_kasus'))
            $query->where('status_kasus', $request->status_kasus);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, 
                $row->nomor_agenda ?? '-',
                $row->tanggal_diterima ? \Carbon\Carbon::parse($row->tanggal_diterima)->format('d/m/Y') : '-',
                $row->nama_perusahaan,
                $row->sektor ?? '-',
                $row->nama_pekerja ?? '-',
                $row->jml_org ?? 0,
                $row->jenis_perselisihan ?? '-',
                $row->mediator ?? '-',
                $row->metode_penyelesaian ?? '-',
                $row->tanggal_diselesaikan ? \Carbon\Carbon::parse($row->tanggal_diselesaikan)->format('d/m/Y') : '-'
            ];
        }

        $headings = [
            'NO', 
            'NOMOR AGENDA',
            'TANGGAL KASUS DITERIMA',
            'NAMA PERUSAHAAN', 
            'SEKTOR', 
            'NAMA PEKERJA', 
            'JML ORG', 
            'JENIS PERSELISIHAN',
            'MEDIATOR', 
            'PENYELESAIAN KASUS', 
            'TANGGAL KASUS DISELESAIKAN'
        ];
        return Excel::download(new GenericDataExport($exportData, $headings), 'laporan_pengaduan_kasus_phi.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'sektor' => 'nullable|string|max:255',
            'nama_pekerja' => 'nullable|string|max:255',
            'jml_org' => 'nullable|integer|min:1',
            'mediator' => 'nullable|string|max:255',
            'jenis_perselisihan' => 'nullable|string|max:255',
            'nomor_agenda' => 'nullable|string|max:255',
            'tanggal_diterima' => 'nullable|date',
            'tanggal_diselesaikan' => 'nullable|date',
            'status_kasus' => 'required|in:berjalan,selesai',
            'metode_penyelesaian' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->hasFile('file') ? $request->file('file')->store('reports/pengaduan', 'public') : null;

        // Auto-extract month and year from tanggal_diterima if available, otherwise current
        $bulan = $request->tanggal_diterima ? (int) date('m', strtotime($request->tanggal_diterima)) : (int) date('m');
        $tahun = $request->tanggal_diterima ? (int) date('Y', strtotime($request->tanggal_diterima)) : (int) date('Y');

        PhiReport::create([
            'user_id' => auth()->id() ?? 1,
            'nama_perusahaan' => $request->nama_perusahaan,
            'sektor' => $request->sektor,
            'nama_pekerja' => $request->nama_pekerja,
            'jml_org' => $request->jml_org,
            'mediator' => $request->mediator,
            'jenis_perselisihan' => $request->jenis_perselisihan,
            'nomor_agenda' => $request->nomor_agenda,
            'tanggal_diterima' => $request->tanggal_diterima,
            'tanggal_diselesaikan' => $request->status_kasus === 'selesai' ? $request->tanggal_diselesaikan : null,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'status_kasus' => $request->status_kasus,
            'metode_penyelesaian' => $request->metode_penyelesaian,
            'file_path' => $path,
        ]);

        return Redirect::back()->with('success', 'Data Kasus berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'sektor' => 'nullable|string|max:255',
            'nama_pekerja' => 'nullable|string|max:255',
            'jml_org' => 'nullable|integer|min:1',
            'mediator' => 'nullable|string|max:255',
            'jenis_perselisihan' => 'nullable|string|max:255',
            'nomor_agenda' => 'nullable|string|max:255',
            'tanggal_diterima' => 'nullable|date',
            'tanggal_diselesaikan' => 'nullable|date',
            'status_kasus' => 'required|in:berjalan,selesai',
            'metode_penyelesaian' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $pengaduan = PhiReport::findOrFail($id);
        
        $bulan = $request->tanggal_diterima ? (int) date('m', strtotime($request->tanggal_diterima)) : $pengaduan->bulan;
        $tahun = $request->tanggal_diterima ? (int) date('Y', strtotime($request->tanggal_diterima)) : $pengaduan->tahun;

        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'sektor' => $request->sektor,
            'nama_pekerja' => $request->nama_pekerja,
            'jml_org' => $request->jml_org,
            'mediator' => $request->mediator,
            'jenis_perselisihan' => $request->jenis_perselisihan,
            'nomor_agenda' => $request->nomor_agenda,
            'tanggal_diterima' => $request->tanggal_diterima,
            'tanggal_diselesaikan' => $request->status_kasus === 'selesai' ? $request->tanggal_diselesaikan : null,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'status_kasus' => $request->status_kasus,
            'metode_penyelesaian' => $request->metode_penyelesaian,
        ];

        if ($request->hasFile('file')) {
            if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
                Storage::disk('public')->delete($pengaduan->file_path);
            }
            $data['file_path'] = $request->file('file')->store('reports/pengaduan', 'public');
        }

        $pengaduan->update($data);

        return Redirect::back()->with('success', 'Data Kasus berhasil diperbarui!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file' => 'required|file|mimes:xls,xlsx,csv|max:5120',
        ]);

        Excel::import(
            new PhiPengaduanImport($request->bulan, $request->tahun),
            $request->file('file')
        );

        return Redirect::back()->with('success', 'Data Kasus berhasil diimpor dari Excel!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new PhiPengaduanTemplateExport, 'template_pengaduan_kasus.xlsx');
    }

    public function destroy($id)
    {
        $pengaduan = PhiReport::findOrFail($id);
        if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
            Storage::disk('public')->delete($pengaduan->file_path);
        }
        $pengaduan->delete();
        return Redirect::back()->with('success', 'Data Kasus berhasil dihapus!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->ids, true);
        if (is_array($ids)) {
            $pengaduans = PhiReport::whereIn('id', $ids)->get();
            foreach ($pengaduans as $pengaduan) {
                if ($pengaduan->file_path && Storage::disk('public')->exists($pengaduan->file_path)) {
                    Storage::disk('public')->delete($pengaduan->file_path);
                }
                $pengaduan->delete();
            }
        }
        return Redirect::back()->with('success', count($ids) . ' Data Kasus berhasil dihapus!');
    }
}
