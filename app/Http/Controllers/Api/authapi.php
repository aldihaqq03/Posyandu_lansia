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

        // Batasi hanya lansia
        if ($user->jabatan !== 'lansia') {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan untuk aplikasi mobile'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => $user
        ]);
    }
}