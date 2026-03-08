<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'login'])->name('login');

Route::post('/', [AuthController::class, 'proses_login']);

Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::post('/register', [AuthController::class, 'proses_register']);


//route controler
Route::resource('lansia', LansiaController::class)->parameters([
    'lansia' => 'lansia'
]);


//route hanya untuk tes
route::view('/scan', view: 'skrining.skrining_utama');
//route tes sidebar fungsinya
route::view('/tes', 'admin.dashboard');
//route halaman yang di tuju sidebar 
route::view('/dashboard', 'admin.dashboard')->name('dashboard');
route::view('/pemeriksaan', 'admin.pemeriksaan')->name('pemeriksaan');
route::get('/data_lansia', [LansiaController::class, 'index'])->name('data_lansia');