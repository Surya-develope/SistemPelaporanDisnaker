<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PentaController;

Route::middleware(['web', \App\Http\Middleware\RoleMiddleware::class . ':admin,penta'])->prefix('penta')->group(function () {
    Route::get('/tenaga-kerja', [PentaController::class , 'tenagaKerja']);
    Route::post('/tenaga-kerja', [PentaController::class , 'storeTenagaKerja'])->name('penta.store.tenaga-kerja');
    Route::get('/tenaga-kerja/export', [PentaController::class , 'exportTenagaKerja'])->name('penta.export.tenaga-kerja');
    Route::get('/tenaga-kerja/template', [PentaController::class , 'templatePencari'])->name('penta.template.pencari');
    
    Route::get('/lowongan', [PentaController::class , 'lowongan']);
    Route::post('/lowongan', [PentaController::class , 'storeLowongan'])->name('penta.store.lowongan');
    Route::get('/lowongan/export', [PentaController::class , 'exportLowongan'])->name('penta.export.lowongan');
    Route::get('/lowongan/template', [PentaController::class , 'templateLowongan'])->name('penta.template.lowongan');
    
    Route::get('/rekap', [PentaController::class , 'rekap']);
    Route::post('/rekap', [PentaController::class , 'storePenempatan'])->name('penta.store.penempatan');
    Route::get('/rekap/export', [PentaController::class , 'exportRekap'])->name('penta.export.rekap');
    Route::get('/rekap/template', [PentaController::class , 'templatePenempatan'])->name('penta.template.penempatan');

    // Import Data Penta
    Route::prefix('import')->group(function () {
            Route::post('/lowongan', [PentaController::class , 'importLowongan'])->name('penta.import.lowongan');
            Route::post('/pencari', [PentaController::class , 'importPencari'])->name('penta.import.pencari');
            Route::post('/penempatan', [PentaController::class , 'importPenempatan'])->name('penta.import.penempatan');
        }
        );

        // Delete Data Penta
        Route::delete('/lowongan/{id}', [PentaController::class , 'destroyLowongan'])->name('penta.destroy.lowongan');
        Route::delete('/pencari/{id}', [PentaController::class , 'destroyPencari'])->name('penta.destroy.pencari');
        Route::delete('/penempatan/{id}', [PentaController::class , 'destroyPenempatan'])->name('penta.destroy.penempatan');

        // Bulk Delete Data Penta
        Route::delete('/lowongan/bulk-delete/all', [PentaController::class , 'bulkDeleteLowongan'])->name('penta.bulk-delete.lowongan');
        Route::delete('/pencari/bulk-delete/all', [PentaController::class , 'bulkDeletePencari'])->name('penta.bulk-delete.pencari');
        Route::delete('/penempatan/bulk-delete/all', [PentaController::class , 'bulkDeletePenempatan'])->name('penta.bulk-delete.penempatan');

        // Update Data Penta
        Route::put('/lowongan/{id}', [PentaController::class , 'updateLowongan'])->name('penta.update.lowongan');
        Route::put('/pencari/{id}', [PentaController::class , 'updatePencari'])->name('penta.update.pencari');
        Route::put('/penempatan/{id}', [PentaController::class , 'updatePenempatan'])->name('penta.update.penempatan');
    });
