<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LansiaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\JadwalPosyanduController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| REACT ROUTES — pakai middleware 'inertia'
|--------------------------------------------------------------------------
*/

// Welcome page (React)
Route::middleware(['inertia'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome');
    });
});

// Lengkapi profil (React) — auth tapi belum tentu aktif
Route::middleware(['auth', 'inertia'])->group(function () {
    Route::get('/lengkapi-profil', [ProfileController::class, 'lengkapi'])
        ->name('profile.lengkapi');
    Route::post('/lengkapi-profil', [ProfileController::class, 'lengkapiUpdate'])
        ->name('profile.lengkapi.update');
});

/*
|--------------------------------------------------------------------------
| BLADE ROUTES — TANPA middleware inertia
|--------------------------------------------------------------------------
*/

// Auth + sudah aktif
Route::middleware(['auth', 'cek.aktif'])->group(function () {

    // Dashboard
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // Pemeriksaan
    Route::get('/pemeriksaan', function () {
        $lansias = \Illuminate\Support\Facades\DB::table('lansia')->get();
        return view('admin.pemeriksaan', compact('lansias'));
    })->name('pemeriksaan');

    Route::get('/skrining_utama', function () {
        $lansias = \Illuminate\Support\Facades\DB::table('lansia')
            ->select('id_lansia', 'nama_lansia', 'nik')->get();
        return view('modal.M_skriningUtama', compact('lansias'));
    })->name('skrining_utama');

    Route::get('/pemeriksaan/create', function () {
        $lansias = \Illuminate\Support\Facades\DB::table('lansia')
            ->select('id_lansia', 'nama_lansia', 'nik')->get();
        return view('modal.M_skriningPPOK', compact('lansias'));
    })->name('pemeriksaan.create');

    // Data
    Route::get('/data_lansia', [LansiaController::class, 'index'])->name('data_lansia');
    Route::get('/jadwal_posyandu', [JadwalPosyanduController::class, 'index'])->name('jadwal_posyandu');

    // Pengaturan
    Route::get('/pengaturan', [\App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/profil', [\App\Http\Controllers\PengaturanController::class, 'updateProfil'])->name('pengaturan.profil');
    Route::post('/pengaturan/password', [\App\Http\Controllers\PengaturanController::class, 'updatePassword'])->name('pengaturan.password');

    // Resource Lansia
    Route::resource('lansia', LansiaController::class)->parameters(['lansia' => 'lansia']);

    // Skrining
    Route::view('/scan', 'skrining.skrining_utama');

    /*
    |----------------------------------------------------------------------
    | Khusus Kepala Kader
    |----------------------------------------------------------------------
    */
    Route::middleware('role:kepala_kader')->group(function () {
        Route::get('/data_petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::get('/petugas/tambah', [PetugasController::class, 'tambah'])->name('petugas.tambah');
        Route::post('/petugas/store', [PetugasController::class, 'store'])->name('petugas.store');
        Route::get('/petugas/edit/{id}', [PetugasController::class, 'edit'])->name('petugas.edit');
        Route::put('/petugas/update/{id}', [PetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/hapus/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');
        Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan');
    });
});

/*
|--------------------------------------------------------------------------
| Halaman Sukses (Blade)
|--------------------------------------------------------------------------
*/
Route::view('/berhasil', 'simpel.berhasil')->name('berhasil');

/*
|--------------------------------------------------------------------------
| Auth Routes (React login, register, dll) — pakai inertia
|--------------------------------------------------------------------------
*/
Route::middleware(['inertia'])->group(function () {
    require __DIR__.'/auth.php';
});