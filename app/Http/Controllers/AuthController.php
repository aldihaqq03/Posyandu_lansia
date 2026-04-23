<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Petugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function login(): View
    {
        return view('simpel.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function proses_login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Display the registration view.
     */
    public function register(): View
    {
        return view('simpel.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function proses_register(RegisterRequest $request): RedirectResponse
    {
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