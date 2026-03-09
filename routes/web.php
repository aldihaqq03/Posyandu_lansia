<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'proses_register'])->name('proses_register');

    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'proses_login'])->name('proses_login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard umum
   // Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // Admin routes
    Route::middleware('role:kader')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::view('/pemeriksaan', 'admin.pemeriksaan')->name('pemeriksaan');
        Route::view('/data_lansia', 'admin.data_lansia')->name('data_lansia');
    });

    // Resource Lansia bisa diakses oleh yang punya hak
    Route::resource('lansia', LansiaController::class);

    // Route testing
    Route::view('/scan', 'skrining.skrining_utama');
    Route::view('/tes', 'admin.dashboard');
});

// Halaman sukses
Route::view('/berhasil', 'simpel.berhasil')->name('berhasil');