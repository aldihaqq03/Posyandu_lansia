<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LansiaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\jadwalPosyanduController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;
use App\Models\Lansia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Data Lansia
    Route::resource('data_lansia', LansiaController::class)->names([
        'index' => 'data_lansia',
        'create' => 'lansia.create',
        'store' => 'lansia.store',
        'show' => 'lansia.show',
        'edit' => 'lansia.edit',
        'update' => 'lansia.update',
        'destroy' => 'lansia.destroy',
    ])->parameters([
                'data_lansia' => 'lansia'
            ]);

    // Profil Lengkap Lansia
    Route::get('/lansia/{lansia}/profil-lengkap', [LansiaController::class, 'profilLengkap'])->name('lansia.profil_lengkap');

    // Data Petugas
    Route::get('/data_petugas', [PetugasController::class, 'index'])->name('data_petugas.index');
    Route::get('/data_petugas/tambah', [PetugasController::class, 'tambah'])->name('data_petugas.tambah');
    Route::post('/data_petugas', [PetugasController::class, 'store'])->name('data_petugas.store');
    Route::get('/data_petugas/edit/{id}', [PetugasController::class, 'edit'])->name('data_petugas.edit');
    Route::put('/data_petugas/update/{id}', [PetugasController::class, 'update'])->name('data_petugas.update');
    Route::delete('/data_petugas/hapus/{id}', [PetugasController::class, 'destroy'])->name('data_petugas.destroy');

    // Jadwal Posyandu
    Route::resource('jadwal_posyandu', jadwalPosyanduController::class)->names([
        'index' => 'jadwal_posyandu.index',
    ])->parameters([
                'jadwal_posyandu' => 'id'
            ]);

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Pemeriksaan & Skrining
    Route::get('/pemeriksaan', function () {
        return view('admin.pemeriksaan', ['lansias' => Lansia::all()]);
    })->name('pemeriksaan');

    Route::get('/skrining_utama', function () {
        return view('modal.M_skriningUtama', ['lansias' => Lansia::all()]);
    })->name('skrining_utama');

    Route::get('/pemeriksaan/create', function () {
        return view('modal.M_skriningPPOK', ['lansias' => Lansia::all()]);
    })->name('pemeriksaan.create');

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan/profil', [PengaturanController::class, 'updateProfil'])->name('pengaturan.profil');
    Route::post('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');

    // Breeze Profile Routes (Optional, keeping as default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
