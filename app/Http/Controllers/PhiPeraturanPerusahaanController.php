<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhiPeraturanPerusahaan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeraturanPerusahaanTemplateExport;
use App\Exports\GenericDataExport;
use App\Imports\PeraturanPerusahaanImport;
use Carbon\Carbon;

class PhiPeraturanPerusahaanController extends Controller
{
    public function index(Request $request)
    {
        $query = PhiPeraturanPerusahaan::latest();

        if ($request->filled('keyword')) {
            $query->where('nama_perusahaan', 'like', '%' . $request->keyword . '%');
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $peraturans = $query->get();
        return View::make('phi.peraturan', compact('peraturans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'nullable|string',
            'nama_pimpinan' => 'nullable|string|max:255',
            'sektor_usaha' => 'nullable|string|max:255',
            'pekerja_lk' => 'nullable|integer|min:0',
            'pekerja_pr' => 'nullable|integer|min:0',
            'status_pp' => 'required|in:Baru,Perpanjangan',
            'no_sk' => 'nullable|string|max:255',
            'pp_ke' => 'nullable|integer|min:1',
            'masa_berlaku_awal' => 'nullable|date',
            'masa_berlaku_akhir' => 'nullable|date',
            'keterangan' => 'nullable|string'
        ]);

        $data = $request->all();
        // Set default values if null
        $data['pekerja_lk'] = $data['pekerja_lk'] ?? 0;
        $data['pekerja_pr'] = $data['pekerja_pr'] ?? 0;

        PhiPeraturanPerusahaan::create($data);

        return Redirect::back()->with('success', 'Data Peraturan Perusahaan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'nullable|string',
            'nama_pimpinan' => 'nullable|string|max:255',
            'sektor_usaha' => 'nullable|string|max:255',
            'pekerja_lk' => 'nullable|integer|min:0',
            'pekerja_pr' => 'nullable|integer|min:0',
            'status_pp' => 'required|in:Baru,Perpanjangan',
            'no_sk' => 'nullable|string|max:255',
            'pp_ke' => 'nullable|integer|min:1',
            'masa_berlaku_awal' => 'nullable|date',
            'masa_berlaku_akhir' => 'nullable|date',
            'keterangan' => 'nullable|string'
        ]);

        $peraturan = PhiPeraturanPerusahaan::findOrFail($id);

        $data = $request->all();
        $data['pekerja_lk'] = $data['pekerja_lk'] ?? 0;
        $data['pekerja_pr'] = $data['pekerja_pr'] ?? 0;

        $peraturan->update($data);

        return Redirect::back()->with('success', 'Data Peraturan Perusahaan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $peraturan = PhiPeraturanPerusahaan::findOrFail($id);
        $peraturan->delete();

        return Redirect::back()->with('success', 'Data Peraturan Perusahaan berhasil dihapus!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new PeraturanPerusahaanTemplateExport(), 'Template_Impor_Peraturan_Perusahaan.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            Excel::import(new PeraturanPerusahaanImport($request->bulan, $request->tahun), $request->file('file'));
            return Redirect::back()->with('success', 'Data Peraturan Perusahaan berhasil diimpor!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $query = PhiPeraturanPerusahaan::latest();

        if ($request->filled('keyword')) {
            $query->where('nama_perusahaan', 'like', '%' . $request->keyword . '%');
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $data = $query->get();

        $exportData = [];
        $no = 1;

        foreach ($data as $p) {
            $masa_berlaku_awal = $p->masa_berlaku_awal ?Carbon::parse($p->masa_berlaku_awal)->format('d-m-Y') : '-';
            $masa_berlaku_akhir = $p->masa_berlaku_akhir ?Carbon::parse($p->masa_berlaku_akhir)->format('d-m-Y') : '-';
            $periode = $masa_berlaku_awal . ' s/d ' . $masa_berlaku_akhir;

            $exportData[] = [
                $no++,
                $p->bulan,
                $p->tahun,
                $p->nama_perusahaan,
                $p->sektor_usaha,
                $p->pekerja_lk,
                $p->pekerja_pr,
                $p->total_pekerja,
                $p->status_pp,
                $p->no_sk,
                $p->pp_ke,
                $periode,
                $p->keterangan
            ];
        }

        $headings = [
            'No', 'Bulan Pencatatan', 'Tahun Pencatatan', 'Nama Perusahaan', 'Sektor Usaha',
            'Pekerja Laki Laki', 'Pekerja Perempuan', 'Total Pekerja',
            'Status PP', 'No SK PP', 'PP Ke', 'Masa Berlaku', 'Keterangan Tambahan'
        ];

        return Excel::download(new GenericDataExport($exportData, $headings), 'Laporan_Peraturan_Perusahaan.xlsx');
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->ids, true);
        if (is_array($ids)) {
            PhiPeraturanPerusahaan::whereIn('id', $ids)->delete();
        }
        return Redirect::back()->with('success', count($ids) . ' Data Peraturan Perusahaan berhasil dihapus!');
    }
}
