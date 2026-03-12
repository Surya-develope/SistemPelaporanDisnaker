<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/* |-------------------------------------------------------------------------- | LOGIN & LOGOUT |-------------------------------------------------------------------------- */

// Login/Logout routes using AuthController
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');


/* |-------------------------------------------------------------------------- | DASHBOARD (Semua role bisa akses) |-------------------------------------------------------------------------- */

Route::middleware(['web', \App\Http\Middleware\RoleMiddleware::class . ':super_admin,admin,penta,phi,lattas,pejabat'])->group(function () {
    Route::get('/', function () {

            // === BIDANG PENTA ===
            $totalPencariKerja = \App\Models\PencariKerja::count();
            $totalLowongan = \App\Models\LowonganKerja::count();
            $totalPenempatan = \App\Models\Penempatan::count();

            // === BIDANG PHI ===
            $totalLaporanPkwt = \App\Models\PkwtReport::count();
            $totalPekerjaKwt = \App\Models\PkwtReport::sum('total_pekerja');
            $totalPerusahaanPP = \App\Models\PhiPeraturanPerusahaan::count();
            $totalKasusPhi = \App\Models\PhiReport::sum('kasus_masuk');
            
            // Hitung Tingkat Penyelesaian Kasus
            $totalKasusSelesai = \App\Models\PhiReport::sum('selesai_bipartit') + \App\Models\PhiReport::sum('selesai_pb') + \App\Models\PhiReport::sum('selesai_anjuran') + \App\Models\PhiReport::sum('selesai_lainnya');
            $tingkatPenyelesaianPhi = $totalKasusPhi > 0 ? round(($totalKasusSelesai / $totalKasusPhi) * 100, 1) : 0;

            // === BIDANG LATTAS ===
            $totalLpkAktif = \App\Models\Lpk::where('status', 'aktif')->count();
            $totalLpkNonaktif = \App\Models\Lpk::where('status', 'tidak aktif')->count();
            $totalPelatihan = \App\Models\LpkTraining::count();
            $totalPeserta = \App\Models\LpkTraining::sum('jumlah_peserta');

            // === GRAFIK BULANAN (tahun berjalan) ===
            $tahun = (int)date('Y');

            // Penta
            $pencariKerjaBulanan = \App\Models\PencariKerja::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->whereYear('created_at', $tahun)->groupByRaw('MONTH(created_at)')->pluck('total', 'bulan');
            $lowonganBulanan = \App\Models\LowonganKerja::selectRaw('bulan, SUM(kuota) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');
            $penempatanBulanan = \App\Models\Penempatan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->whereYear('created_at', $tahun)->groupByRaw('MONTH(created_at)')->pluck('total', 'bulan');

            // PHI
            $laporanPkwtBulanan = \App\Models\PkwtReport::selectRaw('bulan, COUNT(*) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');
            $pekerjaKwtBulanan = \App\Models\PkwtReport::selectRaw('bulan, SUM(total_pekerja) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');
            $kasusPhiBulanan = \App\Models\PhiReport::selectRaw('bulan, SUM(kasus_masuk) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');
            $perusahaanPPBulanan = \App\Models\PhiPeraturanPerusahaan::selectRaw('bulan, COUNT(*) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');
            $kasusDiselesaikanBulanan = \App\Models\PhiReport::selectRaw('bulan, SUM(selesai_bipartit + selesai_pb + selesai_anjuran + selesai_lainnya) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');

            // Lattas
            $pelatihanBulanan = \App\Models\LpkTraining::selectRaw('bulan, COUNT(*) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');
            $lpkAktifBulanan = \App\Models\Lpk::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->where('status', 'aktif')->whereYear('created_at', $tahun)->groupByRaw('MONTH(created_at)')->pluck('total', 'bulan');
            $lpkNonaktifBulanan = \App\Models\Lpk::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->where('status', 'tidak aktif')->whereYear('created_at', $tahun)->groupByRaw('MONTH(created_at)')->pluck('total', 'bulan');
            $pesertaPelatihanBulanan = \App\Models\LpkTraining::selectRaw('bulan, SUM(jumlah_peserta) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');

            // Susun array 12 bulan
            $chartData = [
                'pencariKerja' => collect(range(1, 12))->map(fn($m) => (int)($pencariKerjaBulanan[$m] ?? 0))->values()->toArray(),
                'lowongan' => collect(range(1, 12))->map(fn($m) => (int)($lowonganBulanan[$m] ?? 0))->values()->toArray(),
                'penempatan' => collect(range(1, 12))->map(fn($m) => (int)($penempatanBulanan[$m] ?? 0))->values()->toArray(),
                
                'laporanPkwt' => collect(range(1, 12))->map(fn($m) => (int)($laporanPkwtBulanan[$m] ?? 0))->values()->toArray(),
                'pekerjaKwt' => collect(range(1, 12))->map(fn($m) => (int)($pekerjaKwtBulanan[$m] ?? 0))->values()->toArray(),
                'kasusPhi' => collect(range(1, 12))->map(fn($m) => (int)($kasusPhiBulanan[$m] ?? 0))->values()->toArray(),
                'perusahaanPP' => collect(range(1, 12))->map(fn($m) => (int)($perusahaanPPBulanan[$m] ?? 0))->values()->toArray(),
                'kasusDiselesaikan' => collect(range(1, 12))->map(fn($m) => (int)($kasusDiselesaikanBulanan[$m] ?? 0))->values()->toArray(),
                
                'pelatihan' => collect(range(1, 12))->map(fn($m) => (int)($pelatihanBulanan[$m] ?? 0))->values()->toArray(),
                'lpkAktif' => collect(range(1, 12))->map(fn($m) => (int)($lpkAktifBulanan[$m] ?? 0))->values()->toArray(),
                'lpkNonaktif' => collect(range(1, 12))->map(fn($m) => (int)($lpkNonaktifBulanan[$m] ?? 0))->values()->toArray(),
                'pesertaPelatihan' => collect(range(1, 12))->map(fn($m) => (int)($pesertaPelatihanBulanan[$m] ?? 0))->values()->toArray(),
            ];

            return view('dashboard', compact(
            'totalPencariKerja', 'totalLowongan', 'totalPenempatan',
            'totalLaporanPkwt', 'totalPekerjaKwt', 'totalKasusPhi', 'totalPerusahaanPP', 'tingkatPenyelesaianPhi',
            'totalLpkAktif', 'totalLpkNonaktif', 'totalPelatihan', 'totalPeserta',
            'chartData', 'tahun'
            ));
        }
        );

        Route::get('/dashboard/export', function (\Illuminate\Http\Request $request) {
            $tahun = $request->query('tahun', date('Y'));
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DashboardExport($tahun), 'Laporan_Lengkap_Disnaker_'.$tahun.'.xlsx');
        })->name('dashboard.export');

        Route::get('/dashboard/detail/{type}', function ($type, \Illuminate\Http\Request $request) {
            $tahun = $request->query('tahun', date('Y'));
            $data = [];
            $title = '';
            $headers = ['No', 'Nama / Entitas Utama', 'Informasi 1', 'Informasi 2', 'Status'];

            switch ($type) {
                // PENTA
                case 'pencariKerja':
                    $data = \App\Models\PencariKerja::where('tahun', $tahun)
                        ->select('nama_lengkap as nama', 'jenis_kelamin as detail_1', 'pendidikan_terakhir as detail_2', 'status_bekerja as status')
                        ->latest()->get();
                    $title = 'Daftar Pencari Kerja Aktif';
                    $headers = ['No', 'Nama Lengkap', 'Jenis Kelamin', 'Pendidikan', 'Status Bekerja'];
                    break;
                case 'lowongan':
                    $data = \App\Models\LowonganKerja::where('tahun', $tahun)
                        ->select('perusahaan as nama', 'judul_lowongan as detail_1', 'kuota as detail_2', 'status_lowongan as status')
                        ->latest()->get();
                    $title = 'Daftar Lowongan Pekerjaan';
                    $headers = ['No', 'Perusahaan', 'Judul Lowongan', 'Kuota', 'Status Lowongan'];
                    break;
                case 'penempatan':
                    $data = \App\Models\Penempatan::where('tahun', $tahun)
                        ->select('nama_pekerja as nama', 'perusahaan as detail_1', 'jabatan as detail_2', 'status_penempatan as status')
                        ->latest()->get();
                    $title = 'Daftar Penempatan Tenaga Kerja';
                    $headers = ['No', 'Nama Pekerja', 'Perusahaan', 'Jabatan', 'Status Penempatan'];
                    break;

                // PHI
                case 'laporanPkwt':
                    $data = \App\Models\PkwtReport::where('tahun', $tahun)
                        ->select('nama_perusahaan as nama', 'no_pencatatan as detail_1', 'total_pekerja as detail_2')
                        ->latest()->get();
                    $title = 'Daftar Laporan PKWT';
                    $headers = ['No', 'Nama Perusahaan', 'No. Pencatatan', 'Total Pekerja', 'Status'];
                    break;
                case 'pekerjaKwt':
                    $data = \App\Models\PkwtReport::where('tahun', $tahun)
                        ->select('nama_pekerja as nama', 'nama_perusahaan as detail_1', 'jabatan as detail_2')
                        ->latest()->get();
                    $title = 'Daftar Pekerja PKWT';
                    $headers = ['No', 'Nama Pekerja', 'Perusahaan', 'Jabatan', 'Status'];
                    break;
                case 'kasusPhi':
                    $data = \App\Models\PhiReport::where('tahun', $tahun)
                        ->selectRaw('CONCAT("Bulan: ", bulan) as nama, sisa_bulan_lalu as detail_1, kasus_masuk as detail_2, sisa_kasus_akhir as status')
                        ->latest()->get();
                    $title = 'Rekapitulasi Kasus PHI';
                    $headers = ['No', 'Bulan', 'Sisa Kasus Lalu', 'Kasus Masuk', 'Sisa Kasus Akhir'];
                    break;
                case 'perusahaanPP':
                    $data = \App\Models\PhiPeraturanPerusahaan::where('tahun', $tahun)
                        ->select('nama_perusahaan as nama', 'sektor_usaha as detail_1', 'no_sk as detail_2', 'status_pp as status')
                        ->latest()->get();
                    $title = 'Daftar Perusahaan (Peraturan Perusahaan)';
                    $headers = ['No', 'Nama Perusahaan', 'Sektor Pekerjaan', 'Masa Berlaku (SK)', 'Status Peraturan'];
                    break;
                case 'kasusDiselesaikan':
                    $data = \App\Models\PhiReport::where('tahun', $tahun)
                        ->selectRaw('CONCAT("Bulan: ", bulan) as nama, kasus_masuk as detail_1, (selesai_bipartit + selesai_pb + selesai_anjuran + selesai_lainnya) as detail_2, sisa_kasus_akhir as status')
                        ->latest()->get();
                    $title = 'Penyelesaian Kasus Bulanan';
                    $headers = ['No', 'Bulan', 'Kasus Masuk', 'Berhasil Diselesaikan', 'Sisa Kasus Akhir'];
                    break;

                // LATTAS
                case 'lpkAktif':
                    $data = \App\Models\Lpk::where('status', 'aktif')->whereYear('created_at', '<=', $tahun)
                        ->select('nama_lpk as nama', 'nama_pimpinan as detail_1', 'alamat as detail_2', 'status')
                        ->latest()->get();
                    $title = 'Daftar LPK Aktif';
                    $headers = ['No', 'Nama LPK', 'Nama Pimpinan', 'Alamat', 'Status'];
                    break;
                case 'lpkNonaktif':
                    $data = \App\Models\Lpk::where('status', 'tidak aktif')->whereYear('created_at', '<=', $tahun)
                        ->select('nama_lpk as nama', 'nama_pimpinan as detail_1', 'alamat as detail_2', 'status')
                        ->latest()->get();
                    $title = 'Daftar LPK Tidak Aktif';
                    $headers = ['No', 'Nama LPK', 'Nama Pimpinan', 'Alamat', 'Status'];
                    break;
                case 'pelatihan':
                    $data = \App\Models\LpkTraining::where('tahun', $tahun)->latest()->get()->map(function($item) {
                        return [
                            'nama' => $item->nama_lpk ? $item->nama_lpk : '-',
                            'detail_1' => $item->program_pelatihan,
                            'detail_2' => $item->jumlah_peserta . ' Orang',
                            'status' => $item->jumlah_paket . ' Paket'
                        ];
                    });
                    $title = 'Daftar Program Pelatihan';
                    $headers = ['No', 'Nama LPK', 'Program Pelatihan', 'Jumlah Peserta', 'Jumlah Paket'];
                    break;
            }

            return response()->json([
                'title' => $title,
                'headers' => $headers,
                'data' => $data
            ]);
        })->name('dashboard.detail');

    });


/* |-------------------------------------------------------------------------- | ROUTES PENGGUNA (Admin Only) |-------------------------------------------------------------------------- */

Route::middleware(['web', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
});

/* |-------------------------------------------------------------------------- | ROUTES BIDANG |-------------------------------------------------------------------------- */

require __DIR__ . '/penta.php';
require __DIR__ . '/phi.php';
require __DIR__ . '/lattas.php';