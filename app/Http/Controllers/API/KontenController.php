<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KontenController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil semua data dari tabel konten
            $konten = DB::table('konten')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $konten
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data konten'
            ], 500);
        }
    }

    // Optional: ambil berdasarkan tipe (video/artikel/pamflet)
    public function getByTipe($tipe)
    {
        try {
            $konten = DB::table('konten')
                ->where('tipe_konten', $tipe)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $konten
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data konten'
            ], 500);
        }
    }
}