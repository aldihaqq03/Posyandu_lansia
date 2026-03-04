<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'simpel.register')->name('register');

Route::view('/login', 'simpel.login')->name('login');

Route::view('/admin', 'admin')->name('admin');

Route::resource('lansia', LansiaController::class);


//route hanya untuk tes
//route tes sidebar fungsinya
route::view('/tes', 'admin.dashboard');
//route halaman yang di tuju sidebar sementara
route::view('/dashboard', 'admin.dashboard');
route::view('/pemeriksaan', 'admin.pemeriksaan');