<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkriningApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->lansia) {
            return response()->json(['success' => false, 'message' => 'Lansia tidak ditemukan'], 404);
        }

        $skrinings = DB::table('skrining')
            ->join('skrining_utama', 'skrining.id_skrining', '=', 'skrining_utama.id_skrining')
            ->where('skrining.id_lansia', $user->lansia->id_lansia)
            ->orderBy('skrining.tanggal_skrining', 'desc')
            ->select(
                'skrining.id_skrining',
                'skrining.tanggal_skrining',
                'skrining.keluhan',
                'skrining_utama.td_sistolik as sistolik',
                'skrining_utama.td_diastolik as diastolik',
                'skrining_utama.gula_darah',
                'skrining_utama.berat_badan',
                'skrining_utama.tinggi_badan',
                'skrining_utama.imt',
                'skrining_utama.kolesterol',
                'skrining_utama.lingkar_perut'
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $skrinings
        ]);
    }
}
