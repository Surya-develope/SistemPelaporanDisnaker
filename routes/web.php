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

    return view('dashboard');
});


/*
|--------------------------------------------------------------------------
| ================= PENTA =================
|--------------------------------------------------------------------------
*/

Route::get('/penta/tenaga-kerja', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'penta'])) abort(403);

    return view('penta.tenaga_kerja');
});

Route::get('/penta/lowongan', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'penta'])) abort(403);

    return view('penta.lowongan');
});

Route::get('/penta/rekap', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'penta'])) abort(403);

    return view('penta.rekap');
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
Route::get('/lattas/pelatihan', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'lattas'])) abort(403);

    return view('lattas.pelatihan');
});

// Rekap LPK Aktif
Route::get('/lattas/lpk-aktif', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'lattas'])) abort(403);

    return view('lattas.lpk_aktif');
});

// Rekap LPK Non Aktif
Route::get('/lattas/lpk-nonaktif', function () {

    if (!session()->has('role')) return redirect('/login');
    if (!in_array(session('role'), ['admin', 'lattas'])) abort(403);

    return view('lattas.lpk_nonaktif');
});