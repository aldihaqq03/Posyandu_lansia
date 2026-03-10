<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\jadwalPosyandu;
class jadwalPosyanduController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwalPosyandu = DB::table('jadwal_posyandu')
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->get();


            $stats = [
                'total' => DB::table('jadwal_posyandu')->count(),
                'terjadwal' => DB::table('jadwal_posyandu')->where('status', '0')->count(),
                'berlangsung' => DB::table('jadwal_posyandu')->where('status', '1')->count(),
                'selesai' => DB::table('jadwal_posyandu')->where('status', '2')->count(),
            ];

       return view('admin.jadwal_posyandu', compact('jadwalPosyandu', 'stats'));
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

        DB::table('jadwal_posyandu')->insert([
            'id_petugas'          => 1,
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
    //  // INDEX
    // public function index()
    // {
    //     $jadwalPosyandu = JadwalPosyandu::orderBy('tanggal_pelaksanaan', 'desc')->get();

    //     $stats = [
    //         'total'       => JadwalPosyandu::count(),
    //         'terjadwal'   => JadwalPosyandu::where('status', 1)->count(),
    //         'berlangsung' => JadwalPosyandu::where('status', 2)->count(),
    //         'selesai'     => JadwalPosyandu::where('status', 3)->count(),
    //     ];

    //     return view('admin.jadwal_posyandu', compact('jadwalPosyandu', 'stats'));
    // }

    // public function create() {}

    // // STORE
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'tanggal_pelaksanaan' => 'required|date',
    //         'tema'                => 'required|string|max:255',
    //         'lokasi'              => 'required|string|max:255',
    //         'kegiatan'            => 'nullable|array',
    //         'kegiatan.*.nama'     => 'required_with:kegiatan|string',
    //         'kegiatan.*.jam'      => 'nullable|date_format:H:i',
    //         'keterangan'          => 'nullable|string',
    //     ]);

    //     $kegiatan = $request->kegiatan
    //         ? json_encode($request->kegiatan)
    //         : null;

    //     // Eloquent: created_at & updated_at otomatis diisi
    //     JadwalPosyandu::create([
    //         'id_petugas'          => 1, // sementara, nanti ganti auth()->id()
    //         'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
    //         'tema'                => $request->tema,
    //         'lokasi'              => $request->lokasi,
    //         'kegiatan'            => $kegiatan,
    //         'keterangan'          => $request->keterangan,
    //         'status'              => 1,
    //     ]);

    //     if ($request->expectsJson()) {
    //         return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan!']);
    //     }

    //     return redirect()->route('jadwal_posyandu.index')
    //         ->with('success', 'Jadwal berhasil ditambahkan!');
    // }

    // // SHOW
    // public function show(string $id)
    // {
    //     // findOrFail otomatis return 404 kalau tidak ketemu — tidak perlu cek manual
    //     $jadwalPosyandu = JadwalPosyandu::findOrFail($id);

    //     // Decode kegiatan supaya JS tidak perlu JSON.parse lagi
    //     $jadwalPosyandu->kegiatan = $jadwalPosyandu->kegiatan
    //         ? json_decode($jadwalPosyandu->kegiatan)
    //         : [];

    //     if (request()->expectsJson()) {
    //         return response()->json($jadwalPosyandu);
    //     }

    //     return view('jadwal.detail_jadwal', compact('jadwalPosyandu'));
    // }

    // public function edit(string $id) {}

    // // UPDATE
    // public function update(Request $request, string $id)
    // {
    //     // findOrFail otomatis 404 kalau tidak ketemu
    //     $jadwalLama = JadwalPosyandu::findOrFail($id);

    //     // Hanya status Terjadwal (1) yang boleh diedit
    //     if ($jadwalLama->status != 1) {
    //         if ($request->expectsJson()) {
    //             return response()->json([
    //                 'error' => 'Hanya jadwal dengan status "Terjadwal" yang boleh diedit'
    //             ], 403);
    //         }
    //         return redirect()->route('jadwal_posyandu.index')
    //             ->with('error', 'Hanya jadwal dengan status "Terjadwal" yang boleh diedit');
    //     }

    //     $minDate = date('Y-m-d', strtotime($jadwalLama->tanggal_pelaksanaan . ' +1 day'));

    //     $request->validate([
    //         'tanggal_pelaksanaan' => ['required', 'date', 'after:' . $minDate],
    //         'tema'                => 'required|string|max:255',
    //         'lokasi'              => 'required|string|max:255',
    //         'kegiatan'            => 'nullable|array',
    //         'kegiatan.*.nama'     => 'required_with:kegiatan|string',
    //         'kegiatan.*.jam'      => 'nullable|date_format:H:i',
    //         'keterangan'          => 'nullable|string',
    //     ], [
    //         'tanggal_pelaksanaan.after' => "Tanggal harus lebih dari {$minDate} (H+1 dari jadwal semula)",
    //     ]);

    //     $kegiatan = $request->kegiatan
    //         ? json_encode($request->kegiatan)
    //         : null;

    //     // Eloquent: updated_at otomatis diisi, tidak perlu tulis now()
    //     $jadwalLama->update([
    //         'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
    //         'tema'                => $request->tema,
    //         'lokasi'              => $request->lokasi,
    //         'kegiatan'            => $kegiatan,
    //         'keterangan'          => $request->keterangan,
    //     ]);

    //     if ($request->expectsJson()) {
    //         return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui!']);
    //     }

    //     return redirect()->route('jadwal_posyandu.index')
    //         ->with('success', 'Jadwal berhasil diperbarui!');
    // }

    // // DESTROY
    // public function destroy(string $id)
    // {
    //     JadwalPosyandu::findOrFail($id)->delete();

    //     return redirect()->route('jadwal_posyandu.index')
    //         ->with('success', 'Jadwal berhasil dihapus!');
    // }
}
