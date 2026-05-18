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
        $user   = Auth::user();
        $lansia = Lansia::where('id_user', $user->id)->first();

        if (!$lansia) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data lansia tidak ditemukan',
            ], 404);
        }

        // Ambil skrining terbaru yang punya resep
        // Gunakan whereHas agar tidak mengambil skrining tanpa resep
        $skrining = $lansia->skrinings()
            ->whereHas('resep')
            ->with(['resep.detailResep.obat'])
            ->orderBy('tanggal_skrining', 'desc')
            ->first(['id_skrining', 'tanggal_skrining']);

        if (!$skrining || !$skrining->resep) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Belum ada resep',
            ], 404);
        }

        $resep = $skrining->resep;

        // Map semua detail obat — bisa lebih dari satu
        $obatList = $resep->detailResep->map(fn($detail) => [
            'nama_obat'  => $detail->obat?->nama_obat ?? '-',
            'dosis'      => $detail->dosis,
            'frekuensi'  => $detail->frekuensi,
            'keterangan' => $detail->keterangan,
        ])->values()->toArray();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data resep berhasil diambil',
            'data'    => [
                'tanggal_resep'  => $skrining->tanggal_skrining,
                'catatan'        => $resep->catatan,
                'jumlah_obat'    => count($obatList),
                'obat'           => $obatList,
            ],
        ], 200);
    }
}