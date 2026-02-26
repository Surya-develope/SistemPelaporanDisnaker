<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/penta/tenaga-kerja', function () {
    return view('penta.tenaga_kerja');
});

Route::get('/phi/pkwt', function () {
    return view('phi.pkwt');
});

Route::get('/lattas/pelatihan', function () {
    return view('lattas.pelatihan');
});