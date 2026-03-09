<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetugasController;

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
        Route::get('/data_lansia', [LansiaController::class, 'index'])->name('data_lansia');
        Route::view('/data_petugas', 'admin.petugas.index')->name('data_petugas');
        Route::get('/petugas', [PetugasController::class,'index']);
        Route::get('/petugas/tambah', [PetugasController::class,'tambah']);
        Route::post('/petugas/store', [PetugasController::class,'store']);
    });

    // Resource Lansia bisa diakses oleh yang punya hak
    Route::resource('lansia', LansiaController::class)->parameters([
        'lansia' => 'lansia'
    ]);

    // Route testing
    Route::view('/scan', 'skrining.skrining_utama');
    Route::view('/tes', 'admin.dashboard');
});

// Halaman sukses
Route::view('/berhasil', 'simpel.berhasil')->name('berhasil');