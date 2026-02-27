<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

route::view('/admin', 'admin')->name('admin');
