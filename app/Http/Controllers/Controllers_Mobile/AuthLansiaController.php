<?php

namespace App\Http\Controllers\Controllers_Mobile; // Sesuaikan dengan folder baru kamu

use App\Http\Controllers\Controller; // Wajib ada karena sekarang beda folder
use App\Models\Lansia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthLansiaController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'no_hp'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari di tabel lansia pakai no_hp
        $lansia = Lansia::where('no_hp', $request->no_hp)->first();

        if (!$lansia) {
            return response()->json(['message' => 'Nomor HP tidak ditemukan'], 401);
        }

        // Cek password 
        // Kalau password di DB masih plain text (bukan hash), pakai ini:
        if ($request->password !== $lansia->password) {
             return response()->json(['message' => 'Kata sandi salah'], 401);
        }
        
        // Kalau nanti sudah pakai Hash (bcrypt), gunakan yang bawah ini:
        // if (!Hash::check($request->password, $lansia->password)) {
        //     return response()->json(['message' => 'Kata sandi salah'], 401);
        // }

        // Buat token (Pastikan model Lansia sudah pakai trait HasApiTokens)
        $token = $lansia->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'lansia'  => $lansia,   // seluruh data lansia dikirim ke Flutter
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
}