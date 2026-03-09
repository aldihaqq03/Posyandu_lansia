<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register()
    {
        return view('simpel.register');
    }

    public function login()
    {
        return view('simpel.login'); // pastikan ada file resources/views/simpel/login.blade.php
    }

    public function proses_register(Request $request)
    {
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'whatsapp' => $request->whatsapp,
            'jabatan' => $request->jabatan,
            'wilayah_kerja' => $request->wilayah_kerja,
            'password' => $request->password
        ]);

        return redirect('/login');
    }

<<<<<<< HEAD
<<<<<<< Updated upstream
=======





>>>>>>> Stashed changes
=======

    public function proses_login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate(); // penting untuk keamanan

            // Redirect sesuai jabatan
            $jabatan = Auth::user()->jabatan;

            if ($jabatan == 'kader' || $jabatan == 'KepalaKader') {
                return redirect('/admin/dashboard');
            }

            // default jika jabatan tidak terdeteksi
            return redirect('/dashboard');
        }

        return back()->with('error', 'Email atau Password salah');
    }
    public function logout(Request $request)
    {
        // Logout user
        Auth::logout();

        // Hapus session Laravel
        $request->session()->invalidate();

        // Buat token CSRF baru
        $request->session()->regenerateToken();

        // Hapus cookie laravel_session (opsional)
        return redirect('/login')->withCookie(cookie()->forget('laravel_session'));
    }
>>>>>>> main
}