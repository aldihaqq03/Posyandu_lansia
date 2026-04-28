<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 401);
        }

        // Ambil semua jadwal posyandu
        $jadwal = DB::table('jadwal_posyandu')
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }
}
