<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::view('/admin', 'admin')->name('admin');

Route::resource('lansia', LansiaController::class);
