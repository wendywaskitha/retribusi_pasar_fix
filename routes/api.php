<?php

use Illuminate\Http\Request;
use Rupadana\ApiService\ApiService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MobileAuthController;
use App\Filament\Resources\PedagangResource\Api\PedagangApiService;
use App\Filament\Resources\RetribusiPembayaranResource\Api\RetribusiPembayaranApiService;
use App\Filament\Resources\RetribusiRealizationReportResource\Api\RetribusiRealizationReportApiService;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// //Mobile
// Route::prefix('mobile')->group(function () {
//     Route::post('login', [MobileAuthController::class, 'login']);

//     Route::middleware('auth:sanctum')->group(function () {
//         // Protected mobile routes will go here
//     });
// });

