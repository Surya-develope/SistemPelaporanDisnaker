<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/* |-------------------------------------------------------------------------- | LOGIN & LOGOUT |-------------------------------------------------------------------------- */

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {

    $username = $request->username;
    $password = $request->password;

    $validRoles = ['admin', 'penta', 'phi', 'lattas'];

    if (in_array($username, $validRoles) && $password === '123') {
        $user = \App\Models\User::where('role', $username)->first();

        session([
            'role' => $username,
            'user_id' => $user ? $user->id : 1, // Fallback ke ID 1 jika user belum ada di DB
            'name' => $user ? $user->name : ucfirst($username),
        ]);

        return redirect('/');
    }

    return back()->with('error', 'Login gagal!');
});

Route::get('/logout', function () {
    session()->forget('role');
    return redirect('/login');
});


/* |-------------------------------------------------------------------------- | DASHBOARD (Semua role bisa akses) |-------------------------------------------------------------------------- */

Route::middleware(['web', \App\Http\Middleware\RoleMiddleware::class . ':admin,penta,phi,lattas'])->group(function () {
    Route::get('/', function () {

            // === BIDANG PENTA ===
            $totalPencariKerja = \App\Models\PencariKerja::count();
            $totalLowongan = \App\Models\LowonganKerja::count();
            $totalPenempatan = \App\Models\Penempatan::count();

            // === BIDANG PHI ===
            $totalLaporanPkwt = \App\Models\PkwtReport::count();
            $totalPekerjaKwt = \App\Models\PkwtReport::sum('total_pekerja');
            $totalKasusPhi = \App\Models\PhiReport::sum('kasus_masuk');

            // === BIDANG LATTAS ===
            $totalPelatihan = \App\Models\LpkTraining::count();
            $totalLpkAktif = \App\Models\Lpk::where('status', 'aktif')->count();
            $totalPeserta = \App\Models\LpkTraining::sum('jumlah_peserta');

            // === GRAFIK BULANAN (tahun berjalan) ===
            $tahun = (int)date('Y');

            // Penta: jumlah pencari kerja terdaftar per bulan
            $pentaBulanan = \App\Models\PencariKerja::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->whereYear('created_at', $tahun)
                ->groupByRaw('MONTH(created_at)')
                ->pluck('total', 'bulan');

            // PHI: total pekerja PKWT per bulan
            $phiBulanan = \App\Models\PkwtReport::selectRaw('bulan, SUM(total_pekerja) as total')
                ->where('tahun', $tahun)
                ->groupBy('bulan')
                ->pluck('total', 'bulan');

            // Lattas: jumlah peserta pelatihan per bulan
            $lattasBulanan = \App\Models\LpkTraining::selectRaw('bulan, SUM(jumlah_peserta) as total')
                ->where('tahun', $tahun)
                ->groupBy('bulan')
                ->pluck('total', 'bulan');

            // Susun array 12 bulan
            $chartPenta = collect(range(1, 12))->map(fn($m) => (int)($pentaBulanan[$m] ?? 0))->values()->toArray();
            $chartPhi = collect(range(1, 12))->map(fn($m) => (int)($phiBulanan[$m] ?? 0))->values()->toArray();
            $chartLattas = collect(range(1, 12))->map(fn($m) => (int)($lattasBulanan[$m] ?? 0))->values()->toArray();

            return view('dashboard', compact(
            'totalPencariKerja', 'totalLowongan', 'totalPenempatan',
            'totalLaporanPkwt', 'totalPekerjaKwt', 'totalKasusPhi',
            'totalPelatihan', 'totalLpkAktif', 'totalPeserta',
            'chartPenta', 'chartPhi', 'chartLattas', 'tahun'
            ));
        }
        );
    });


/* |-------------------------------------------------------------------------- | ROUTES BIDANG |-------------------------------------------------------------------------- */

require __DIR__ . '/penta.php';
require __DIR__ . '/phi.php';
require __DIR__ . '/lattas.php';