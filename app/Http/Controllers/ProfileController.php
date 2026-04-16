<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Tampilkan halaman lengkapi profil (untuk user yang belum aktif)
     */
    public function lengkapi(): Response
    {
        $user = Auth::user();
        $petugas = $user->petugas;

        return Inertia::render('Profile/LengkapiProfil', [
            'user' => $user,
            'petugas' => $petugas,
        ]);
    }

    /**
     * Update profil & set is_active = true
     */
    public function lengkapiUpdate(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'nama'     => 'required|string|max:255',
            'nik'      => 'required|string|size:16|unique:petugas,nik,' . ($user->petugas?->id_petugas ?? 0) . ',id_petugas',
            'whatsapp' => 'required|string|max:15',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update nomor WhatsApp di tabel users
        $user->update(['whatsapp' => $request->whatsapp]);

        $petugasData = [
            'nama'   => $request->nama,
            'nik'    => $request->nik,
            'status' => 'aktif',
            // jabatan TIDAK disentuh — sudah diisi oleh admin sebelumnya
        ];

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_petugas', 'public');
            $petugasData['foto'] = $fotoPath;
        }

        $user->petugas()->updateOrCreate(
            ['id_user' => $user->id],
            $petugasData
        );

        // Set user jadi aktif
        $user->update(['is_active' => true]);

        return Redirect::route('dashboard')->with('success', 'Profil berhasil dilengkapi!');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}