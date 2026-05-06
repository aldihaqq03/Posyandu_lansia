<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ProfilApiController;
use App\Http\Controllers\Api\JadwalApiController;
use App\Http\Controllers\Api\ItemJadwalController;
use App\Http\Controllers\Api\EmergencyApiController;

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/login-lansia', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardApiController::class, 'index']);
    Route::get('/profil', [ProfilApiController::class, 'index']);
    Route::get('/jadwal', [JadwalApiController::class, 'index']);
    Route::get('/item-jadwal', [ItemJadwalController::class, 'index']);
    Route::get('/kontak-keluarga', [\App\Http\Controllers\Api\KeluargaApiController::class, 'index']);
    Route::get('/skrining', [\App\Http\Controllers\Api\SkriningApiController::class, 'index']);
    Route::post('/update-password', [ProfilApiController::class, 'updatePassword']);
    Route::post('/update-fcm-token', [AuthApiController::class, 'updateFcmToken']);
    Route::post('/logout-lansia', [AuthApiController::class, 'logout']);
    Route::post('/emergency-alert', [EmergencyApiController::class, 'sendAlert']);
});

// Endpoint untuk Telegram Webhook
Route::post('/telegram/webhook', [\App\Http\Controllers\API\TelegramWebhookController::class, 'handle']);
