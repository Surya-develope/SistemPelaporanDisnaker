<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PentaController;

Route::middleware(['web', \App\Http\Middleware\RoleMiddleware::class . ':admin,penta'])->prefix('penta')->group(function () {
    Route::get('/tenaga-kerja', [PentaController::class , 'tenagaKerja']);
    Route::get('/lowongan', [PentaController::class , 'lowongan']);
    Route::get('/rekap', [PentaController::class , 'rekap']);

    // Import Data Penta
    Route::prefix('import')->group(function () {
            Route::get('/', [PentaController::class , 'importIndex'])->name('penta.import');
            Route::post('/lowongan', [PentaController::class , 'importLowongan'])->name('penta.import.lowongan');
            Route::post('/pencari', [PentaController::class , 'importPencari'])->name('penta.import.pencari');
            Route::post('/penempatan', [PentaController::class , 'importPenempatan'])->name('penta.import.penempatan');
        }
        );

        // Delete Data Penta
        Route::delete('/lowongan/{id}', [PentaController::class , 'destroyLowongan'])->name('penta.destroy.lowongan');
        Route::delete('/pencari/{id}', [PentaController::class , 'destroyPencari'])->name('penta.destroy.pencari');
        Route::delete('/penempatan/{id}', [PentaController::class , 'destroyPenempatan'])->name('penta.destroy.penempatan');

        // Update Data Penta
        Route::put('/lowongan/{id}', [PentaController::class , 'updateLowongan'])->name('penta.update.lowongan');
        Route::put('/pencari/{id}', [PentaController::class , 'updatePencari'])->name('penta.update.pencari');
        Route::put('/penempatan/{id}', [PentaController::class , 'updatePenempatan'])->name('penta.update.penempatan');
    });
