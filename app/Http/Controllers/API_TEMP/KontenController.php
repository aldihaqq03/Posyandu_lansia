<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Http\Request;

class KontenController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Menggunakan Model agar accessor full_url otomatis muncul
            $konten = Konten::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $konten
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data konten: ' . $e->getMessage()
            ], 500);
        }
    }

    // Ambil berdasarkan tipe (video/artikel/pamflet)
    public function getByTipe($tipe)
    {
        try {
            $konten = Konten::where('tipe_konten', $tipe)
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