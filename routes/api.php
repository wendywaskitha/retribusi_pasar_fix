<?php

use Illuminate\Http\Request;
use Rupadana\ApiService\ApiService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
// Route::middleware(['auth:sanctum'])->group(function () {
//     ApiService::routes();

//     // Custom summary endpoint for RetribusiRealizationReport
//     Route::get('/retribusi-realization-report/summary', [RetribusiRealizationReportApiService::class, 'getSummary']);

//     // Logout route
//     Route::post('/logout', [AuthController::class, 'logout']);

//     // Get current user data
//     Route::get('/user', [AuthController::class, 'user']);
// });
