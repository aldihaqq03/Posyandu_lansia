<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lansia;
use Illuminate\Support\Facades\Auth;

class MonitoringApiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $lansia = Lansia::where('id_user', $user->id)
            ->with([
                'skrinings' => function ($query) {
                    $query->orderBy('tanggal_skrining', 'desc')
                          ->with([
                              'kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,berat_badan,tinggi_badan,imt,lingkar_perut',
                              'utama:id_skrining_utama,id_skrining,gula_darah,kolesterol',
                          ]);
                },
                'sarans' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])->first();

        if (!$lansia) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data lansia tidak ditemukan',
            ], 404);
        }

        // ── Data pemeriksaan terakhir ─────────────────────────────────
        $latest     = $lansia->skrinings->first();
        $latestData = null;

        if ($latest) {
            $latestData = [
                'tanggal'       => $latest->tanggal_skrining,
                'sistolik'      => $latest->kunjungan?->td_sistolik,
                'diastolik'     => $latest->kunjungan?->td_diastolik,
                'berat_badan'   => $latest->kunjungan?->berat_badan,
                'tinggi_badan'  => $latest->kunjungan?->tinggi_badan,
                'imt'           => $latest->kunjungan?->imt,
                'lingkar_perut' => $latest->kunjungan?->lingkar_perut,
                'gula_darah'    => $latest->utama?->gula_darah,
                'kolesterol'    => $latest->utama?->kolesterol,
            ];
        }

        // ── Riwayat per indikator (tanggal mengikuti data di DB) ─────

        $historyTekananDarah = $lansia->skrinings
            ->filter(fn($s) => $s->kunjungan?->td_sistolik !== null)
            ->map(fn($s) => [
                'tanggal'   => $s->tanggal_skrining,
                'sistolik'  => $s->kunjungan->td_sistolik,
                'diastolik' => $s->kunjungan->td_diastolik,
            ])->values();

        $historyBeratBadan = $lansia->skrinings
            ->filter(fn($s) => $s->kunjungan?->berat_badan !== null)
            ->map(fn($s) => [
                'tanggal'     => $s->tanggal_skrining,
                'berat_badan' => $s->kunjungan->berat_badan,
            ])->values();

        $historyGulaDarah = $lansia->skrinings
            ->filter(fn($s) => $s->utama?->gula_darah !== null)
            ->map(fn($s) => [
                'tanggal'    => $s->tanggal_skrining,
                'gula_darah' => $s->utama->gula_darah,
            ])->values();

        $historyKolesterol = $lansia->skrinings
            ->filter(fn($s) => $s->utama?->kolesterol !== null)
            ->map(fn($s) => [
                'tanggal'    => $s->tanggal_skrining,
                'kolesterol' => $s->utama->kolesterol,
            ])->values();

        // ── Saran ─────────────────────────────────────────────────────
        $saranData = $lansia->sarans->map(fn($s) => [
            'id'          => $s->id_saran,
            'tanggal'     => $s->created_at->format('Y-m-d'),
            'jenis_saran' => $s->jenis_saran,
            'isi_saran'   => $s->isi_saran,
        ])->values();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data monitoring berhasil diambil',
            'data'    => [
                'lansia' => [
                    'nama'          => $lansia->nama_lansia,
                    'nik'           => $lansia->nik,
                    'jenis_kelamin' => $lansia->jenis_kelamin,
                    'umur'          => \Carbon\Carbon::parse($lansia->tanggal_lahir)->age,
                ],
                'latest_monitoring'  => $latestData,
                'monitoring_history' => [
                    'tekanan_darah' => $historyTekananDarah,
                    'berat_badan'   => $historyBeratBadan,
                    'gula_darah'    => $historyGulaDarah,
                    'kolesterol'    => $historyKolesterol,
                ],
                'saran' => $saranData,
            ],
        ], 200);
    }
}