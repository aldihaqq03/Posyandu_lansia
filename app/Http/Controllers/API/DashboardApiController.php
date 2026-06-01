<?php

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use App\Models\User;

// class DashboardApiController extends Controller
// {
//     public function index(Request $request)
//     {

//         $user = $request->user();

//         if (!$user) {
//             return response()->json(['success' => false, 'message' => 'User tidak ditemukan atau belum login'], 401);
//         }


//         $jadwal_terdekat = DB::table('jadwal_posyandu')
//             ->where('tanggal_pelaksanaan', '>=', now()->toDateString())
//             ->orderBy('tanggal_pelaksanaan', 'asc')
//             ->first();


//         if (!$jadwal_terdekat) {
//             $jadwal_terdekat = DB::table('jadwal_posyandu')
//                 ->orderBy('tanggal_pelaksanaan', 'desc')
//                 ->first();
//         }

//         // Ambil riwayat skrining terakhir untuk lansia ini jika dia lansia
//         $skrining_terakhir = null;
//         if ($user->lansia) {
//             $skrining_terakhir = DB::table('skrining')
//                 ->leftJoin('skrining_utama', 'skrining.id_skrining', '=', 'skrining_utama.id_skrining')
//                 ->leftJoin('skrining_ppok', 'skrining.id_skrining', '=', 'skrining_ppok.id_skrining')
//                 ->leftJoin('skrining_kunjungan', 'skrining.id_skrining', '=', 'skrining_kunjungan.id_skrining')
//                 ->where('skrining.id_lansia', $user->lansia->id_lansia)
//                 ->orderBy('skrining.tanggal_skrining', 'desc')
//                 ->select(
//                     'skrining.tanggal_skrining',
//                     DB::raw('COALESCE(skrining_utama.td_sistolik, skrining_ppok.td_sistolik, skrining_kunjungan.td_sistolik) as sistolik'),
//                     DB::raw('COALESCE(skrining_utama.td_diastolik, skrining_ppok.td_diastolik, skrining_kunjungan.td_diastolik) as diastolik'),
//                     DB::raw('COALESCE(skrining_utama.gula_darah, 0) as gula_darah'),
//                     DB::raw('COALESCE(skrining_utama.berat_badan, skrining_ppok.berat_badan, skrining_kunjungan.berat_badan) as berat_badan'),
//                     DB::raw('COALESCE(skrining_utama.tinggi_badan, skrining_ppok.tinggi_badan, skrining_kunjungan.tinggi_badan) as tinggi_badan'),
//                     DB::raw('COALESCE(skrining_utama.lingkar_perut, skrining_ppok.lingkar_perut, skrining_kunjungan.lingkar_perut) as lingkar_perut'),
//                     DB::raw('COALESCE(skrining_utama.imt, skrining_ppok.imt) as imt'),
//                     DB::raw('COALESCE(skrining_utama.kolesterol, 0) as kolesterol')
//                 )
//                 ->first();
//         }

//         // Ambil resep obat untuk lansia ini
//         $resep_obat = [];
//         if ($user->lansia) {
//             $resep_obat = DB::table('resep')
//                 ->join('detail_resep', 'resep.id_resep', '=', 'detail_resep.id_resep')
//                 ->join('obat', 'detail_resep.id_obat', '=', 'obat.id_obat')
//                 ->join('skrining', 'resep.id_skrining', '=', 'skrining.id_skrining')
//                 ->where('skrining.id_lansia', $user->lansia->id_lansia)
//                 ->select(
//                     'obat.nama_obat',
//                     'detail_resep.dosis',
//                     'detail_resep.jenis_jadwal',
//                     'detail_resep.frekuensi as aturan_pakai',
//                     'detail_resep.hari_konsumsi',
//                     'detail_resep.durasi_hari',
//                     'detail_resep.jumlah_obat',
//                     'detail_resep.keterangan'
//                 )
//                 ->orderBy('resep.created_at', 'desc')
//                 ->get();
//         }
        
//         if (!empty($resep_obat)) {
//             $resep_obat = $resep_obat->map(function ($item) {
//                 if (!empty($item->hari_konsumsi)) {
//                     $item->hari_konsumsi = json_decode($item->hari_konsumsi);
//                 }
//                 return $item;
//             });
//         }

//         return response()->json([
//             'success' => true,
//             'data' => [
//                 'jadwal_terdekat' => $jadwal_terdekat,
//                 'skrining_terakhir' => $skrining_terakhir,
//                 'resep_obat' => $resep_obat,
//                 'nama' => $user->nama
//             ]
//         ]);
//     }
// }

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan atau belum login'
            ], 401);
        }

        // =========================================================
        // JADWAL POSYANDU
        // =========================================================
        $jadwal_terdekat = DB::table('jadwal_posyandu')
            ->where('tanggal_pelaksanaan', '>=', now()->toDateString())
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->first();

        if (!$jadwal_terdekat) {
            $jadwal_terdekat = DB::table('jadwal_posyandu')
                ->orderBy('tanggal_pelaksanaan', 'desc')
                ->first();
        }

        // =========================================================
        // DEFAULT RESPONSE
        // =========================================================
        $latest_monitoring = null;

        // =========================================================
        // AMBIL DATA LANSIA
        // =========================================================
        if ($user->lansia) {

            $lansia = $user->lansia;

            // Ambil semua skrining (untuk cari latest per parameter)
            $skrinings = DB::table('skrining')
                ->leftJoin('skrining_utama', 'skrining.id_skrining', '=', 'skrining_utama.id_skrining')
                ->leftJoin('skrining_ppok', 'skrining.id_skrining', '=', 'skrining_ppok.id_skrining')
                ->leftJoin('skrining_kunjungan', 'skrining.id_skrining', '=', 'skrining_kunjungan.id_skrining')
                ->where('skrining.id_lansia', $lansia->id_lansia)
                ->orderBy('skrining.tanggal_skrining', 'desc')
                ->select(
                    'skrining.tanggal_skrining',
                    'skrining_utama.td_sistolik',
                    'skrining_utama.td_diastolik',
                    'skrining_utama.gula_darah',
                    'skrining_utama.kolesterol',
                    'skrining_kunjungan.berat_badan',
                    'skrining_kunjungan.tinggi_badan',
                    'skrining_kunjungan.lingkar_perut'
                )
                ->get();

            // =====================================================
            // INIT LATEST PER PARAMETER
            // =====================================================
            $latest = [
                'tanggal'       => null,
                'sistolik'      => null,
                'diastolik'     => null,
                'gula_darah'    => null,
                'kolesterol'    => null,
                'berat_badan'   => null,
                'tinggi_badan'  => null,
                'lingkar_perut' => null,
            ];

            // =====================================================
            // SCAN UNTUK AMBIL LAST PER FIELD
            // =====================================================
            foreach ($skrinings as $s) {

                if ($latest['tanggal'] === null) {
                    $latest['tanggal'] = $s->tanggal_skrining;
                }

                if ($latest['sistolik'] === null && $s->td_sistolik !== null) {
                    $latest['sistolik'] = $s->td_sistolik;
                    $latest['diastolik'] = $s->td_diastolik;
                }

                if ($latest['gula_darah'] === null && $s->gula_darah !== null) {
                    $latest['gula_darah'] = $s->gula_darah;
                }

                if ($latest['kolesterol'] === null && $s->kolesterol !== null) {
                    $latest['kolesterol'] = $s->kolesterol;
                }

                if ($latest['berat_badan'] === null && $s->berat_badan !== null) {
                    $latest['berat_badan'] = $s->berat_badan;
                }

                if ($latest['tinggi_badan'] === null && $s->tinggi_badan !== null) {
                    $latest['tinggi_badan'] = $s->tinggi_badan;
                }

                if ($latest['lingkar_perut'] === null && $s->lingkar_perut !== null) {
                    $latest['lingkar_perut'] = $s->lingkar_perut;
                }
            }

            $latest_monitoring = $latest;
        }

        // =========================================================
        // RESEP OBAT
        // =========================================================
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
                    'detail_resep.jenis_jadwal',
                    'detail_resep.frekuensi as aturan_pakai',
                    'detail_resep.hari_konsumsi',
                    'detail_resep.durasi_hari',
                    'detail_resep.jumlah_obat',
                    'detail_resep.keterangan'
                )
                ->orderBy('resep.created_at', 'desc')
                ->get();
        }

        if (!empty($resep_obat)) {
            $resep_obat = $resep_obat->map(function ($item) {
                if (!empty($item->hari_konsumsi)) {
                    $item->hari_konsumsi = json_decode($item->hari_konsumsi);
                }
                return $item;
            });
        }

        // =========================================================
        // RESPONSE
        // =========================================================
        return response()->json([
            'success' => true,
            'data' => [
                'jadwal_terdekat'   => $jadwal_terdekat,
                'skrining_terakhir' => $latest_monitoring,
                'resep_obat'        => $resep_obat,
                'nama'              => $user->nama
            ]
        ]);
    }
}