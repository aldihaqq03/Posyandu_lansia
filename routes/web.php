<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\JadwalPosyanduController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\SkriningController;

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


    // Admin routes
    Route::middleware('role:Kader,Admin')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::view('/pemeriksaan', 'admin.pemeriksaan')->name('pemeriksaan');
        Route::view('/data_lansia', 'admin.data_lansia')->name('data_lansia');
        Route::view('/profil', 'admin.profil')->name('profil')->name('profil');
    });

    // Resource Lansia
    Route::resource('lansia', LansiaController::class);


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
        Route::get('/{lansia}/histori-skrining', [LansiaController::class, 'historiSkrining'])->name('histori');
        Route::get('/{lansia}/health-summary', [LansiaController::class, 'healthSummary'])->name('health-summary');
        Route::get('/{lansia}/keluarga', [LansiaController::class, 'getKeluarga'])->name('keluarga');
        Route::get('/{lansia}/skrining-utama/{id_skrining}', [LansiaController::class, 'detailSkriningUtama'])->name('detail-utama');
        Route::get('/{lansia}/skrining-ppok/{id_skrining}', [LansiaController::class, 'detailSkriningPPOK'])->name('detail-ppok');
    });

    Route::resource('lansia', LansiaController::class)
        ->parameters(['lansia' => 'lansia'])
        ->except(['show']); // ❗ penting

    // ========================
    // LAINNYA
    // ========================
    Route::resource('obat', ObatController::class);
    Route::resource('jadwal_posyandu', JadwalPosyanduController::class);

    Route::get('/skrining', [SkriningController::class, 'index'])->name('skrining.index');
    Route::post('/skrining', [SkriningController::class, 'store'])->name('skrining.store');

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
    
    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    */

    Route::view('/scan', 'skrining.skrining_utama');
    Route::view('/tes', 'admin.dashboard');


    // Simpan perubahan profil
    Route::put('/profil', function (Request $request) {

        // Ambil user langsung dari model Eloquent biar save() tidak error
        $user = User::find(Auth::id());

        $rules = [
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'nik'              => 'nullable|digits:16',
            'jabatan'          => 'nullable|string|max:100',
            'wilayah_posyandu' => 'nullable|string|max:255',
            'no_whatsapp'      => 'nullable|string|max:20',
            'avatar'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'confirmed|min:8';
        }

        $request->validate($rules);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->name             = $request->name;
        $user->email            = $request->email;
        $user->nik              = $request->nik;
        $user->jabatan          = $request->jabatan;
        $user->wilayah_posyandu = $request->wilayah_posyandu;
        $user->no_whatsapp      = $request->no_whatsapp;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/profil')->with('success', 'Profil berhasil diperbarui!');

    })->name('profil.update');


});


/*
|--------------------------------------------------------------------------
| Halaman Sukses
|--------------------------------------------------------------------------
*/

Route::view('/berhasil', 'simpel.berhasil')->name('berhasil');