<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'simpel.register')->name('register');

Route::view('/login', 'simpel.login')->name('login');

Route::view('/admin', 'admin')->name('admin');

Route::resource('lansia', LansiaController::class);
