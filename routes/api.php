<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;

use App\Http\Controllers\Api\ProfilApiController;
use App\Http\Controllers\Api\JadwalApiController;

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/login-lansia', [AuthApiController::class, 'login']); // Aliased just in case

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardApiController::class, 'index']);
    Route::get('/profil', [ProfilApiController::class, 'index']);
    Route::get('/jadwal', [JadwalApiController::class, 'index']);
    Route::post('/logout-lansia', [AuthApiController::class, 'logout']);
});
