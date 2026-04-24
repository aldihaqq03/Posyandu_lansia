<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\JadwalPosyanduController;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'proses_register'])->name('proses_register');

    Route::view('/', 'welcome')->name('welcome');

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'proses_login'])->name('proses_login');

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
    Route::middleware('role:kader,kepala_kader')->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/pemeriksaan', function () {
            $lansias = \Illuminate\Support\Facades\DB::table('lansia')->get();
            return view('admin.pemeriksaan', compact('lansias'));
        })->name('pemeriksaan');

        Route::get('/skrining_utama', function () {
            $lansias = \Illuminate\Support\Facades\DB::table('lansia')->select('id_lansia', 'nama_lansia', 'nik')->get();
            return view('modal.M_skriningUtama', compact('lansias'));
        })->name('skrining_utama');

        Route::get('/pemeriksaan/create', function () {
            $lansias = \Illuminate\Support\Facades\DB::table('lansia')->select('id_lansia', 'nama_lansia', 'nik')->get();
            return view('modal.M_skriningPPOK', compact('lansias'));
        })->name('pemeriksaan.create');

        Route::get('/data_lansia', [LansiaController::class, 'index'])->name('data_lansia');

        Route::resource('jadwal_posyandu', JadwalPosyanduController::class);

        // Skrining & Pemeriksaan
        Route::post('/pemeriksaan', [\App\Http\Controllers\SkriningController::class, 'storePemeriksaan'])->name('pemeriksaan.store');
        Route::post('/skrining_utama', [\App\Http\Controllers\SkriningController::class, 'storeSkriningUtama'])->name('skrining_utama.store');
        Route::post('/pemeriksaan/ppok', [\App\Http\Controllers\SkriningController::class, 'storeSkriningPPOK'])->name('skrining_ppok.store');

        Route::get('/pengaturan', [\App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan');
        Route::post('/pengaturan/profil', [\App\Http\Controllers\PengaturanController::class, 'updateProfil'])->name('pengaturan.profil');
        Route::post('/pengaturan/password', [\App\Http\Controllers\PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    });


    /*
    |--------------------------------------------------------------------------
    | CRUD PETUGAS
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:kepala_kader')->group(function () {
        Route::get('/data_petugas', [PetugasController::class, 'index'])->name('petugas.index');

        Route::get('/petugas/tambah', [PetugasController::class, 'tambah'])->name('petugas.tambah');

        Route::post('/petugas/store', [PetugasController::class, 'store'])->name('petugas.store');

        Route::get('/petugas/edit/{id}', [PetugasController::class, 'edit'])->name('petugas.edit');

        Route::put('/petugas/update/{id}', [PetugasController::class, 'update'])->name('petugas.update');

        Route::delete('/petugas/hapus/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');

        // Rute Laporan (Hanya Admin)
        Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan');
    });


    /*
    |--------------------------------------------------------------------------
    | Resource Lansia
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:kader,kepala_kader')->group(function () {
        Route::resource('lansia', LansiaController::class)->parameters([
            'lansia' => 'lansia'
        ]);
    });

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