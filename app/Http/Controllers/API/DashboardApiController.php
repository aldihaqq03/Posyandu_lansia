<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardApiController extends Controller
{
    public function index(Request $request)
    {

        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan atau belum login'], 401);
        }


        $jadwal_terdekat = DB::table('jadwal_posyandu')
            ->where('tanggal_pelaksanaan', '>=', now()->toDateString())
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->first();


        if (!$jadwal_terdekat) {
            $jadwal_terdekat = DB::table('jadwal_posyandu')
                ->orderBy('tanggal_pelaksanaan', 'desc')
                ->first();
        }

        // Ambil riwayat skrining terakhir untuk lansia ini jika dia lansia
        $skrining_terakhir = null;
        if ($user->lansia) {
            $skrining_terakhir = DB::table('skrining')
                ->join('skrining_utama', 'skrining.id_skrining', '=', 'skrining_utama.id_skrining')
                ->where('skrining.id_lansia', $user->lansia->id_lansia)
                ->orderBy('skrining.tanggal_skrining', 'desc')
                ->select(
                    'skrining_utama.td_sistolik as sistolik',
                    'skrining_utama.td_diastolik as diastolik',
                    'skrining_utama.gula_darah',
                    'skrining_utama.berat_badan',
                    'skrining_utama.tinggi_badan',
                    'skrining_utama.lingkar_perut',
                    'skrining_utama.imt',
                    'skrining_utama.kolesterol'
                )
                ->first();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'jadwal_terdekat' => $jadwal_terdekat,
                'skrining_terakhir' => $skrining_terakhir,
                'nama' => $user->nama
            ]
        ]);
    }
}
