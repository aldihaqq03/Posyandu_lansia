<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controllers_Mobile\AuthLansiaController;

Route::post('/login-lansia', [AuthLansiaController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout-lansia', [AuthLansiaController::class, 'logout']);
});