<?php
<<<<<<< Updated upstream
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;

use App\Http\Controllers\Api\ProfilApiController;
use App\Http\Controllers\Api\JadwalApiController;

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/login-lansia', [AuthApiController::class, 'login']); // Aliased just in case
=======

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ProfilApiController;
use App\Http\Controllers\Api\JadwalApiController;
use App\Http\Controllers\Api\ItemJadwalController;

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/login-lansia', [AuthApiController::class, 'login']); 
>>>>>>> Stashed changes

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardApiController::class, 'index']);
    Route::get('/profil', [ProfilApiController::class, 'index']);
    Route::get('/jadwal', [JadwalApiController::class, 'index']);
<<<<<<< Updated upstream
    Route::post('/logout-lansia', [AuthApiController::class, 'logout']);
});
=======
    Route::get('/item-jadwal', [ItemJadwalController::class, 'index']);
    Route::post('/logout-lansia', [AuthApiController::class, 'logout']);
});
>>>>>>> Stashed changes
