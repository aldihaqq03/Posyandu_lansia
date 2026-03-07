<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'simpel.register')->name('register');

Route::view('/login', 'simpel.login')->name('login');

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