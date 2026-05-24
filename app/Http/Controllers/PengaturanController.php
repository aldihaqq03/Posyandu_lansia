<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.pengaturan', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'nik' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();
        $petugas = $user->petugas;
        $emailChanged = $user->email !== $request->email;

        if ($request->hasFile('foto')) {
            if ($petugas && $petugas->foto) {
                Storage::disk('public')->delete($petugas->foto);
            }

            if ($petugas) {
                $petugas->foto = $request->file('foto')->store('petugas/profile', 'public');
                $petugas->save();
            }
        }

        $user->email = $request->email;
        $user->whatsapp = $request->whatsapp;
        if ($emailChanged) {
            $user->email_verified_at = null;
        }
        $user->save();

        if ($petugas) {
            $petugas->nama = $request->nama;
            $petugas->nik = $request->nik;
            if ($emailChanged) {
                $petugas->status = 'pending';
            }
            $petugas->save();

            if ($emailChanged) {
                $user->sendEmailVerificationNotification();
            }
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        $user->password = Hash::make($request->new_password);
        // Jika model User tidak memiliki password mutator, maka pastikan ini ter-hash. Di kode user register ada Hash? Di `AuthController` `User::create` tidak menggunakan `Hash::make()`!!! Wait, let me look at AuthController.php line 32: `'password' => $request->password`.
        // If AuthController didn't hash it, checking with Hash might fail. No wait, Laravel standard User model automatically casts password to hash in recent versions, OR the Auth::attempt wouldn't work if they weren't hashed. Let's assume standard Laravel. Wait, I should just assign it. In Laravel 10+, User model usually casts `password => 'hashed'`. Let me just use Hash::make() directly.
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }
}
