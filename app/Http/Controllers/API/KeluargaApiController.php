<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\Lansia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeluargaApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Find lansia record for this user
        $lansia = Lansia::where('id_user', $user->id)->first();

        if (!$lansia) {
            return response()->json([
                'success' => false,
                'message' => 'Data lansia tidak ditemukan.',
            ], 404);
        }

        $contacts = Keluarga::where('id_lansia', $lansia->id_lansia)->get();

        return response()->json([
            'success' => true,
            'data' => $contacts
        ]);
    }
}
