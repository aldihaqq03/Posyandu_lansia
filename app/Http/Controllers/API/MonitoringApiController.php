<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\lansia;
use Illuminate\Support\Facades\Auth;

class MonitoringApiController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();
        
        // Memastikan user adalah lansia atau memiliki data lansia yang terhubung
        $lansia = lansia::where('user_id', $user->id)
            ->with([
                'skrinings' => function($query) {
                    $query->orderBy('tanggal_skrining', 'desc')->take(10); // Ambil 10 data terakhir
                },
                'skrinings.utama',
                'sarans' => function($query) {
                    $query->orderBy('created_at', 'desc')->take(5);
                }
            ])->first();

        if (!$lansia) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data lansia tidak ditemukan',
            ], 404);
        }

        // Format data kesehatan (monitoring)
        $monitoringData = $lansia->skrinings->map(function ($skrining) {
            return [
                'tanggal' => $skrining->tanggal_skrining,
                'sistolik' => $skrining->utama ? $skrining->utama->td_sistolik : null,
                'diastolik' => $skrining->utama ? $skrining->utama->td_diastolik : null,
                'gula_darah' => $skrining->utama ? $skrining->utama->gula_darah : null,
                'kolesterol' => $skrining->utama ? $skrining->utama->kolesterol : null,
            ];
        });

        // Format data saran
        $saranData = $lansia->sarans->map(function ($saran) {
            return [
                'tanggal' => $saran->created_at->format('Y-m-d'),
                'isi_saran' => $saran->isi_saran
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data monitoring kesehatan berhasil diambil',
            'data' => [
                'lansia' => [
                    'nama' => $user->name,
                    'nik' => $lansia->nik,
                ],
                'monitoring' => $monitoringData,
                'saran' => $saranData,
            ]
        ], 200);
    }
}
