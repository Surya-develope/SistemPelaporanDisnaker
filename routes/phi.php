<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhiController;

Route::middleware(['web', \App\Http\Middleware\RoleMiddleware::class . ':admin,phi'])->prefix('phi')->group(function () {
    // Rekap PKWT
    Route::get('/pkwt', [PhiController::class , 'pkwt'])->name('phi.pkwt');
    Route::post('/pkwt', [PhiController::class , 'storePkwt'])->name('phi.store.pkwt');
    Route::get('/pkwt/template', [PhiController::class , 'downloadPkwtTemplate'])->name('phi.template.pkwt');
    Route::get('/pkwt/export', [PhiController::class , 'exportPkwt'])->name('phi.export.pkwt');
    Route::post('/pkwt/import', [PhiController::class , 'importPkwt'])->name('phi.import.pkwt');
    Route::put('/pkwt/{id}', [PhiController::class , 'updatePkwt'])->name('phi.update.pkwt');
    Route::delete('/pkwt/{id}', [PhiController::class , 'destroyPkwt'])->name('phi.destroy.pkwt');

    // Rekap Pengaduan Kasus
    Route::get('/pengaduan', [PhiController::class , 'pengaduan']);
    Route::get('/pengaduan/export', [PhiController::class , 'exportPengaduan'])->name('phi.export.pengaduan');
    Route::post('/pengaduan', [PhiController::class , 'storePengaduan'])->name('phi.store.pengaduan');
    Route::put('/pengaduan/{id}', [PhiController::class , 'updatePengaduan'])->name('phi.update.pengaduan');
    Route::delete('/pengaduan/{id}', [PhiController::class , 'destroyPengaduan'])->name('phi.destroy.pengaduan');

    // Rekap Peraturan Perusahaan
    Route::get('/peraturan', function () {
            return view('phi.peraturan');
        }
        );
    });
