<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\JadwalPosyanduController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\SkriningController;
use App\Http\Controllers\SaranController;


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
    Route::post('/test-notification', [\App\Http\Controllers\DashboardController::class, 'testNotification'])->name('test.notification');

    // ========================
    // LANSIA (FIXED)
    // ========================
    Route::get('/data_lansia', [LansiaController::class, 'index'])->name('data_lansia');

    Route::prefix('lansia')->name('lansia.')->group(function () {
        Route::get('/check-unique', [LansiaController::class, 'checkUnique'])->name('check-unique');
        Route::get('/{lansia}/histori-skrining', [LansiaController::class, 'historiSkrining'])->name('histori');
        Route::get('/{lansia}/monitoring', [LansiaController::class, 'monitoring'])->name('monitoring');
        Route::get('/{lansia}/health-summary', [LansiaController::class, 'healthSummary'])->name('health-summary');
        Route::get('/{lansia}/keluarga', [LansiaController::class, 'getKeluarga'])->name('keluarga');
        Route::get('/{lansia}/skrining-utama/{id_skrining}', [LansiaController::class, 'detailSkriningUtama'])->name('detail-utama');
        Route::get('/{lansia}/skrining-ppok/{id_skrining}', [LansiaController::class, 'detailSkriningPPOK'])->name('detail-ppok');
        Route::get('/{lansia}/health-history', [LansiaController::class, 'healthHistory'])->name('health-history');
        Route::get('/{lansia}/keluhan-history', [LansiaController::class, 'keluhanHistory'])->name('keluhan-history');
        
        // ─── SARAN ──────────────────────────────────────────────
        Route::get('/{lansia}/saran', [SaranController::class, 'index'])->name('saran.index');
        Route::post('/{lansia}/saran', [SaranController::class, 'store'])->name('saran.store');
        Route::put('/{lansia}/saran/{saran}', [SaranController::class, 'update'])->name('saran.update');
        Route::delete('/{lansia}/saran/{saran}', [SaranController::class, 'destroy'])->name('saran.destroy');
    });

    Route::resource('lansia', LansiaController::class)
        ->parameters(['lansia' => 'lansia'])
        ->except(['show']); // ❗ penting

    // ========================
    // LAINNYA
    // ========================
    Route::get('/obat/{id}/mutasi-stok', [ObatController::class, 'mutasiStokApi'])->name('obat.mutasi_stok_api');
    Route::post('/obat/{obat}/restock', [ObatController::class, 'restock'])->name('obat.restock');
    Route::resource('obat', ObatController::class);
    Route::patch('jadwal_posyandu/{id}/selesai', [JadwalPosyanduController::class, 'selesai'])
    ->name('jadwal_posyandu.selesai');
    Route::resource('jadwal_posyandu', JadwalPosyanduController::class);

    Route::get('/skrining', [SkriningController::class, 'index'])->name('skrining.index');
    Route::post('/skrining', [SkriningController::class, 'store'])->name('skrining.store');

    Route::get('/pengaturan', [\App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/profil', [\App\Http\Controllers\PengaturanController::class, 'updateProfil'])->name('pengaturan.profil');
    Route::post('/pengaturan/password', [\App\Http\Controllers\PengaturanController::class, 'updatePassword'])->name('pengaturan.password');

    // ========================
    // KONTEN
    // ========================
    Route::resource('konten', \App\Http\Controllers\KontenController::class);

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
        Route::get('/laporan/detail/{id}', [\App\Http\Controllers\LaporanController::class, 'detail']);
    });


    /*
    |--------------------------------------------------------------------------
    | Resource Lansia
    |--------------------------------------------------------------------------
    
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

Route::get('/set-telegram-webhook', function () {
    $token = env('TELEGRAM_BOT_TOKEN');
    $url = url('/api/telegram/webhook');
    
    if (!$token) {
        return "TELEGRAM_BOT_TOKEN belum diset di .env";
    }

    $response = Illuminate\Support\Facades\Http::get("https://api.telegram.org/bot{$token}/setWebhook?url={$url}");
    
    return $response->json();
});