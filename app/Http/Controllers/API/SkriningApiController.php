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
            ->leftJoin('skrining_utama', 'skrining.id_skrining', '=', 'skrining_utama.id_skrining')
            ->leftJoin('skrining_ppok', 'skrining.id_skrining', '=', 'skrining_ppok.id_skrining')
            ->leftJoin('skrining_kunjungan', 'skrining.id_skrining', '=', 'skrining_kunjungan.id_skrining')
            ->where('skrining.id_lansia', $user->lansia->id_lansia)
            ->orderBy('skrining.tanggal_skrining', 'desc')
            ->select(
                'skrining.id_skrining',
                'skrining.tanggal_skrining',
                'skrining.keluhan',
                DB::raw('COALESCE(skrining_utama.td_sistolik, skrining_ppok.td_sistolik, skrining_kunjungan.td_sistolik) as sistolik'),
                DB::raw('COALESCE(skrining_utama.td_diastolik, skrining_ppok.td_diastolik, skrining_kunjungan.td_diastolik) as diastolik'),
                DB::raw('COALESCE(skrining_utama.gula_darah, 0) as gula_darah'),
                DB::raw('COALESCE(skrining_utama.berat_badan, skrining_ppok.berat_badan, skrining_kunjungan.berat_badan) as berat_badan'),
                DB::raw('COALESCE(skrining_utama.tinggi_badan, skrining_ppok.tinggi_badan, skrining_kunjungan.tinggi_badan) as tinggi_badan'),
                DB::raw('COALESCE(skrining_utama.imt, skrining_ppok.imt) as imt'),
                DB::raw('COALESCE(skrining_utama.kolesterol, 0) as kolesterol'),
                DB::raw('COALESCE(skrining_utama.lingkar_perut, skrining_ppok.lingkar_perut, skrining_kunjungan.lingkar_perut) as lingkar_perut')
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $skrinings
        ]);
    }
}
