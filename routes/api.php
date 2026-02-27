<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PhiReportController;
use App\Http\Controllers\Api\PkwtReportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // PHI Reports
    Route::get('/phi-reports', [PhiReportController::class, 'index']);
    Route::post('/phi-reports', [PhiReportController::class, 'store']);
    Route::get('/phi-reports/{id}/download', [PhiReportController::class, 'download']);

    // PKWT Reports
    Route::get('/pkwt-reports', [PkwtReportController::class, 'index']);
    Route::post('/pkwt-reports', [PkwtReportController::class, 'store']);
    Route::get('/pkwt-reports/{id}/download', [PkwtReportController::class, 'download']);
});
