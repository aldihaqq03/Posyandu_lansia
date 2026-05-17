<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lansia;
use Illuminate\Support\Facades\Auth;

class ResepApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Gunakan 'id_user' konsisten dengan MonitoringApiController
        $lansia = Lansia::where('id_user', $user->id)
            ->with([
                'skrinings' => function ($query) {
                    $query->orderBy('tanggal_skrining', 'desc')->limit(1);
                },
                'skrinings.resep.detailResep.obat'
            ])->first();

        if (!$lansia) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data lansia tidak ditemukan',
            ], 404);
        }

        $skriningTerbaru = $lansia->skrinings->first();

        if (!$skriningTerbaru || !$skriningTerbaru->resep) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Belum ada resep',
            ], 404);
        }

        $resepData = [
            'tanggal_resep' => $skriningTerbaru->resep->created_at->format('Y-m-d'),
            'catatan'       => $skriningTerbaru->resep->catatan,
            'obat'          => $skriningTerbaru->resep->detailResep->map(function ($detail) {
                return [
                    'nama_obat'  => $detail->obat->nama_obat,
                    'dosis'      => $detail->dosis,
                    'frekuensi'  => $detail->frekuensi,
                    'keterangan' => $detail->keterangan,
                ];
            })->toArray(),
        ];

        return response()->json([
            'status'  => 'success',
            'message' => 'Data resep berhasil diambil',
            'data'    => $resepData,
        ], 200);
    }
}