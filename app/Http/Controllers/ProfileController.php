<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * View for Profile completion.
     */
    public function lengkapi(Request $request): View
    {
        $user = $request->user();
        $petugas = $user->petugas;
        return view('auth.lengkapiProfil', compact('user', 'petugas'));
    }

    /**
     * Submit for Profile completion.
     */
    public function lengkapiUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16'],
            'whatsapp' => ['required', 'string', 'max:15'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();
        $user->whatsapp = $request->whatsapp;
        $user->is_active = true;
        $user->save();

        $petugas = $user->petugas;
        if ($petugas) {
             $petugas->nama = $request->nama;
             $petugas->nik = $request->nik;
             
             if ($request->hasFile('foto')) {
                 $path = $request->file('foto')->store('profil_petugas', 'public');
                 $petugas->foto = $path;
             }
             $petugas->save();
        }

        return Redirect::route('dashboard');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
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

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
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
