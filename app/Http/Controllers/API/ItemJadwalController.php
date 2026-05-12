<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemJadwalController extends Controller
{
    public function index()
    {
        try {
            // Ambil data langsung dari tabel item_jadwal_lansia
            $data = DB::table('item_jadwal_lansia')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data Item Jadwal Berhasil Dimuat',
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
}