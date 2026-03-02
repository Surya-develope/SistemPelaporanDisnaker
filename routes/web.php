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

    // === GRAFIK BULANAN (tahun berjalan) ===
    $tahun = (int) date('Y');

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

    // Susun array 12 bulan (indeks 0=Jan … 11=Des)
    $chartPenta  = collect(range(1, 12))->map(fn($m) => (int) ($pentaBulanan[$m]  ?? 0))->values()->toArray();
    $chartPhi    = collect(range(1, 12))->map(fn($m) => (int) ($phiBulanan[$m]    ?? 0))->values()->toArray();
    $chartLattas = collect(range(1, 12))->map(fn($m) => (int) ($lattasBulanan[$m] ?? 0))->values()->toArray();

    return view('dashboard', compact(
        'totalPencariKerja', 'totalLowongan', 'totalPenempatan',
        'totalLaporanPkwt', 'totalPekerjaKwt', 'totalKasusPhi',
        'totalPelatihan', 'totalLpkAktif', 'totalPeserta',
        'chartPenta', 'chartPhi', 'chartLattas', 'tahun'
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

    // Delete Data Penta
    Route::delete('/penta/lowongan/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
        return app(PentaController::class)->destroyLowongan($id);
    })->name('penta.destroy.lowongan');

    Route::delete('/penta/pencari/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
        return app(PentaController::class)->destroyPencari($id);
    })->name('penta.destroy.pencari');

    Route::delete('/penta/penempatan/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
        return app(PentaController::class)->destroyPenempatan($id);
    })->name('penta.destroy.penempatan');
    
    // Update Data Penta
    Route::put('/penta/lowongan/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
        return app(PentaController::class)->updateLowongan($request, $id);
    })->name('penta.update.lowongan');

    Route::put('/penta/pencari/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
        return app(PentaController::class)->updatePencari($request, $id);
    })->name('penta.update.pencari');

    Route::put('/penta/penempatan/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'penta'])) abort(403);
        return app(PentaController::class)->updatePenempatan($request, $id);
    })->name('penta.update.penempatan');
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
Route::get('/lattas/lpk-aktif', function (Request $request) {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'lattas'])) abort(403);

    $query = \App\Models\Lpk::where('status', 'aktif');
    
    if ($request->filled('bulan')) {
        $query->where('bulan', $request->bulan);
    }
    if ($request->filled('tahun')) {
        $query->where('tahun', $request->tahun);
    }

    $lpks = $query->get();
    return view('lattas.lpk_aktif', compact('lpks'));
});

// Rekap LPK Non Aktif
Route::get('/lattas/lpk-nonaktif', function (Request $request) {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'lattas'])) abort(403);

    $query = \App\Models\Lpk::where('status', 'tidak aktif');
    
    if ($request->filled('bulan')) {
        $query->where('bulan', $request->bulan);
    }
    if ($request->filled('tahun')) {
        $query->where('tahun', $request->tahun);
    }

    $lpks = $query->get();
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

// Delete Data Lattas
Route::middleware(['web'])->group(function () {
    Route::delete('/lattas/lpk/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'lattas'])) abort(403);
        return app(LattasController::class)->destroyLpk($id);
    })->name('lattas.destroy.lpk');

    Route::delete('/lattas/training/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'lattas'])) abort(403);
        return app(LattasController::class)->destroyLpkTraining($id);
    })->name('lattas.destroy.training');
    
    // Update Data Lattas
    Route::put('/lattas/lpk/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'lattas'])) abort(403);
        return app(LattasController::class)->updateLpk($request, $id);
    })->name('lattas.update.lpk');

    Route::put('/lattas/training/{id}', function (Request $request, $id) {
        if (!session()->has('role') || !in_array(session('role'), ['admin', 'lattas'])) abort(403);
        return app(LattasController::class)->updateLpkTraining($request, $id);
    })->name('lattas.update.training');
});