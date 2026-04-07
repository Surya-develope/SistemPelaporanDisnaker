<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LowonganKerja;
use App\Models\PencariKerja;
use App\Models\Penempatan;
use App\Imports\LowonganKerjaImport;
use App\Imports\PencariKerjaImport;
use App\Imports\PenempatanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PentaController extends Controller
{
    public function lowongan(Request $request)
    {
        $query = LowonganKerja::latest('tanggal_posting');
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $lowongans = $query->get();
        return View::make('penta.lowongan', compact('lowongans'));
    }

    public function exportLowongan(Request $request)
    {
        $query = LowonganKerja::latest('tanggal_posting');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, 
                $row->judul_lowongan,
                $row->deskripsi_pekerjaan,
                $row->perusahaan,
                $row->kategori_pekerjaan,
                $row->tipe_pekerjaan,
                $row->sektor_pekerjaan,
                $row->fungsi_pekerjaan,
                $row->kode_kbji,
                $row->minimal_pendidikan,
                $row->keahlian_diperlukan,
                $row->kebutuhan_disabilitas,
                $row->kuota,
                $row->kuota_sisa,
                $row->status_lowongan,
                $row->tanggal_posting ? \Carbon\Carbon::parse($row->tanggal_posting)->format('Y-m-d') : null,
                $row->tanggal_kadaluwarsa ? \Carbon\Carbon::parse($row->tanggal_kadaluwarsa)->format('Y-m-d H:i:s') : null,
            ];
        }

        $headings = ['NO', 'JUDUL LOWONGAN', 'DESKRIPSI PEKERJAAN', 'PERUSAHAAN', 'KATEGORI PEKERJAAN', 'TIPE PEKERJAAN', 'SEKTOR PEKERJAAN', 'FUNGSI PEKERJAAN', 'KODE KBJI', 'MINIMAL PENDIDIKAN', 'KEAHLIAN DIPERLUKAN', 'KEBUTUHAN DISABILITAS', 'KUOTA', 'KUOTA SISA', 'STATUS LOWONGAN', 'TANGGAL POSTING', 'TANGGAL KADALUWARSA'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GenericDataExport($exportData, $headings), 'lowongan_kerja.xlsx');
    }

    public function tenagaKerja(Request $request)
    {
        $query = PencariKerja::latest('tanggal_daftar');
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $pencaris = $query->get();
        return View::make('penta.tenaga_kerja', compact('pencaris'));
    }

    public function exportTenagaKerja(Request $request)
    {
        $query = PencariKerja::latest('tanggal_daftar');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, 
                $row->nik, 
                $row->nama, 
                $row->email, 
                $row->no_hp, 
                $row->tempat_tanggal_lahir, 
                $row->alamat_domisili, 
                $row->domisili, 
                $row->jenis_kelamin, 
                $row->kondisi_fisik, 
                $row->pendidikan_terakhir, 
                $row->jurusan, 
                $row->tanggal_daftar ? \Carbon\Carbon::parse($row->tanggal_daftar)->format('Y-m-d') : null, 
                $row->status_verifikasi
            ];
        }

        $headings = ['NO', 'NIK', 'NAMA', 'EMAIL', 'NO HP', 'TEMPAT TANGGAL LAHIR', 'ALAMAT DOMISILI', 'DOMISILI', 'JENIS KELAMIN', 'KONDISI FISIK', 'PENDIDIKAN TERAKHIR', 'JURUSAN', 'TANGGAL DAFTAR', 'STATUS VERIFIKASI'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GenericDataExport($exportData, $headings), 'pencari_kerja.xlsx');
    }

    public function rekap(Request $request)
    {
        $query = Penempatan::latest('tanggal_diterima');
        if ($request->filled('bulan'))
            $query->where('bulan', $request->bulan);
        if ($request->filled('tahun'))
            $query->where('tahun', $request->tahun);
        $penempatans = $query->get();
        return View::make('penta.rekap', compact('penempatans'));
    }

    public function exportRekap(Request $request)
    {
        $query = Penempatan::latest('tanggal_diterima');
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        $data = $query->get();

        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                $no++, 
                $row->nama, 
                $row->email, 
                $row->judul_lowongan, 
                $row->kode_kbji, 
                $row->nama_perusahaan, 
                $row->pendidikan_terakhir_pelamar, 
                $row->pendidikan_minimal_loker, 
                $row->domisili_pelamar, 
                $row->domisili_lowongan, 
                $row->tanggal_melamar ? \Carbon\Carbon::parse($row->tanggal_melamar)->format('Y-m-d') : null, 
                $row->tanggal_diterima ? \Carbon\Carbon::parse($row->tanggal_diterima)->format('Y-m-d') : null
            ];
        }

        $headings = ['NO', 'NAMA', 'EMAIL', 'JUDUL LOWONGAN', 'KODE KBJI', 'NAMA PERUSAHAAN', 'PENDIDIKAN TERAKHIR PELAMAR', 'PENDIDIKAN MINIMAL LOKER', 'DOMISILI PELAMAR', 'DOMISILI LOWONGAN', 'TANGGAL MELAMAR', 'TANGGAL DITERIMA'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GenericDataExport($exportData, $headings), 'penempatan_kerja.xlsx');
    }


    public function templateLowongan()
    {
        $headings = ['JUDUL LOWONGAN', 'DESKRIPSI PEKERJAAN', 'PERUSAHAAN', 'KATEGORI PEKERJAAN', 'TIPE PEKERJAAN', 'SEKTOR PEKERJAAN', 'FUNGSI PEKERJAAN', 'KODE KBJI', 'MINIMAL PENDIDIKAN', 'KEAHLIAN DIPERLUKAN', 'KEBUTUHAN DISABILITAS', 'KUOTA', 'KUOTA SISA', 'STATUS LOWONGAN', 'TANGGAL POSTING', 'TANGGAL KADALUWARSA'];
        return Excel::download(new \App\Exports\GenericDataExport([], $headings), 'template_lowongan_penta.xlsx');
    }

    public function templatePencari()
    {
        $headings = ['NIK', 'NAMA', 'EMAIL', 'NO HP', 'TEMPAT TANGGAL LAHIR', 'ALAMAT DOMISILI', 'DOMISILI', 'JENIS KELAMIN', 'KONDISI FISIK', 'PENDIDIKAN TERAKHIR', 'JURUSAN', 'TANGGAL DAFTAR', 'STATUS VERIFIKASI'];
        return Excel::download(new \App\Exports\GenericDataExport([], $headings), 'template_pencari_penta.xlsx');
    }

    public function templatePenempatan()
    {
        $headings = ['NAMA', 'EMAIL', 'JUDUL LOWONGAN', 'KODE KBJI', 'NAMA PERUSAHAAN', 'PENDIDIKAN TERAKHIR PELAMAR', 'PENDIDIKAN MINIMAL LOKER', 'DOMISILI PELAMAR', 'DOMISILI LOWONGAN', 'TANGGAL MELAMAR', 'TANGGAL DITERIMA'];
        return Excel::download(new \App\Exports\GenericDataExport([], $headings), 'template_penempatan_penta.xlsx');
    }

    public function importLowongan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);
        try {
            Excel::import(new LowonganKerjaImport($request->bulan, $request->tahun), $request->file('file'));
            return Redirect::back()->with('success', 'Data Lowongan Kerja berhasil diimpor!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    public function importPencari(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);
        try {
            Excel::import(new PencariKerjaImport($request->bulan, $request->tahun), $request->file('file'));
            return Redirect::back()->with('success', 'Data Pencari Kerja Aktif berhasil diimpor!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    public function importPenempatan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);
        try {
            Excel::import(new PenempatanImport($request->bulan, $request->tahun), $request->file('file'));
            return Redirect::back()->with('success', 'Data Penempatan berhasil diimpor!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    public function updateLowongan(Request $request, $id)
    {
        $request->validate([
            'judul_lowongan' => 'required|string',
            'deskripsi_pekerjaan' => 'nullable|string',
            'perusahaan' => 'required|string',
            'kategori_pekerjaan' => 'nullable|string',
            'tipe_pekerjaan' => 'nullable|string',
            'sektor_pekerjaan' => 'nullable|string',
            'fungsi_pekerjaan' => 'nullable|string',
            'kode_kbji' => 'nullable|string',
            'minimal_pendidikan' => 'nullable|string',
            'keahlian_diperlukan' => 'nullable|string',
            'kebutuhan_disabilitas' => 'nullable|string',
            'kuota' => 'required|integer|min:0',
            'kuota_sisa' => 'required|integer|min:0',
            'status_lowongan' => 'required|string',
            'tanggal_posting' => 'nullable|date',
            'tanggal_kadaluwarsa' => 'nullable|date',
        ]);

        $lowongan = LowonganKerja::findOrFail($id);
        $lowongan->update($request->all());

        return Redirect::back()->with('success', 'Data Lowongan Kerja berhasil diperbarui!');
    }

    public function updatePencari(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'email' => 'nullable|email',
            'no_hp' => 'required|string',
            'tempat_tanggal_lahir' => 'required|string',
            'alamat_domisili' => 'required|string',
            'domisili' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'kondisi_fisik' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'tanggal_daftar' => 'nullable|date',
            'status_verifikasi' => 'required|string',
        ]);

        $pencari = PencariKerja::findOrFail($id);
        $pencari->update($request->all());

        return Redirect::back()->with('success', 'Data Pencari Kerja Aktif berhasil diperbarui!');
    }

    public function updatePenempatan(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'judul_lowongan' => 'required|string',
            'nama_perusahaan' => 'required|string',
            'pendidikan_terakhir_pelamar' => 'required|string',
        ]);

        $penempatan = Penempatan::findOrFail($id);
        $penempatan->update($request->all());

        return Redirect::back()->with('success', 'Data Penempatan berhasil diperbarui!');
    }

    public function destroyLowongan($id)
    {
        $lowongan = LowonganKerja::findOrFail($id);
        $lowongan->delete();
        return Redirect::back()->with('success', 'Data Lowongan Kerja berhasil dihapus!');
    }

    public function destroyPencari($id)
    {
        $pencari = PencariKerja::findOrFail($id);
        $pencari->delete();
        return Redirect::back()->with('success', 'Data Pencari Kerja berhasil dihapus!');
    }

    public function destroyPenempatan($id)
    {
        $penempatan = Penempatan::findOrFail($id);
        $penempatan->delete();
        return Redirect::back()->with('success', 'Data Penempatan berhasil dihapus!');
    }

    public function storeLowongan(Request $request)
    {
        $request->validate([
            'judul_lowongan' => 'required|string',
            'deskripsi_pekerjaan' => 'nullable|string',
            'perusahaan' => 'required|string',
            'kategori_pekerjaan' => 'nullable|string',
            'tipe_pekerjaan' => 'nullable|string',
            'sektor_pekerjaan' => 'nullable|string',
            'fungsi_pekerjaan' => 'nullable|string',
            'kode_kbji' => 'nullable|string',
            'minimal_pendidikan' => 'nullable|string',
            'keahlian_diperlukan' => 'nullable|string',
            'kebutuhan_disabilitas' => 'nullable|string',
            'kuota' => 'required|integer|min:0',
            'kuota_sisa' => 'required|integer|min:0',
            'status_lowongan' => 'required|string',
            'tanggal_posting' => 'nullable|date',
            'tanggal_kadaluwarsa' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['bulan'] = $request->bulan ?? date('n');
        $data['tahun'] = $request->tahun ?? date('Y');
        $data['tanggal_posting'] = $request->tanggal_posting ?? date('Y-m-d');

        LowonganKerja::create($data);

        return Redirect::back()->with('success', 'Data Lowongan Kerja berhasil ditambahkan!');
    }

    public function storeTenagaKerja(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'email' => 'nullable|email',
            'no_hp' => 'required|string',
            'tempat_tanggal_lahir' => 'required|string',
            'alamat_domisili' => 'required|string',
            'domisili' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'kondisi_fisik' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'tanggal_daftar' => 'nullable|date',
            'status_verifikasi' => 'required|string',
        ]);

        $data = $request->all();
        $data['bulan'] = $request->bulan ?? date('n');
        $data['tahun'] = $request->tahun ?? date('Y');
        $data['tanggal_daftar'] = $data['tanggal_daftar'] ?? date('Y-m-d');

        PencariKerja::create($data);

        return Redirect::back()->with('success', 'Data Pencari Kerja berhasil ditambahkan!');
    }

    public function storePenempatan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'judul_lowongan' => 'required|string',
            'nama_perusahaan' => 'required|string',
            'pendidikan_terakhir_pelamar' => 'required|string',
        ]);

        $data = $request->all();
        $data['bulan'] = $request->bulan ?? date('n');
        $data['tahun'] = $request->tahun ?? date('Y');
        $data['tanggal_diterima'] = $request->tanggal_diterima ?? date('Y-m-d');

        Penempatan::create($data);

        return Redirect::back()->with('success', 'Data Penempatan berhasil ditambahkan!');
    }

    public function bulkDeleteLowongan(Request $request)
    {
        $ids = json_decode($request->ids, true);
        if(is_array($ids)) {
            LowonganKerja::whereIn('id', $ids)->delete();
        }
        return Redirect::back()->with('success', count($ids) . ' Data Lowongan Kerja berhasil dihapus!');
    }

    public function bulkDeletePencari(Request $request)
    {
        $ids = json_decode($request->ids, true);
        if(is_array($ids)) {
            PencariKerja::whereIn('id', $ids)->delete();
        }
        return Redirect::back()->with('success', count($ids) . ' Data Pencari Kerja berhasil dihapus!');
    }

    public function bulkDeletePenempatan(Request $request)
    {
        $ids = json_decode($request->ids, true);
        if(is_array($ids)) {
            Penempatan::whereIn('id', $ids)->delete();
        }
        return Redirect::back()->with('success', count($ids) . ' Data Penempatan berhasil dihapus!');
    }
}
