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
    Route::get('/', function (\Illuminate\Http\Request $request) {
            $tahun = $request->query('tahun', (int)date('Y'));

            // === BIDANG PENTA ===
            $totalPencariKerja = \App\Models\PencariKerja::where('tahun', $tahun)->count();
            $totalLowongan = \App\Models\LowonganKerja::where('tahun', $tahun)->count();
            $totalPenempatan = \App\Models\Penempatan::where('tahun', $tahun)->count();

            // === BIDANG PHI ===
            $totalLaporanPkwt = \App\Models\PkwtReport::where('tahun', $tahun)->count();
            $totalPekerjaKwt = \App\Models\PkwtReport::where('tahun', $tahun)->sum('total_pekerja');
            $totalPerusahaanPP = \App\Models\PhiPeraturanPerusahaan::where('tahun', $tahun)->count();
            $totalKasusPhi = \App\Models\PhiReport::where('tahun', $tahun)->count();
            
            // Hitung Tingkat Penyelesaian Kasus
            $totalKasusSelesai = \App\Models\PhiReport::where('tahun', $tahun)->where('status_kasus', 'selesai')->count();
            $tingkatPenyelesaianPhi = $totalKasusPhi > 0 ? round(($totalKasusSelesai / $totalKasusPhi) * 100, 1) : 0;

            // === BIDANG LATTAS ===
            $totalLpkAktif = \App\Models\Lpk::where('status', 'aktif')->whereYear('created_at', '<=', $tahun)->count();
            $totalLpkNonaktif = \App\Models\Lpk::where('status', 'tidak aktif')->whereYear('created_at', '<=', $tahun)->count();
            $totalPelatihan = \App\Models\LpkTraining::where('tahun', $tahun)->count();
            $totalPeserta = \App\Models\LpkTraining::where('tahun', $tahun)->sum('jumlah_peserta');

            // === GRAFIK BULANAN (tahun berjalan) ===

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
            $kasusPhiBulanan = \App\Models\PhiReport::selectRaw('bulan, COUNT(*) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');
            $perusahaanPPBulanan = \App\Models\PhiPeraturanPerusahaan::selectRaw('bulan, COUNT(*) as total')
                ->where('tahun', $tahun)->groupBy('bulan')->pluck('total', 'bulan');
            $kasusDiselesaikanBulanan = \App\Models\PhiReport::selectRaw('bulan, COUNT(*) as total')
                ->where('tahun', $tahun)->where('status_kasus', 'selesai')->groupBy('bulan')->pluck('total', 'bulan');

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

            $pieMetodeRaw = \App\Models\PhiReport::where('tahun', $tahun)
                ->where('status_kasus', 'selesai')
                ->selectRaw("COALESCE(metode_penyelesaian, 'DLL') as metode, COUNT(*) as total")
                ->groupBy('metode')
                ->pluck('total', 'metode')->toArray();
            
            // Standardize categories
            $pieMetode = [
                'Bipartit' => 0,
                'Perjanjian Bersama' => 0,
                'Anjuran' => 0,
                'DLL' => 0,
            ];
            foreach($pieMetodeRaw as $k => $v) {
                // Determine category
                $kLow = strtolower($k);
                if (str_contains($kLow, 'bipartit') || str_contains($kLow, 'bipartid')) $pieMetode['Bipartit'] += $v;
                elseif (str_contains($kLow, 'perjanjian bersama') || str_contains($kLow, 'pb')) $pieMetode['Perjanjian Bersama'] += $v;
                elseif (str_contains($kLow, 'anjuran')) $pieMetode['Anjuran'] += $v;
                else $pieMetode['DLL'] += $v;
            }

            return view('dashboard', compact(
            'totalPencariKerja', 'totalLowongan', 'totalPenempatan',
            'totalLaporanPkwt', 'totalPekerjaKwt', 'totalKasusPhi', 'totalPerusahaanPP', 'tingkatPenyelesaianPhi',
            'totalLpkAktif', 'totalLpkNonaktif', 'totalPelatihan', 'totalPeserta',
            'chartData', 'pieMetode', 'tahun'
            ));
        }
        );

        Route::get('/dashboard/export', function (\Illuminate\Http\Request $request) {
            $tahun = $request->query('tahun', date('Y'));
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DashboardExport($tahun), 'Laporan_Lengkap_Disnaker_'.$tahun.'.xlsx');
        })->name('dashboard.export');

        Route::get('/dashboard/detail/{type}', function ($type, \Illuminate\Http\Request $request) {
            $tahun = $request->query('tahun');
            $bulan = $request->query('bulan');
            $data = [];
            $title = '';
            $headers = ['No', 'Nama / Entitas Utama', 'Informasi 1', 'Informasi 2', 'Status'];

            switch ($type) {
                // PENTA
                case 'pencariKerja':
                    $query = \App\Models\PencariKerja::query();
                    if ($tahun) $query->where('tahun', $tahun);
                    if ($bulan) $query->where('bulan', $bulan);
                    
                    $data = $query->select('nama', 'jenis_kelamin as detail_1', 'pendidikan_terakhir as detail_2', 'status_verifikasi as status')
                        ->latest()->get();
                    $title = 'Daftar Pencari Kerja Aktif';
                    $headers = ['No', 'Nama Lengkap', 'Jenis Kelamin', 'Pendidikan', 'Status Verifikasi'];
                    break;
                case 'lowongan':
                    $query = \App\Models\LowonganKerja::query();
                    if ($tahun) $query->where('tahun', $tahun);
                    if ($bulan) $query->where('bulan', $bulan);

                    $data = $query->select('perusahaan as nama', 'judul_lowongan as detail_1', 'kuota as detail_2', 'status_lowongan as status')
                        ->latest()->get();
                    $title = 'Daftar Lowongan Pekerjaan';
                    $headers = ['No', 'Perusahaan', 'Judul Lowongan', 'Kuota', 'Status Lowongan'];
                    break;
                case 'penempatan':
                    $query = \App\Models\Penempatan::query();
                    if ($tahun) $query->where('tahun', $tahun);
                    if ($bulan) $query->where('bulan', $bulan);

                    $data = $query->select('nama as nama', 'nama_perusahaan as detail_1', 'judul_lowongan as detail_2', 'tanggal_diterima as status')
                        ->latest()->get();
                    
                    // Format dates
                    $data->transform(function ($item) {
                        $item->status = $item->status ? date('d-m-Y', strtotime($item->status)) : '-';
                        return $item;
                    });
                    
                    $title = 'Daftar Penempatan Tenaga Kerja';
                    $headers = ['No', 'Nama Pekerja', 'Perusahaan', 'Judul Lowongan', 'Tgl Diterima'];
                    break;

                // PHI
                case 'laporanPkwt':
                    $query = \App\Models\PkwtReport::query();
                    if ($tahun) $query->where('tahun', $tahun);
                    if ($bulan) $query->where('bulan', $bulan);

                    $data = $query->select('nama_perusahaan as nama', 'no_pencatatan as detail_1', 'total_pekerja as detail_2')
                        ->latest()->get();
                    $title = 'Daftar Laporan PKWT';
                    $headers = ['No', 'Nama Perusahaan', 'No. Pencatatan', 'Total Pekerja', 'Aksi'];
                    break;
                case 'pekerjaKwt':
                    $query = \App\Models\PkwtReport::query();
                    if ($tahun) $query->where('tahun', $tahun);
                    if ($bulan) $query->where('bulan', $bulan);

                    $data = $query->select('nama_pekerja as nama', 'nama_perusahaan as detail_1', 'jabatan as detail_2')
                        ->latest()->get();
                    $title = 'Daftar Pekerja PKWT';
                    $headers = ['No', 'Nama Pekerja', 'Perusahaan', 'Jabatan', 'Aksi'];
                    break;
                case 'kasusPhi':
                    $query = \App\Models\PhiReport::query();
                    if ($tahun) $query->where('tahun', $tahun);
                    if ($bulan) $query->where('bulan', $bulan);

                    $data = $query->selectRaw('nama_perusahaan as nama, COALESCE(jenis_perselisihan, "-") as detail_1, CONCAT(IFNULL(jml_org, 0), " Pekerja") as detail_2, status_kasus as status')
                        ->latest()->get();
                    $title = 'Daftar Kasus PHI Masuk';
                    $headers = ['No', 'Nama Perusahaan', 'Jenis Perselisihan', 'Jml Pekerja', 'Status Kasus'];
                    break;
                case 'perusahaanPP':
                    $query = \App\Models\PhiPeraturanPerusahaan::query();
                    if ($tahun) $query->where('tahun', $tahun);
                    if ($bulan) $query->where('bulan', $bulan);

                    $data = $query->select('nama_perusahaan as nama', 'sektor_usaha as detail_1', 'no_sk as detail_2', 'status_pp as status')
                        ->latest()->get();
                    $title = 'Daftar Perusahaan (Peraturan Perusahaan)';
                    $headers = ['No', 'Nama Perusahaan', 'Sektor Pekerjaan', 'Masa Berlaku (SK)', 'Status Peraturan'];
                    break;
                case 'kasusDiselesaikan':
                    $query = \App\Models\PhiReport::where('status_kasus', 'selesai');
                    if ($tahun) $query->where('tahun', $tahun);
                    if ($bulan) $query->where('bulan', $bulan);

                    $data = $query->selectRaw('nama_perusahaan as nama, COALESCE(jenis_perselisihan, "-") as detail_1, CONCAT(IFNULL(jml_org, 0), " Pekerja") as detail_2, COALESCE(metode_penyelesaian, "-") as status')
                        ->latest()->get();
                    $title = 'Kasus PHI Diselesaikan';
                    $headers = ['No', 'Nama Perusahaan', 'Jenis Perselisihan', 'Jml Pekerja', 'Penyelesaian'];
                    break;

                // LATTAS
                case 'lpkAktif':
                    $query = \App\Models\Lpk::where('status', 'aktif');
                    if ($tahun) $query->whereYear('created_at', '<=', $tahun);
                    
                    $data = $query->select('nama_lpk as nama', 'nama_pimpinan as detail_1', 'alamat as detail_2', 'status')
                        ->latest()->get();
                    $title = 'Daftar LPK Aktif';
                    $headers = ['No', 'Nama LPK', 'Nama Pimpinan', 'Alamat', 'Status'];
                    break;
                case 'lpkNonaktif':
                    $query = \App\Models\Lpk::where('status', 'tidak aktif');
                    if ($tahun) $query->whereYear('created_at', '<=', $tahun);

                    $data = $query->select('nama_lpk as nama', 'nama_pimpinan as detail_1', 'alamat as detail_2', 'status')
                        ->latest()->get();
                    $title = 'Daftar LPK Tidak Aktif';
                    $headers = ['No', 'Nama LPK', 'Nama Pimpinan', 'Alamat', 'Status'];
                    break;
                case 'pelatihan':
                    $query = \App\Models\LpkTraining::query();
                    if ($tahun) $query->where('tahun', $tahun);
                    if ($bulan) $query->where('bulan', $bulan);

                    $data = $query->latest()->get()->map(function($item) {
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