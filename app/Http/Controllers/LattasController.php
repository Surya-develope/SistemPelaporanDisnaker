<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\LpkImport;
use App\Imports\LpkTrainingImport;
use App\Models\Lpk;
use App\Models\LpkTraining;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LattasController extends Controller
{
    // Data Pelatihan
    public function pelatihan(Request $request)
    {
        $query = LpkTraining::query();

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $trainings = $query->get();
        return View::make('lattas.pelatihan', compact('trainings'));
    }

    public function exportPelatihan(Request $request)
    {
        $query = LpkTraining::query();
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $allData = $query->get();

        $headings = ['No', 'Nama LPK', 'Program Pelatihan', 'Jumlah Peserta', 'Jumlah Paket'];

        $groupedData = $allData->groupBy(function($item) {
            $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $blnStr = ($item->bulan && $item->bulan >= 1 && $item->bulan <= 12) ? $namaBulan[$item->bulan - 1] : 'Unknown';
            $thnStr = $item->tahun ?? 'Unknown';
            return $blnStr . ' ' . $thnStr;
        });

        $sheets = [];
        foreach ($groupedData as $sheetName => $dataChunk) {
            $exportData = [];
            $no = 1;
            foreach ($dataChunk as $row) {
                $namaLpk = $row->nama_lpk ? $row->nama_lpk : '-';
                $exportData[] = [
                    $no++, $namaLpk, $row->program_pelatihan, $row->jumlah_peserta, $row->jumlah_paket
                ];
            }
            $sheets[] = new \App\Exports\GenericDataExport($exportData, $headings, substr($sheetName, 0, 31));
        }

        if (count($sheets) === 0) {
            $sheets[] = new \App\Exports\GenericDataExport([], $headings, 'Data Kosong');
        }

        return Excel::download(new \App\Exports\GenericMultiSheetExport($sheets), 'program_pelatihan_' . date('YmdHis') . '.xlsx');
    }

    // Rekap LPK Aktif
    public function lpkAktif(Request $request)
    {
        $query = Lpk::where('status', 'aktif');

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $lpks = $query->get();
        return View::make('lattas.lpk_aktif', compact('lpks'));
    }

    public function exportLpkAktif(Request $request)
    {
        $query = Lpk::where('status', 'aktif');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $allData = $query->get();

        $headings = ['No', 'Nama LPK', 'Pimpinan', 'Tahun Berdiri', 'Alamat', 'Status'];

        $groupedData = $allData->groupBy(function($item) {
            $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $blnStr = ($item->bulan && $item->bulan >= 1 && $item->bulan <= 12) ? $namaBulan[$item->bulan - 1] : 'Unknown';
            $thnStr = $item->tahun ?? 'Unknown';
            return $blnStr . ' ' . $thnStr;
        });

        $sheets = [];
        foreach ($groupedData as $sheetName => $dataChunk) {
            $exportData = [];
            $no = 1;
            foreach ($dataChunk as $row) {
                $exportData[] = [
                    $no++, $row->nama_lpk, $row->nama_pimpinan, $row->tahun_berdiri, $row->alamat, $row->status
                ];
            }
            $sheets[] = new \App\Exports\GenericDataExport($exportData, $headings, substr($sheetName, 0, 31));
        }

        if (count($sheets) === 0) {
            $sheets[] = new \App\Exports\GenericDataExport([], $headings, 'Data Kosong');
        }

        return Excel::download(new \App\Exports\GenericMultiSheetExport($sheets), 'lpk_aktif_' . date('YmdHis') . '.xlsx');
    }

    // Rekap LPK Non Aktif
    public function lpkNonaktif(Request $request)
    {
        $query = Lpk::where('status', 'tidak aktif');

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $lpks = $query->get();
        return View::make('lattas.lpk_nonaktif', compact('lpks'));
    }

    public function exportLpkNonaktif(Request $request)
    {
        $query = Lpk::where('status', 'tidak aktif');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $allData = $query->get();

        $headings = ['No', 'Nama LPK', 'Pimpinan', 'Tahun Berdiri', 'Alamat', 'Status'];

        $groupedData = $allData->groupBy(function($item) {
            $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $blnStr = ($item->bulan && $item->bulan >= 1 && $item->bulan <= 12) ? $namaBulan[$item->bulan - 1] : 'Unknown';
            $thnStr = $item->tahun ?? 'Unknown';
            return $blnStr . ' ' . $thnStr;
        });

        $sheets = [];
        foreach ($groupedData as $sheetName => $dataChunk) {
            $exportData = [];
            $no = 1;
            foreach ($dataChunk as $row) {
                $exportData[] = [
                    $no++, $row->nama_lpk, $row->nama_pimpinan, $row->tahun_berdiri, $row->alamat, $row->status
                ];
            }
            $sheets[] = new \App\Exports\GenericDataExport($exportData, $headings, substr($sheetName, 0, 31));
        }

        if (count($sheets) === 0) {
            $sheets[] = new \App\Exports\GenericDataExport([], $headings, 'Data Kosong');
        }

        return Excel::download(new \App\Exports\GenericMultiSheetExport($sheets), 'lpk_tidak_aktif_' . date('YmdHis') . '.xlsx');
    }


    public function templateLpk()
    {
        $headings = ['Nama LPK', 'Nama Pimpinan', 'Tahun Berdiri', 'Alamat', 'Status'];
        return Excel::download(new \App\Exports\GenericDataExport([], $headings), 'template_master_lpk.xlsx');
    }

    public function templatePelatihan()
    {
        $headings = ['Nama LPK', 'Program Pelatihan', 'Jumlah Peserta', 'Jumlah Paket'];
        return Excel::download(new \App\Exports\GenericDataExport([], $headings), 'template_training_lpk.xlsx');
    }

    // Proses data Master LPK
    public function importLpk(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        try {
            Excel::import(new LpkImport($request->bulan, $request->tahun), $request->file('file'));
            return Redirect::back()->with('success', 'Data Master LPK berhasil diimpor!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    // Proses data Pelatihan LPK
    public function importLpkTraining(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        try {
            Excel::import(new LpkTrainingImport($request->bulan, $request->tahun), $request->file('file'));
            return Redirect::back()->with('success', 'Data Pelatihan LPK berhasil diimpor!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    // Update LPK
    public function updateLpk(Request $request, $id)
    {
        $request->validate([
            'nama_lpk' => 'required|string|max:255',
            'nama_pimpinan' => 'nullable|string|max:255',
            'tahun_berdiri' => 'nullable|string|max:4',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        $lpk = Lpk::findOrFail($id);
        $lpk->update($request->all());

        return Redirect::back()->with('success', 'Data LPK berhasil diperbarui!');
    }

    public function updateLpkTraining(Request $request, $id)
    {
        $request->validate([
            'nama_lpk' => 'required|string|max:255',
            'program_pelatihan' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:0',
            'jumlah_paket' => 'required|integer|min:0',
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2000|max:2100',
        ]);

        $training = LpkTraining::findOrFail($id);
        $training->update($request->only('nama_lpk', 'program_pelatihan', 'jumlah_peserta', 'jumlah_paket', 'bulan', 'tahun'));

        return Redirect::back()->with('success', 'Data Pelatihan LPK berhasil diperbarui!');
    }

    // Hapus LPK (akan menghapus semua data LpkTraining yang terkait berkat relasi cascade di DB/Model)
    public function destroyLpk($id)
    {
        $lpk = Lpk::findOrFail($id);
        $lpk->delete();
        return Redirect::back()->with('success', 'Data LPK dan semua riwayat pelatihannya berhasil dihapus!');
    }

    // Hapus LpkTraining
    public function destroyLpkTraining($id)
    {
        $training = LpkTraining::findOrFail($id);
        $training->delete();
        return Redirect::back()->with('success', 'Data Pelatihan LPK berhasil dihapus!');
    }

    public function storeLpk(Request $request)
    {
        $request->validate([
            'nama_lpk' => 'required|string|max:255',
            'nama_pimpinan' => 'nullable|string|max:255',
            'tahun_berdiri' => 'nullable|string|max:4',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        $data = $request->all();
        $data['bulan'] = $request->bulan ?? date('n');
        $data['tahun'] = $request->tahun ?? date('Y');

        Lpk::create($data);

        return Redirect::back()->with('success', 'Data LPK berhasil ditambahkan!');
    }

    public function storeLpkTraining(Request $request)
    {
        $request->validate([
            'nama_lpk' => 'required|string|max:255',
            'program_pelatihan' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:0',
            'jumlah_paket' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['bulan'] = $request->bulan ?? date('n');
        $data['tahun'] = $request->tahun ?? date('Y');

        LpkTraining::create($data);

        return Redirect::back()->with('success', 'Data Pelatihan LPK berhasil ditambahkan!');
    }

    public function bulkDeleteLpk(Request $request)
    {
        $ids = json_decode($request->ids, true);
        if (is_array($ids)) {
            Lpk::whereIn('id', $ids)->delete();
        }
        return Redirect::back()->with('success', count($ids) . ' Data LPK beserta riwayat pelatihannya berhasil dihapus!');
    }

    public function bulkDeleteTraining(Request $request)
    {
        $ids = json_decode($request->ids, true);
        if (is_array($ids)) {
            LpkTraining::whereIn('id', $ids)->delete();
        }
        return Redirect::back()->with('success', count($ids) . ' Data Pelatihan LPK berhasil dihapus!');
    }
}
