<?php

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Lansia;
// use App\Models\Saran;
// use Illuminate\Support\Facades\Auth;

// class MonitoringApiController extends Controller
// {
//     public function index(Request $request)
//     {
//         $user   = Auth::user();
//         $lansia = Lansia::where('id_user', $user->id)->first();

//         if (!$lansia) {
//             return response()->json([
//                 'status'  => 'error',
//                 'message' => 'Data lansia tidak ditemukan',
//             ], 404);
//         }

//         // ── Ambil semua skrining (Kunjungan & Utama) ────────────────────
//         $skrinings = $lansia->skrinings()
//             ->with([
//                 'kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,berat_badan,tinggi_badan,lingkar_perut',
//                 'utama:id_skrining_utama,id_skrining,gula_darah,kolesterol',
//             ])
//             ->orderBy('tanggal_skrining', 'desc')
//             ->get(['id_skrining', 'tanggal_skrining']);

//         $monitoringHistory = $skrinings
//             ->filter(fn($s) => $s->kunjungan || $s->utama)
//             ->map(fn($s) => [
//                 'tanggal'       => $s->tanggal_skrining,
//                 'sistolik'      => $s->kunjungan?->td_sistolik,
//                 'diastolik'     => $s->kunjungan?->td_diastolik,
//                 'berat_badan'   => $s->kunjungan?->berat_badan,
//                 'tinggi_badan'  => $s->kunjungan?->tinggi_badan,
//                 'lingkar_perut' => $s->kunjungan?->lingkar_perut,
//                 'gula_darah'    => $s->utama?->gula_darah,
//                 'kolesterol'    => $s->utama?->kolesterol,
//             ])
//             ->values();

//         $latestData     = $monitoringHistory->first();
//         $skriningLatest = $skrinings->first();

//         // ── Perbaikan Pengambilan Saran ────────────────────
//         // Karena di model Saran relasinya memakai 'id_lansia', kita panggil begini:
//         $saranData = Saran::where('id_lansia', $lansia->id_lansia)
//             ->orderBy('created_at', 'desc')
//             ->get()
//             ->map(fn($s) => [
//                 'id'          => $s->id_saran,
//                 'tanggal'     => $s->created_at->format('Y-m-d'),
//                 'jenis_saran' => $s->jenis_saran,
//                 'isi_saran'   => $s->isi_saran,
//             ])
//             ->values();

//         return response()->json([
//             'status'  => 'success',
//             'message' => 'Data monitoring berhasil diambil',
//             'data'    => [
//                 'lansia' => [
//                     'nama'          => $lansia->nama_lansia,
//                     'nik'           => $lansia->nik,
//                     'jenis_kelamin' => $lansia->jenis_kelamin,
//                     'umur'          => \Carbon\Carbon::parse($lansia->tanggal_lahir)->age,
//                     'tinggi_badan'  => $skriningLatest?->kunjungan?->tinggi_badan,
//                 ],
//                 'latest_monitoring'  => $latestData,
//                 'monitoring_history' => $monitoringHistory,
//                 'saran'              => $saranData,
//             ],
//         ], 200);
//     }
// }

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lansia;
use App\Models\Saran;
use Illuminate\Support\Facades\Auth;

class MonitoringApiController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $lansia = Lansia::where('id_user', $user->id)->first();

        if (!$lansia) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data lansia tidak ditemukan',
            ], 404);
        }

        // ── Ambil semua skrining (Kunjungan & Utama) ────────────────────
        $skrinings = $lansia->skrinings()
            ->with([
                'kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,berat_badan,tinggi_badan,lingkar_perut',
                'utama:id_skrining_utama,id_skrining,gula_darah,kolesterol',
            ])
            ->orderBy('tanggal_skrining', 'desc')
            ->get(['id_skrining', 'tanggal_skrining']);

        $monitoringHistory = $skrinings
            ->filter(fn($s) => $s->kunjungan || $s->utama)
            ->map(fn($s) => [
                'tanggal'       => $s->tanggal_skrining,
                'sistolik'      => $s->kunjungan?->td_sistolik,
                'diastolik'     => $s->kunjungan?->td_diastolik,
                'berat_badan'   => $s->kunjungan?->berat_badan,
                'tinggi_badan'  => $s->kunjungan?->tinggi_badan,
                'lingkar_perut' => $s->kunjungan?->lingkar_perut,
                'gula_darah'    => $s->utama?->gula_darah,
                'kolesterol'    => $s->utama?->kolesterol,
            ])
            ->values();

        // ── PERBAIKAN LOGIKA: Cari data terbaru untuk masing-masing parameter (Independent) ──
        $latestData = [
            'tanggal'       => $monitoringHistory->first()['tanggal'] ?? null,
            'sistolik'      => null,
            'diastolik'     => null,
            'berat_badan'   => null,
            'tinggi_badan'  => null,
            'lingkar_perut' => null,
            'gula_darah'    => null,
            'kolesterol'    => null,
        ];

        // Looping dari riwayat terbaru ke terlama, ambil value yang tidak kosong pertama kali
        foreach ($monitoringHistory as $data) {
            if ($latestData['sistolik'] === null && $data['sistolik'] !== null) {
                $latestData['sistolik'] = $data['sistolik'];
                $latestData['diastolik'] = $data['diastolik'];
            }
            if ($latestData['berat_badan'] === null && $data['berat_badan'] !== null) {
                $latestData['berat_badan'] = $data['berat_badan'];
            }
            if ($latestData['tinggi_badan'] === null && $data['tinggi_badan'] !== null) {
                $latestData['tinggi_badan'] = $data['tinggi_badan'];
            }
            if ($latestData['lingkar_perut'] === null && $data['lingkar_perut'] !== null) {
                $latestData['lingkar_perut'] = $data['lingkar_perut'];
            }
            if ($latestData['gula_darah'] === null && $data['gula_darah'] !== null) {
                $latestData['gula_darah'] = $data['gula_darah'];
            }
            if ($latestData['kolesterol'] === null && $data['kolesterol'] !== null) {
                $latestData['kolesterol'] = $data['kolesterol'];
            }
        }

        // ── Perbaikan Pengambilan Saran ────────────────────
        $saranData = Saran::where('id_lansia', $lansia->id_lansia)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($s) => [
                'id'          => $s->id_saran,
                'tanggal'     => $s->created_at->format('Y-m-d'),
                'jenis_saran' => $s->jenis_saran,
                'isi_saran'   => $s->isi_saran,
            ])
            ->values();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data monitoring berhasil diambil',
            'data'    => [
                'lansia' => [
                    'nama'          => $lansia->nama_lansia,
                    'nik'           => $lansia->nik,
                    'jenis_kelamin' => $lansia->jenis_kelamin,
                    'umur'          => \Carbon\Carbon::parse($lansia->tanggal_lahir)->age,
                    'tinggi_badan'  => $latestData['tinggi_badan'],
                ],
                'latest_monitoring'  => $latestData,
                'monitoring_history' => $monitoringHistory,
                'saran'              => $saranData,
            ],
        ], 200);
    }
}
