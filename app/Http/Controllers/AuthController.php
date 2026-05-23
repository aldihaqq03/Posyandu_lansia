<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Petugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function login(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function proses_login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = auth()->user();

        if ($user && $user->jabatan === 'lansia') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->withErrors(['email' => 'Akun lansia hanya dapat diakses melalui aplikasi mobile.']);
        }

        if (!$user || !$user->petugas || !$user->email_verified_at || $user->petugas->status !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->withErrors(['email' => 'Email belum diverifikasi.']);
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Display the registration view.
     */
    public function register(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function proses_register(RegisterRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'password' => $request->password,
            ]);

            Petugas::create([
                'id_user' => $user->id,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'jabatan' => $request->jabatan,
                'status' => 'pending',
            ]);

            $user->sendEmailVerificationNotification();
        });

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan masuk.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }






}