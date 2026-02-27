<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {

    $username = $request->username;
    $password = $request->password;

    // ADMIN
    if ($username === 'admin' && $password === '123') {
        session(['role' => 'admin']);
        return redirect('/');
    }

    // PENTA
    if ($username === 'penta' && $password === '123') {
        session(['role' => 'penta']);
        return redirect('/');
    }

    // PHI
    if ($username === 'phi' && $password === '123') {
        session(['role' => 'phi']);
        return redirect('/');
    }

    // LATTAS
    if ($username === 'lattas' && $password === '123') {
        session(['role' => 'lattas']);
        return redirect('/');
    }

    return back()->with('error', 'Login gagal!');
});

Route::get('/logout', function () {
    session()->forget('role');
    return redirect('/login');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD (SEMUA ROLE YANG LOGIN)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (!session()->has('role')) {
        return redirect('/login');
    }

    // === BIDANG PENTA ===
    $totalPencariKerja = \App\Models\PencariKerja::count();
    $totalLowongan     = \App\Models\LowonganKerja::count();
    $totalPenempatan   = \App\Models\Penempatan::count();

    // === BIDANG PHI ===
    $totalLaporanPkwt  = \App\Models\PkwtReport::count();
    $totalPekerjaKwt   = \App\Models\PkwtReport::sum('total_pekerja');
    $totalKasusPhi     = \App\Models\PhiReport::sum('kasus_masuk');

    // === BIDANG LATTAS ===
    $totalPelatihan    = \App\Models\LpkTraining::count();
    $totalLpkAktif     = \App\Models\Lpk::where('status', 'aktif')->count();
    $totalPeserta      = \App\Models\LpkTraining::sum('jumlah_peserta');

    return view('dashboard', compact(
        'totalPencariKerja', 'totalLowongan', 'totalPenempatan',
        'totalLaporanPkwt', 'totalPekerjaKwt', 'totalKasusPhi',
        'totalPelatihan', 'totalLpkAktif', 'totalPeserta'
    ));
});


/*
|--------------------------------------------------------------------------
| ================= PENTA =================
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\PentaController;

Route::middleware(['web'])->group(function () {
    Route::get('/penta/tenaga-kerja', function (Request $request) {
        if (!session()->has('role')) return redirect('/login');
        if (!in_array(session('role'), ['admin', 'penta'])) abort(403);
        return app(PentaController::class)->tenagaKerja($request);
    });

    Route::get('/penta/lowongan', function (Request $request) {
        if (!session()->has('role')) return redirect('/login');
        if (!in_array(session('role'), ['admin', 'penta'])) abort(403);
        return app(PentaController::class)->lowongan($request);
    });

    Route::get('/penta/rekap', function (Request $request) {
        if (!session()->has('role')) return redirect('/login');
        if (!in_array(session('role'), ['admin', 'penta'])) abort(403);
        return app(PentaController::class)->rekap($request);
    });

    // Import Data Penta dari Excel
    Route::prefix('penta/import')->group(function () {
        Route::get('/', function (Request $request) {
            if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
            return app(PentaController::class)->importIndex($request);
        })->name('penta.import');

        Route::post('/lowongan', function (Request $request) {
            if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
            return app(PentaController::class)->importLowongan($request);
        })->name('penta.import.lowongan');

        Route::post('/pencari', function (Request $request) {
            if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
            return app(PentaController::class)->importPencari($request);
        })->name('penta.import.pencari');

        Route::post('/penempatan', function (Request $request) {
            if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
            return app(PentaController::class)->importPenempatan($request);
        })->name('penta.import.penempatan');
    });
});


/*
|--------------------------------------------------------------------------
| ================= PHI =================
|--------------------------------------------------------------------------
*/

// Rekap PKWT
Route::get('/phi/pkwt', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'phi'])) abort(403);

    return view('phi.pkwt');
});

// Rekap Pengaduan Kasus
Route::get('/phi/pengaduan', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'phi'])) abort(403);

    return view('phi.pengaduan');
});

// Rekap Peraturan Perusahaan
Route::get('/phi/peraturan', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'phi'])) abort(403);

    return view('phi.peraturan');
});


/*
|--------------------------------------------------------------------------
| ================= LATTAS =================
|--------------------------------------------------------------------------
*/

// Data Pelatihan
Route::get('/lattas/pelatihan', function (Request $request) {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'lattas'])) abort(403);

    $query = \App\Models\LpkTraining::with('lpk');
    
    if ($request->filled('bulan')) {
        $query->where('bulan', $request->bulan);
    }
    if ($request->filled('tahun')) {
        $query->where('tahun', $request->tahun);
    }

    $trainings = $query->get();
    return view('lattas.pelatihan', compact('trainings'));
});

// Rekap LPK Aktif
Route::get('/lattas/lpk-aktif', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'lattas'])) abort(403);

    $lpks = \App\Models\Lpk::where('status', 'aktif')->get();
    return view('lattas.lpk_aktif', compact('lpks'));
});

// Rekap LPK Non Aktif
Route::get('/lattas/lpk-nonaktif', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'lattas'])) abort(403);

    $lpks = \App\Models\Lpk::where('status', 'tidak aktif')->get();
    return view('lattas.lpk_nonaktif', compact('lpks'));
});

// Import Rekap Data LPK dari Excel
use App\Http\Controllers\LattasController;

Route::prefix('lattas/import')->group(function () {
    Route::get('/', function (Request $request) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'lattas'])) abort(403);
        return app(LattasController::class)->index($request);
    })->name('lattas.import');
    
    Route::post('/master', function (Request $request) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'lattas'])) abort(403);
        return app(LattasController::class)->importLpk($request);
    })->name('lattas.import.master');

    Route::post('/training', function (Request $request) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'lattas'])) abort(403);
        return app(LattasController::class)->importLpkTraining($request);
    })->name('lattas.import.training');
});