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
                ->leftJoin('skrining_utama', 'skrining.id_skrining', '=', 'skrining_utama.id_skrining')
                ->leftJoin('skrining_ppok', 'skrining.id_skrining', '=', 'skrining_ppok.id_skrining')
                ->leftJoin('skrining_kunjungan', 'skrining.id_skrining', '=', 'skrining_kunjungan.id_skrining')
                ->where('skrining.id_lansia', $user->lansia->id_lansia)
                ->orderBy('skrining.tanggal_skrining', 'desc')
                ->select(
                    'skrining.tanggal_skrining',
                    DB::raw('COALESCE(skrining_utama.td_sistolik, skrining_ppok.td_sistolik, skrining_kunjungan.td_sistolik) as sistolik'),
                    DB::raw('COALESCE(skrining_utama.td_diastolik, skrining_ppok.td_diastolik, skrining_kunjungan.td_diastolik) as diastolik'),
                    DB::raw('COALESCE(skrining_utama.gula_darah, 0) as gula_darah'),
                    DB::raw('COALESCE(skrining_utama.berat_badan, skrining_ppok.berat_badan, skrining_kunjungan.berat_badan) as berat_badan'),
                    DB::raw('COALESCE(skrining_utama.tinggi_badan, skrining_ppok.tinggi_badan, skrining_kunjungan.tinggi_badan) as tinggi_badan'),
                    DB::raw('COALESCE(skrining_utama.lingkar_perut, skrining_ppok.lingkar_perut, skrining_kunjungan.lingkar_perut) as lingkar_perut'),
                    DB::raw('COALESCE(skrining_utama.imt, skrining_ppok.imt) as imt'),
                    DB::raw('COALESCE(skrining_utama.kolesterol, 0) as kolesterol')
                )
                ->first();
        }

        // Ambil resep obat untuk lansia ini
        $resep_obat = [];
        if ($user->lansia) {
            $resep_obat = DB::table('resep')
                ->join('detail_resep', 'resep.id_resep', '=', 'detail_resep.id_resep')
                ->join('obat', 'detail_resep.id_obat', '=', 'obat.id_obat')
                ->join('skrining', 'resep.id_skrining', '=', 'skrining.id_skrining')
                ->where('skrining.id_lansia', $user->lansia->id_lansia)
                ->select(
                    'obat.nama_obat',
                    'detail_resep.dosis',
                    'detail_resep.frekuensi as aturan_pakai',
                    'detail_resep.keterangan'
                )
                ->orderBy('resep.created_at', 'desc')
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'jadwal_terdekat' => $jadwal_terdekat,
                'skrining_terakhir' => $skrining_terakhir,
                'resep_obat' => $resep_obat,
                'nama' => $user->nama
            ]
        ]);
    }
}
