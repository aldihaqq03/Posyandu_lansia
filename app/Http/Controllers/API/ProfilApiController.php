<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->lansia) {
            return response()->json(['success' => false, 'message' => 'Profil lansia tidak ditemukan'], 404);
        }

        $lansia = DB::table('lansia')->where('id_lansia', $user->lansia->id_lansia)->first();

        return response()->json([
            'success' => true,
            'data' => $lansia
        ]);
    }
}
