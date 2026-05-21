<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\MutasiStokObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObatController extends Controller
{
    // Daftar tipe obat yang valid
    const TIPE_OBAT = [
        'Tablet',
        'Kaplet',
        'Kapsul',
        'Sirup',
        'Suspensi',
        'Salep/Krim',
        'Injeksi',
        'Tetes',
        'Inhaler',
        'Suppositoria',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obat = Obat::all();
        $tipeObat = self::TIPE_OBAT;
        return view('admin.obat.index', compact('obat', 'tipeObat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('obat.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:150|unique:obat,nama_obat',
            'tipe_obat' => 'required|in:' . implode(',', self::TIPE_OBAT),
            'stock' => 'required|integer|min:0|max:999999',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'nama_obat.unique' => 'Nama obat sudah terdaftar, gunakan nama yang berbeda.',
            'tipe_obat.in' => 'Tipe obat tidak valid.',
            'stock.max' => 'Stok tidak boleh lebih dari 999999.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $obat = Obat::create($validated);
                
                MutasiStokObat::create([
                    'id_obat' => $obat->id_obat,
                    'tipe' => 'masuk',
                    'jumlah' => $obat->stock,
                    'keterangan' => 'stok awal obat',
                ]);
            });
            return redirect()->route('obat.index')->with('success', 'Data obat berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambah data obat')->withInput();
        }
    }

    /**
     * Restock obat
     */
    public function restock(Request $request, string $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:999999',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $obat = Obat::findOrFail($id);
                $obat->increment('stock', $request->jumlah);

                MutasiStokObat::create([
                    'id_obat' => $obat->id_obat,
                    'tipe' => 'masuk',
                    'jumlah' => $request->jumlah,
                    'keterangan' => $request->keterangan ?: 'Restock obat',
                ]);
            });

            return redirect()->route('obat.index')->with('success', 'Restock obat berhasil dilakukan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan restock obat');
        }
    }

    /**
     * Get mutasi stok API
     */
    public function mutasiStokApi($id)
    {
        $mutasi = MutasiStokObat::where('id_obat', $id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($mutasi);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('obat.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $obat = Obat::findOrFail($id);

        $validated = $request->validate([
            'nama_obat' => 'required|string|max:150|unique:obat,nama_obat,' . $id . ',id_obat',
            'tipe_obat' => 'required|in:' . implode(',', self::TIPE_OBAT),
            'keterangan' => 'nullable|string|max:500',
        ], [
            'nama_obat.unique' => 'Nama obat sudah terdaftar, gunakan nama yang berbeda.',
            'tipe_obat.in' => 'Tipe obat tidak valid.',
        ]);

        try {
            $obat->update($validated);
            return redirect()->route('obat.index')->with('success', 'Data obat berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupdate data obat')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(string $id)
    {
        try {
            $obat = Obat::findOrFail($id);
            $obat->delete();
            return redirect()->route('obat.index')->with('success', 'Data obat berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data obat');
        }
    }
}
