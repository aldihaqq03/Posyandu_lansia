<?php

namespace App\Http\Controllers;

use App\Models\Saran;
use App\Models\Lansia;
use Illuminate\Http\Request;

class SaranController extends Controller
{
    // ─── Index: Tampilkan semua saran untuk lansia tertentu ────
    public function index(Lansia $lansia)
    {
        $sarans = $lansia->sarans()->latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $sarans,
        ]);
    }

    // ─── Store: Tambah saran baru untuk lansia ────────────────
    public function store(Request $request, Lansia $lansia)
    {

        $validated = $request->validate([
            'jenis_saran' => 'required|string|max:100',
            'isi_saran'   => 'required|string|max:1000',
        ]);

        $saran = $lansia->sarans()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Saran berhasil ditambahkan',
            'data' => $saran,
        ], 201);
    }

    // ─── Update: Edit saran lansia ─────────────────────────────
    public function update(Request $request, Lansia $lansia, Saran $saran)
    {

        // Validasi saran milik lansia ini
        if ($saran->id_lansia !== $lansia->id_lansia) {
            abort(403, 'Saran tidak ditemukan untuk lansia ini');
        }

        $validated = $request->validate([
            'jenis_saran' => 'sometimes|string|max:50',
            'isi_saran'   => 'sometimes|string|max:1000',
        ]);

        $saran->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Saran berhasil diperbarui',
            'data' => $saran,
        ]);
    }

    // ─── Destroy: Hapus saran lansia ──────────────────────────
    public function destroy(Lansia $lansia, Saran $saran)
    {

        // Validasi saran milik lansia ini
        if ($saran->id_lansia !== $lansia->id_lansia) {
            abort(403, 'Saran tidak ditemukan untuk lansia ini');
        }

        $saran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Saran berhasil dihapus',
        ]);
    }
}

