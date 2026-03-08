<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class,'login'])->name('login');

Route::post('/', [AuthController::class,'proses_login']);

Route::get('/register',[AuthController::class,'register'])->name('register');

Route::post('/register',[AuthController::class,'proses_register']);

Route::view('/berhasil', 'simpel.berhasil')->name('berhasil');

Route::view('/admin', 'admin')->name('admin');

Route::resource('lansia', LansiaController::class);


//route hanya untuk tes
//route tes sidebar fungsinya
route::view('/tes', 'admin.dashboard');
//route halaman yang di tuju sidebar 
route::view('/dashboard', 'admin.dashboard')->name('dashboard');
route::view('/pemeriksaan', 'admin.pemeriksaan')->name('pemeriksaan');
route::view('/data_lansia', 'admin.data_lansia')->name('data_lansia');