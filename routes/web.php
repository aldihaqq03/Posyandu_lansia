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


    /*
    |--------------------------------------------------------------------------
    | Admin / Kader Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:kader')->group(function () {

        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

        Route::view('/pemeriksaan', 'admin.pemeriksaan')->name('pemeriksaan');

        Route::get('/data_lansia', [LansiaController::class, 'index'])->name('data_lansia');

    });


    /*
    |--------------------------------------------------------------------------
    | CRUD PETUGAS
    |--------------------------------------------------------------------------
    */

    Route::get('/data_petugas', [PetugasController::class, 'index'])->name('petugas.index');

    Route::get('/petugas/tambah', [PetugasController::class, 'tambah'])->name('petugas.tambah');

    Route::post('/petugas/store', [PetugasController::class, 'store'])->name('petugas.store');

    Route::get('/petugas/edit/{id}', [PetugasController::class, 'edit'])->name('petugas.edit');

    Route::put('/petugas/update/{id}', [PetugasController::class, 'update'])->name('petugas.update');

    Route::delete('/petugas/hapus/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');


    /*
    |--------------------------------------------------------------------------
    | Resource Lansia
    |--------------------------------------------------------------------------
    */

    Route::resource('lansia', LansiaController::class)->parameters([
        'lansia' => 'lansia'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    */

    Route::view('/scan', 'skrining.skrining_utama');

    Route::view('/tes', 'admin.dashboard');

});


/*
|--------------------------------------------------------------------------
| Halaman Sukses
|--------------------------------------------------------------------------
*/

Route::view('/berhasil', 'simpel.berhasil')->name('berhasil');