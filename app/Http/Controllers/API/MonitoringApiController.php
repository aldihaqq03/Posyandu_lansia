<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lansia;
use App\Models\Saran;
use App\Services\HealthRiskAssessor;
use Illuminate\Support\Facades\Auth;

class MonitoringApiController extends Controller
{
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

//         // ── Cari data terbaru per parameter (Independent) ──────────────
//         $latestData = [
//             'tanggal'       => $monitoringHistory->first()['tanggal'] ?? null,
//             'sistolik'      => null,
//             'diastolik'     => null,
//             'berat_badan'   => null,
//             'tinggi_badan'  => null,
//             'lingkar_perut' => null,
//             'gula_darah'    => null,
//             'kolesterol'    => null,
//         ];

//         foreach ($monitoringHistory as $data) {
//             if ($latestData['sistolik'] === null && $data['sistolik'] !== null) {
//                 $latestData['sistolik']  = $data['sistolik'];
//                 $latestData['diastolik'] = $data['diastolik'];
//             }
//             if ($latestData['berat_badan'] === null && $data['berat_badan'] !== null) {
//                 $latestData['berat_badan'] = $data['berat_badan'];
//             }
//             if ($latestData['tinggi_badan'] === null && $data['tinggi_badan'] !== null) {
//                 $latestData['tinggi_badan'] = $data['tinggi_badan'];
//             }
//             if ($latestData['lingkar_perut'] === null && $data['lingkar_perut'] !== null) {
//                 $latestData['lingkar_perut'] = $data['lingkar_perut'];
//             }
//             if ($latestData['gula_darah'] === null && $data['gula_darah'] !== null) {
//                 $latestData['gula_darah'] = $data['gula_darah'];
//             }
//             if ($latestData['kolesterol'] === null && $data['kolesterol'] !== null) {
//                 $latestData['kolesterol'] = $data['kolesterol'];
//             }
//         }

//         // ── Hitung status per parameter via HealthRiskAssessor ──────────
//         $assessorData = [
//             'sistolik'      => $latestData['sistolik'],
//             'diastolik'     => $latestData['diastolik'],
//             'gula_darah'    => $latestData['gula_darah'],
//             'kolesterol'    => $latestData['kolesterol'],
//             'imt'           => null,
//             'lingkar_perut' => $latestData['lingkar_perut'],
//             'jenis_kelamin' => $lansia->jenis_kelamin,
//         ];

//         $details = HealthRiskAssessor::detail($assessorData);

//         $parameters = [];
//         foreach (['sistolik', 'diastolik', 'gula_darah', 'kolesterol', 'lingkar_perut'] as $param) {
//             $parameters[$param] = [
//                 'nilai'  => $latestData[$param],
//                 'status' => $details[$param]['status'] ?? null,
//                 'label'  => $details[$param]['label'] ?? '-',
//             ];
//         }

//         $latestData['parameter'] = $parameters;

//         // ── Tambahkan parameter per baris di monitoring_history ─────────
//         $jenisKelamin = $lansia->jenis_kelamin;
//         $monitoringHistory = $monitoringHistory->map(function ($row) use ($jenisKelamin) {
//             $rowAssessor = [
//                 'sistolik'      => $row['sistolik'],
//                 'diastolik'     => $row['diastolik'],
//                 'gula_darah'    => $row['gula_darah'],
//                 'kolesterol'    => $row['kolesterol'],
//                 'imt'           => null,
//                 'lingkar_perut' => $row['lingkar_perut'],
//                 'jenis_kelamin' => $jenisKelamin,
//             ];

//             $rowDetails = HealthRiskAssessor::detail($rowAssessor);

//             $rowParameters = [];
//             foreach (['sistolik', 'diastolik', 'gula_darah', 'kolesterol', 'lingkar_perut'] as $param) {
//                 $rowParameters[$param] = [
//                     'nilai'  => $row[$param],
//                     'status' => $rowDetails[$param]['status'] ?? null,
//                     'label'  => $rowDetails[$param]['label'] ?? '-',
//                 ];
//             }

//             $row['parameter'] = $rowParameters;
//             return $row;
//         })->values();

//         // ── Saran ───────────────────────────────────────────────────────
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
//                     'tinggi_badan'  => $latestData['tinggi_badan'],
//                 ],
//                 'latest_monitoring'  => $latestData,
//                 'monitoring_history' => $monitoringHistory,
//                 'saran'              => $saranData,
//             ],
//         ], 200);
//     }
// }
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

    $page         = max(1, (int) $request->query('page', 1));
    $perPage      = min(50, max(1, (int) $request->query('per_page', 10)));
    $jenisKelamin = $lansia->jenis_kelamin;

    // ── Ambil semua skrining ─────────────────────────────────────────
    $skrinings = $lansia->skrinings()
        ->with([
            'kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,berat_badan,tinggi_badan,lingkar_perut',
            'utama:id_skrining_utama,id_skrining,gula_darah,kolesterol',
        ])
        ->orderBy('tanggal_skrining', 'desc')
        ->get(['id_skrining', 'tanggal_skrining']);

    // ── Builder row (reusable closure) ───────────────────────────────
    $buildRow = function ($s) use ($jenisKelamin) {
        $row = [
            'tanggal'       => $s->tanggal_skrining,
            'sistolik'      => $s->kunjungan?->td_sistolik,
            'diastolik'     => $s->kunjungan?->td_diastolik,
            'berat_badan'   => $s->kunjungan?->berat_badan,
            'tinggi_badan'  => $s->kunjungan?->tinggi_badan,
            'lingkar_perut' => $s->kunjungan?->lingkar_perut,
            'gula_darah'    => $s->utama?->gula_darah,
            'kolesterol'    => $s->utama?->kolesterol,
        ];

        $details = HealthRiskAssessor::detail([
            'sistolik'      => $row['sistolik'],
            'diastolik'     => $row['diastolik'],
            'gula_darah'    => $row['gula_darah'],
            'kolesterol'    => $row['kolesterol'],
            'imt'           => null,
            'lingkar_perut' => $row['lingkar_perut'],
            'jenis_kelamin' => $jenisKelamin,
        ]);

        $row['parameter'] = collect(['sistolik', 'diastolik', 'gula_darah', 'kolesterol', 'lingkar_perut'])
            ->mapWithKeys(fn($p) => [$p => [
                'nilai'  => $row[$p],
                'status' => $details[$p]['status'] ?? null,
                'label'  => $details[$p]['label'] ?? '-',
            ]])->all();

        return $row;
    };

    // ── Bangun full history ──────────────────────────────────────────
    $allHistory = $skrinings
        ->filter(fn($s) => $s->kunjungan || $s->utama)
        ->map($buildRow)
        ->values();

    $totalItems = $allHistory->count();
    $totalPages = (int) ceil($totalItems / $perPage);
    $offset     = ($page - 1) * $perPage;

    // ── Paginated history (untuk list) ───────────────────────────────
    $pagedHistory = $allHistory->slice($offset, $perPage)->values();

    // ── Latest 6 (SELALU dari awal, TIDAK kena pagination) ───────────
    $latest6 = $allHistory->take(6)->values();

    // ── Latest data per parameter ────────────────────────────────────
    $latestData = [
        'tanggal'       => $allHistory->first()['tanggal'] ?? null,
        'sistolik'      => null, 'diastolik'     => null,
        'berat_badan'   => null, 'tinggi_badan'  => null,
        'lingkar_perut' => null, 'gula_darah'    => null,
        'kolesterol'    => null,
    ];

    foreach ($allHistory as $data) {
        if ($latestData['sistolik']      === null && $data['sistolik']      !== null) {
            $latestData['sistolik']  = $data['sistolik'];
            $latestData['diastolik'] = $data['diastolik'];
        }
        if ($latestData['berat_badan']   === null && $data['berat_badan']   !== null) $latestData['berat_badan']   = $data['berat_badan'];
        if ($latestData['tinggi_badan']  === null && $data['tinggi_badan']  !== null) $latestData['tinggi_badan']  = $data['tinggi_badan'];
        if ($latestData['lingkar_perut'] === null && $data['lingkar_perut'] !== null) $latestData['lingkar_perut'] = $data['lingkar_perut'];
        if ($latestData['gula_darah']    === null && $data['gula_darah']    !== null) $latestData['gula_darah']    = $data['gula_darah'];
        if ($latestData['kolesterol']    === null && $data['kolesterol']    !== null) $latestData['kolesterol']    = $data['kolesterol'];
    }

    // ── Status parameter latest ──────────────────────────────────────
    $details    = HealthRiskAssessor::detail([
        'sistolik'      => $latestData['sistolik'],
        'diastolik'     => $latestData['diastolik'],
        'gula_darah'    => $latestData['gula_darah'],
        'kolesterol'    => $latestData['kolesterol'],
        'imt'           => null,
        'lingkar_perut' => $latestData['lingkar_perut'],
        'jenis_kelamin' => $jenisKelamin,
    ]);

    $latestData['parameter'] = collect(['sistolik', 'diastolik', 'gula_darah', 'kolesterol', 'lingkar_perut'])
        ->mapWithKeys(fn($p) => [$p => [
            'nilai'  => $latestData[$p],
            'status' => $details[$p]['status'] ?? null,
            'label'  => $details[$p]['label'] ?? '-',
        ]])->all();

    // ── Saran ────────────────────────────────────────────────────────
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
            'latest_monitoring'   => $latestData,

            // ✅ Selalu 6 data terbaru — tidak kena pagination, untuk chart
            'latest_6_history'    => $latest6,

            // ✅ Paginated — untuk list riwayat
            'monitoring_history'  => $pagedHistory,

            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total_items'  => $totalItems,
                'total_pages'  => $totalPages,
                'has_next'     => $page < $totalPages,
            ],
            'saran' => $saranData,
        ],
    ], 200);
}
}