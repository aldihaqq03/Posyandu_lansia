<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class,'login'])->name('login');

Route::post('/', [AuthController::class,'proses_login']);

Route::get('/register',[AuthController::class,'register'])->name('register');

Route::post('/register',[AuthController::class,'proses_register']);

Route::view('/admin', 'admin')->name('admin');

<<<<<<< Updated upstream
Route::resource('lansia', LansiaController::class);

=======
    // Admin routes
    Route::middleware('role:kader')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::view('/pemeriksaan', 'admin.pemeriksaan')->name('pemeriksaan');
        Route::get('/data_lansia', [LansiaController::class, 'index'])->name('data_lansia');
    });

    // Resource Lansia bisa diakses oleh yang punya hak
    Route::resource('lansia', LansiaController::class)->parameters([
        'lansia' => 'lansia'
    ]);
>>>>>>> Stashed changes

//route hanya untuk tes
//route tes sidebar fungsinya
route::view('/tes', 'admin.dashboard');
//route halaman yang di tuju sidebar 
route::view('/dashboard', 'admin.dashboard')->name('dashboard');
route::view('/pemeriksaan', 'admin.pemeriksaan')->name('pemeriksaan');
route::view('/data_lansia', 'admin.data_lansia')->name('data_lansia');