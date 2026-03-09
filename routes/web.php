<?php

use App\Http\Controllers\LansiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'proses_register'])->name('proses_register');

    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'proses_login'])->name('proses_login');
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

    // Route testing
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

// Halaman sukses
Route::view('/berhasil', 'simpel.berhasil')->name('berhasil');