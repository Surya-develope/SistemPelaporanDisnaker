<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LattasController;

Route::middleware(['web', \App\Http\Middleware\RoleMiddleware::class . ':admin,lattas'])->prefix('lattas')->group(function () {
    // Data Pelatihan
    Route::get('/pelatihan', [LattasController::class , 'pelatihan']);
    Route::get('/pelatihan/export', [LattasController::class , 'exportPelatihan'])->name('lattas.export.pelatihan');

    // Rekap LPK
    Route::get('/lpk-aktif', [LattasController::class , 'lpkAktif']);
    Route::get('/lpk-aktif/export', [LattasController::class , 'exportLpkAktif'])->name('lattas.export.lpk_aktif');
    Route::get('/lpk-nonaktif', [LattasController::class , 'lpkNonaktif']);
    Route::get('/lpk-nonaktif/export', [LattasController::class , 'exportLpkNonaktif'])->name('lattas.export.lpk_nonaktif');

    // Import Rekap Data LPK dari Excel
    Route::prefix('import')->group(function () {
            Route::get('/', [LattasController::class , 'index'])->name('lattas.import');
            Route::post('/master', [LattasController::class , 'importLpk'])->name('lattas.import.master');
            Route::post('/training', [LattasController::class , 'importLpkTraining'])->name('lattas.import.training');
        }
        );

        // Delete Data Lattas
        Route::delete('/lpk/{id}', [LattasController::class , 'destroyLpk'])->name('lattas.destroy.lpk');
        Route::delete('/training/{id}', [LattasController::class , 'destroyLpkTraining'])->name('lattas.destroy.training');

        // Update Data Lattas
        Route::put('/lpk/{id}', [LattasController::class , 'updateLpk'])->name('lattas.update.lpk');
        Route::put('/training/{id}', [LattasController::class , 'updateLpkTraining'])->name('lattas.update.training');
    });
