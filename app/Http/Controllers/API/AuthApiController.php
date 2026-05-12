<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'no_hp' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('whatsapp', $request->no_hp)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP atau password salah'
            ], 401);
        }

        // hanya lansia boleh login mobile
        if ($user->jabatan !== 'lansia') {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan untuk aplikasi mobile'
            ], 403);
        }

        // Buat token sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'whatsapp' => $user->whatsapp,
                'jabatan' => $user->jabatan,
                'nama' => $user->nama
            ],
            'lansia' => $user->lansia
        ]);
    }

    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string'
        ]);

        $request->user()->update([
            'fcm_token' => $request->fcm_token
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FCM Token updated successfully'
        ]);
    }
}