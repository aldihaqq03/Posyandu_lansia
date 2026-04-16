<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Petugas;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:petugas,nik',
            'jabatan' => 'required|in:kepala_kader,kader',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'whatsapp' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 🔥 Buat user dengan is_active = false
        $user = User::create([
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'is_active' => false,
        ]);

        // 🔥 Buat data petugas otomatis
        Petugas::create([
            'id_user' => $user->id,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jabatan' => $request->jabatan,
            'status' => 'pending',
        ]);

        event(new Registered($user));

        Auth::login($user);

        // 🔥 Setelah register, arahkan ke lengkapi profil
        return redirect()->route('profile.lengkapi');
    }
}
