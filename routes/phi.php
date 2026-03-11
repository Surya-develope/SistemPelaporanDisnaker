<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhiPkwtController;
use App\Http\Controllers\PhiPengaduanController;
use App\Http\Controllers\PhiPeraturanPerusahaanController;

Route::middleware(['web', \App\Http\Middleware\RoleMiddleware::class . ':admin,phi'])->prefix('phi')->group(function () {
    // Rekap PKWT
    Route::get('/pkwt', [PhiPkwtController::class , 'index'])->name('phi.pkwt');
    Route::post('/pkwt', [PhiPkwtController::class , 'store'])->name('phi.store.pkwt');
    Route::get('/pkwt/template', [PhiPkwtController::class , 'downloadTemplate'])->name('phi.template.pkwt');
    Route::get('/pkwt/export', [PhiPkwtController::class , 'export'])->name('phi.export.pkwt');
    Route::post('/pkwt/import', [PhiPkwtController::class , 'import'])->name('phi.import.pkwt');
    Route::put('/pkwt/{id}', [PhiPkwtController::class , 'update'])->name('phi.update.pkwt');
    Route::delete('/pkwt/{id}', [PhiPkwtController::class , 'destroy'])->name('phi.destroy.pkwt');

    // Rekap Pengaduan Kasus
    Route::get('/pengaduan', [PhiPengaduanController::class , 'index']);
    Route::get('/pengaduan/export', [PhiPengaduanController::class , 'export'])->name('phi.export.pengaduan');
    Route::post('/pengaduan', [PhiPengaduanController::class , 'store'])->name('phi.store.pengaduan');
    Route::put('/pengaduan/{id}', [PhiPengaduanController::class , 'update'])->name('phi.update.pengaduan');
    Route::delete('/pengaduan/{id}', [PhiPengaduanController::class , 'destroy'])->name('phi.destroy.pengaduan');

    // Rekap Peraturan Perusahaan
    Route::get('/peraturan', [PhiPeraturanPerusahaanController::class , 'index'])->name('phi.peraturan');
    Route::get('/peraturan/template', [PhiPeraturanPerusahaanController::class , 'downloadTemplate'])->name('phi.template.peraturan');
    Route::get('/peraturan/export', [PhiPeraturanPerusahaanController::class , 'export'])->name('phi.export.peraturan');
    Route::post('/peraturan/import', [PhiPeraturanPerusahaanController::class , 'import'])->name('phi.import.peraturan');
    Route::post('/peraturan', [PhiPeraturanPerusahaanController::class , 'store'])->name('phi.store.peraturan');
    Route::put('/peraturan/{id}', [PhiPeraturanPerusahaanController::class , 'update'])->name('phi.update.peraturan');
    Route::delete('/peraturan/{id}', [PhiPeraturanPerusahaanController::class , 'destroy'])->name('phi.destroy.peraturan');
});
