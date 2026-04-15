<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\jadwalPosyandu;
class JadwalPosyanduController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = DB::table('jadwal_posyandu');
        $statsQuery = DB::table('jadwal_posyandu');



        $jadwalPosyandu = $query->orderBy('jadwal_posyandu.tanggal_pelaksanaan', 'desc')->get();

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'terjadwal' => (clone $statsQuery)->where('jadwal_posyandu.status', '0')->count(),
            'berlangsung' => (clone $statsQuery)->where('jadwal_posyandu.status', '1')->count(),
            'selesai' => (clone $statsQuery)->where('jadwal_posyandu.status', '2')->count(),
        ];

       return view('admin.petugas.jadwal_posyandu', compact('jadwalPosyandu', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pelaksanaan' => 'required|date',
            'tema'                => 'required|string|max:255',
            'lokasi'              => 'required|string|max:255',
            'kegiatan'            => 'nullable|array',
            'kegiatan.*.nama'     => 'required_with:kegiatan|string',
            'kegiatan.*.jam'      => 'nullable|date_format:H:i',
            'keterangan'          => 'nullable|string',
        ]);

        $kegiatan = $request->kegiatan
            ? json_encode($request->kegiatan)
            : null;

        $id_petugas = auth()->check() && auth()->user()->petugas ? auth()->user()->petugas->id_petugas : 1;

        DB::table('jadwal_posyandu')->insert([
            'id_petugas'          => $id_petugas,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'tema'                => $request->tema,
            'lokasi'              => $request->lokasi,
            'kegiatan'            => $kegiatan,
            'keterangan'          => $request->keterangan,
            'status'              => 1,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan!']);
        }

        return redirect()->route('jadwal_posyandu.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jadwalPosyandu = DB::table('jadwal_posyandu')->where('id_jadwal_posyandu', $id)->first();
    
        if (!$jadwalPosyandu) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Jadwal tidak ditemukan'], 404);
            }
            return redirect()->route('jadwal_posyandu.index')
                ->with('error', 'Jadwal tidak ditemukan!');
        }
    
        $jadwalPosyandu->kegiatan = $jadwalPosyandu->kegiatan 
            ? json_decode($jadwalPosyandu->kegiatan) 
            : [];
        
        if (request()->expectsJson()) {
            return response()->json($jadwalPosyandu);
        }
    
        return view('jadwal.detail_jadwal', compact('jadwalPosyandu'));
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jadwalLama = DB::table('jadwal_posyandu')
            ->where('id_jadwal_posyandu', $id)
            ->first();

     
        if (!$jadwalLama || $jadwalLama->status != 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Hanya jadwal dengan status "Terjadwal" yang boleh diedit'
                ], 403);
            }
            return redirect()->route('jadwal_posyandu.index')
                ->with('error', 'Hanya jadwal dengan status "Terjadwal" yang boleh diedit');
        }

        $minDate = date('Y-m-d', strtotime($jadwalLama->tanggal_pelaksanaan . ' +1 day'));
        
        $request->validate([
            'tanggal_pelaksanaan' => [
                'required',
                'date',
                'after:' . $minDate
            ],
            'tema'                => 'required|string|max:255',
            'lokasi'              => 'required|string|max:255',
            'kegiatan'            => 'nullable|array',
            'kegiatan.*.nama'     => 'required_with:kegiatan|string',
            'kegiatan.*.jam'      => 'nullable|date_format:H:i',
            'keterangan'          => 'nullable|string',
        ], [
            'tanggal_pelaksanaan.after' => "Tanggal harus lebih dari {$minDate} (H+1 dari jadwal semula)",
        ]);

        $kegiatan = $request->kegiatan
            ? json_encode($request->kegiatan)
            : null;

        DB::table('jadwal_posyandu')->where('id_jadwal_posyandu', $id)->update([
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'tema'                => $request->tema,
            'lokasi'              => $request->lokasi,
            'kegiatan'            => $kegiatan,
            'keterangan'          => $request->keterangan,
            'updated_at'          => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Jadwal berhasil diperbarui!'
            ]);
        }

        return redirect()->route('jadwal_posyandu.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    
    public function destroy(string $id)
    {
         DB::table('jadwal_posyandu')
            ->where('id_jadwal_posyandu', $id)
            ->delete();

        return redirect()->route('jadwal_posyandu.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}