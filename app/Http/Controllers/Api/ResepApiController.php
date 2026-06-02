<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\lansia;
use Illuminate\Support\Facades\Auth;

class ResepApiController extends Controller
{
    // ── Mapping hari (angka → nama) ───────────────────────
    private const HARI_MAP = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

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

        // Map semua detail obat
        $obatList = $resep->detailResep->map(function ($detail) {
            $jenis      = $detail->jenis_jadwal;   // 'harian' | 'hari_tertentu'
            $frekuensi  = (int) $detail->frekuensi;
            $bentukObat = $detail->obat?->bentuk_obat ?? null; // e.g. 'tablet','ml','kapsul'

            // ── jadwal_text ──────────────────────────────
            $jadwalText = match ($jenis) {
                'harian'        => "{$frekuensi}x sehari",
                'hari_tertentu' => "{$frekuensi}x seminggu",
                default         => "{$frekuensi}x",
            };

            // ── hari_konsumsi & hari_konsumsi_text ───────
            // hari_konsumsi disimpan sebagai JSON array angka: [1,3,5]
            // atau string CSV: "1,3,5"
            $hariKonsumsi     = null;
            $hariKonsumsiText = null;

            if ($jenis === 'hari_tertentu' && $detail->hari_konsumsi !== null) {
                // Normalise: bisa array atau string
                $raw = $detail->hari_konsumsi;
                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    $raw = is_array($decoded) ? $decoded : array_map('intval', explode(',', $raw));
                }
                $hariKonsumsi     = $raw;                             // [1, 3, 5]
                $hariKonsumsiText = implode(', ', array_map(
                    fn($h) => self::HARI_MAP[(int)$h] ?? $h,
                    $raw
                ));                                                    // "Senin, Rabu, Jumat"
            }

            // ── dosis_text ────────────────────────────────
            $dosis     = $detail->dosis;     // angka: "1", "5"
            $dosisText = $bentukObat
                ? "{$dosis} {$bentukObat}"   // "1 tablet" | "5 ml"
                : (string) $dosis;

            return [
                'nama_obat'          => $detail->obat?->nama_obat ?? '-',
                'dosis'              => $dosis,
                'dosis_text'         => $dosisText,
                'jenis_jadwal'       => $jenis,
                'frekuensi'          => $frekuensi,
                'jadwal_text'        => $jadwalText,
                'hari_konsumsi'      => $hariKonsumsi,
                'hari_konsumsi_text' => $hariKonsumsiText,
                'durasi_hari'        => $detail->durasi_hari,
                'jumlah_obat'        => $detail->jumlah_obat,
                'keterangan'         => $detail->keterangan,
            ];
        })->values()->toArray();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data resep berhasil diambil',
            'data'    => [
                'tanggal_resep' => $skrining->tanggal_skrining,
                'catatan'       => $resep->catatan,
                'jumlah_obat'   => count($obatList),
                'obat'          => $obatList,
            ],
        ], 200);
    }

    /**
     * Return only the `catatan` (notes) of the latest resep for the authenticated lansia.
     */
   
    /**
     * Return only the `catatan` (notes) of the latest resep for the authenticated lansia.
     */
    public function catatan()
    {
        $user   = Auth::user();
        $lansia = Lansia::where('id_user', $user->id)->first();

        if (!$lansia) {
            return response()->json(['status' => 'error', 'message' => 'Data lansia tidak ditemukan'], 404);
        }

        $skrining = $lansia->skrinings()
            ->whereHas('resep')
            ->with('resep')
            ->orderBy('tanggal_skrining', 'desc')
            ->first(['id_skrining', 'tanggal_skrining']);

        if (!$skrining || !$skrining->resep) {
            return response()->json(['status' => 'error', 'message' => 'Belum ada resep'], 404);
        }

        $resep = $skrining->resep;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id_resep' => $resep->id_resep,
                'tanggal_resep' => $skrining->tanggal_skrining,
                'catatan' => $resep->catatan,
            ],
        ], 200);
    }

    /**
     * Return `id_lansia` for a given resep id by resolving resep->skrining->id_lansia.
     */
    public function lansiaByResep($id_resep)
    {
        $resep = \App\Models\Resep::with('skrining')->find($id_resep);
        if (! $resep) {
            return response()->json(['status' => 'error', 'message' => 'Resep tidak ditemukan'], 404);
        }

        $idLansia = $resep->skrining?->id_lansia ?? null;
        if (! $idLansia) {
            return response()->json(['status' => 'error', 'message' => 'Lansia tidak terhubung ke resep ini'], 404);
        }

        return response()->json(['status' => 'success', 'data' => ['id_lansia' => $idLansia]], 200);
    }

    /**
     * Return `catatan` for a given resep id.
     */
   
    /**
     * Return `catatan` for a given resep id.
     */
    public function catatanByResep($id_resep)
    {
        $resep = \App\Models\Resep::find($id_resep);
        if (! $resep) {
            return response()->json(['status' => 'error', 'message' => 'Resep tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id_resep' => $resep->id_resep,
                'catatan'  => $resep->catatan,
            ],
        ], 200);
    }
}