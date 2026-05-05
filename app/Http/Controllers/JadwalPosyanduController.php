<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

// class JadwalPosyanduController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     public function index()
//     {
//         $query = DB::table('jadwal_posyandu');
//         $statsQuery = DB::table('jadwal_posyandu');



//         $jadwalPosyandu = $query->orderBy('jadwal_posyandu.tanggal_pelaksanaan', 'desc')->get();

//         $stats = [
//             'total' => (clone $statsQuery)->count(),
//             'terjadwal' => (clone $statsQuery)->where('jadwal_posyandu.status', '0')->count(),
//             'berlangsung' => (clone $statsQuery)->where('jadwal_posyandu.status', '1')->count(),
//             'selesai' => (clone $statsQuery)->where('jadwal_posyandu.status', '2')->count(),
//         ];

//        return view('admin.petugas.jadwal_posyandu', compact('jadwalPosyandu', 'stats'));
//     }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
        
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         $request->validate([
//             'tanggal_pelaksanaan' => 'required|date|after:today',
//             'tema'                => 'required|string|max:255',
//             'lokasi'              => 'required|string|max:255',
//             'kegiatan'            => 'nullable|array',
//             'kegiatan.*.nama'     => 'required_with:kegiatan|string',
//             'kegiatan.*.jam'      => 'nullable|date_format:H:i',
//             'keterangan'          => 'nullable|string',
//         ]);

//         $conflict = DB::table('jadwal_posyandu')
//     ->where('tanggal_pelaksanaan', $request->tanggal_pelaksanaan)
//     ->first();

// if ($conflict) {
//     if ($request->expectsJson()) {
//         return response()->json(['error' => 'Jadwal pada tanggal tersebut sudah ada'], 422);
//     }
//     return redirect()->back()->with('error', 'Jadwal pada tanggal tersebut sudah ada');
// }

// $kegiatan = $request->kegiatan
//     ? json_encode($request->kegiatan)
//     : null;

//         $id_petugas = auth()->check() && auth()->user()->petugas ? auth()->user()->petugas->id_petugas : 1;

//         DB::table('jadwal_posyandu')->insert([
//             'id_petugas'          => $id_petugas,
//             'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
//             'tema'                => $request->tema,
//             'lokasi'              => $request->lokasi,
//             'kegiatan'            => $kegiatan,
//             'ada_skrining_utama'  => $request->ada_skrining_utama ?? 0,
//             'ada_skrining_ppok'   => $request->ada_skrining_ppok ?? 0,
//             'keterangan'          => $request->keterangan,
//             'status'              => 1,
//             'created_at'          => now(),
//             'updated_at'          => now(),
//         ]);

//         if ($request->expectsJson()) {
//             return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan!']);
//         }

//         return redirect()->route('jadwal_posyandu.index')
//             ->with('success', 'Jadwal berhasil ditambahkan!');
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show(string $id)
//     {
//         $jadwalPosyandu = DB::table('jadwal_posyandu')->where('id_jadwal_posyandu', $id)->first();
    
//         if (!$jadwalPosyandu) {
//             if (request()->expectsJson()) {
//                 return response()->json(['error' => 'Jadwal tidak ditemukan'], 404);
//             }
//             return redirect()->route('jadwal_posyandu.index')
//                 ->with('error', 'Jadwal tidak ditemukan!');
//         }
    
//         $jadwalPosyandu->kegiatan = $jadwalPosyandu->kegiatan 
//             ? json_decode($jadwalPosyandu->kegiatan) 
//             : [];
        
//         if (request()->expectsJson()) {
//             return response()->json($jadwalPosyandu);
//         }
    
//         return view('jadwal.detail_jadwal', compact('jadwalPosyandu'));
//     }

//     public function edit(string $id)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, string $id)
//     {
//         $jadwalLama = DB::table('jadwal_posyandu')
//             ->where('id_jadwal_posyandu', $id)
//             ->first();

     
//         if (!$jadwalLama || $jadwalLama->status != 1) {
//             if ($request->expectsJson()) {
//                 return response()->json([
//                     'error' => 'Hanya jadwal dengan status "Terjadwal" yang boleh diedit'
//                 ], 403);
//             }
//             return redirect()->route('jadwal_posyandu.index')
//                 ->with('error', 'Hanya jadwal dengan status "Terjadwal" yang boleh diedit');
//         }

//         $minDate = date('Y-m-d', strtotime($jadwalLama->tanggal_pelaksanaan . ' +1 day'));
        
//         $request->validate([
//             'tanggal_pelaksanaan' => [
//                 'required',
//                 'date',
//                 'after:' . $minDate
//             ],
//             'tema'                => 'required|string|max:255',
//             'lokasi'              => 'required|string|max:255',
//             'kegiatan'            => 'nullable|array',
//             'kegiatan.*.nama'     => 'required_with:kegiatan|string',
//             'kegiatan.*.jam'      => 'nullable|date_format:H:i',
//             'keterangan'          => 'nullable|string',
//         ], [
//             'tanggal_pelaksanaan.after' => "Tanggal harus lebih dari {$minDate} (H+1 dari jadwal semula)",
//         ]);

//                 $conflict = DB::table('jadwal_posyandu')
//             ->where('tanggal_pelaksanaan', $request->tanggal_pelaksanaan)
//             ->first();

//         if ($conflict) {
//             if ($request->expectsJson()) {
//                 return response()->json(['error' => 'Jadwal pada tanggal tersebut sudah ada'], 422);
//             }
//             return redirect()->back()->with('error', 'Jadwal pada tanggal tersebut sudah ada');
//         }

//         $kegiatan = $request->kegiatan
//             ? json_encode($request->kegiatan)
//             : null;

//         DB::table('jadwal_posyandu')->where('id_jadwal_posyandu', $id)->update([
//             'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
//             'tema'                => $request->tema,
//             'lokasi'              => $request->lokasi,
//             'kegiatan'            => $kegiatan,
//             'ada_skrining_utama'  => $request->ada_skrining_utama ?? 0,
//             'ada_skrining_ppok'   => $request->ada_skrining_ppok ?? 0,
//             'keterangan'          => $request->keterangan,
//             'updated_at'          => now(),
//         ]);

//         if ($request->expectsJson()) {
//             return response()->json([
//                 'success' => true, 
//                 'message' => 'Jadwal berhasil diperbarui!'
//             ]);
//         }

//         return redirect()->route('jadwal_posyandu.index')
//             ->with('success', 'Jadwal berhasil diperbarui!');
//     }

    
//     public function destroy(string $id)
//     {
//          DB::table('jadwal_posyandu')
//             ->where('id_jadwal_posyandu', $id)
//             ->delete();

//         return redirect()->route('jadwal_posyandu.index')
//             ->with('success', 'Jadwal berhasil dihapus!');
//     }
// }

// app/Http/Controllers/JadwalPosyanduController.php

// namespace App\Http\Controllers;

// use App\Models\DetailSkrining;
// use App\Models\JadwalPosyandu;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

// class JadwalPosyanduController extends Controller
// {
//     public function index()
//     {
//         $jadwalPosyandu = JadwalPosyandu::with('detailSkrining')
//             ->orderBy('tanggal_pelaksanaan', 'desc')
//             ->get();

//         $stats = [
//             'total'       => JadwalPosyandu::count(),
//             'terjadwal'   => JadwalPosyandu::where('status', JadwalPosyandu::STATUS_TERJADWAL)->count(),
//             'berlangsung' => JadwalPosyandu::where('status', JadwalPosyandu::STATUS_BERLANGSUNG)->count(),
//             'selesai'     => JadwalPosyandu::where('status', JadwalPosyandu::STATUS_SELESAI)->count(),
//         ];

//         return view('admin.petugas.jadwal_posyandu', compact('jadwalPosyandu', 'stats'));
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'tanggal_pelaksanaan' => 'required|date|',
//             'tema'                => 'required|string|max:255',
//             'lokasi'              => 'required|string|max:255',
//             'kegiatan'            => 'nullable|array',
//             'kegiatan.*.nama'     => 'required_with:kegiatan|string',
//             'kegiatan.*.jam'      => 'nullable|date_format:H:i',
//             'keterangan'          => 'nullable|string',
//             // jenis_skrining: array of integer (1,2,3)
//             'jenis_skrining'      => 'required|array|min:1',
//             'jenis_skrining.*'    => 'integer|in:1,2,3',
//         ]);

//         // Cek konflik tanggal
//         $conflict = JadwalPosyandu::whereDate('tanggal_pelaksanaan', $request->tanggal_pelaksanaan)->exists();
//         if ($conflict) {
//             return $this->errorResponse($request, 'Jadwal pada tanggal tersebut sudah ada', 422);
//         }

//         DB::transaction(function () use ($request) {
//             $idPetugas = auth()->user()?->petugas?->id_petugas ?? 1;

//             $jadwal = JadwalPosyandu::create([
//                 'id_petugas'          => $idPetugas,
//                 'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
//                 'tema'                => $request->tema,
//                 'lokasi'              => $request->lokasi,
//                 'kegiatan'            => $request->kegiatan ? json_encode($request->kegiatan) : null,
//                 'keterangan'          => $request->keterangan,
//                 'status'              => JadwalPosyandu::STATUS_TERJADWAL,
//             ]);

//             // Selalu sertakan Kunjungan Rutin (1), tambah pilihan user
//             $jenisList = collect($request->jenis_skrining)
//                 ->push(DetailSkrining::KUNJUNGAN_RUTIN)
//                 ->unique()
//                 ->values();

//             $inserts = $jenisList->map(fn($j) => [
//                 'id_jadwal_posyandu' => $jadwal->id_jadwal_posyandu,
//                 'jenis_skrining'     => $j,
//                 'created_at'         => now(),
//                 'updated_at'         => now(),
//             ])->toArray();

//             DB::table('detail_skrining')->insert($inserts);
//         });

//         return $this->successResponse($request, 'Jadwal berhasil ditambahkan!');
//     }

//     public function show(string $id)
//     {
//         $jadwal = JadwalPosyandu::with('detailSkrining')->find($id);

//         if (! $jadwal) {
//             return $this->errorResponse(request(), 'Jadwal tidak ditemukan', 404);
//         }

//         $jadwal->kegiatan = $jadwal->kegiatan ? json_decode($jadwal->kegiatan) : [];

//         if (request()->expectsJson()) {
//             return response()->json($jadwal);
//         }

//         return view('jadwal.detail_jadwal', compact('jadwal'));
//     }

//     public function update(Request $request, string $id)
//     {
//         $jadwal = JadwalPosyandu::find($id);

//         if (! $jadwal || $jadwal->status !== JadwalPosyandu::STATUS_TERJADWAL) {
//             return $this->errorResponse($request, 'Hanya jadwal berstatus "Terjadwal" yang bisa diedit', 403);
//         }

//         $minDate = date('Y-m-d', strtotime($jadwal->tanggal_pelaksanaan . ' +1 day'));

//         $request->validate([
//             'tanggal_pelaksanaan' => ['required', 'date', "after:{$minDate}"],
//             'tema'                => 'required|string|max:255',
//             'lokasi'              => 'required|string|max:255',
//             'kegiatan'            => 'nullable|array',
//             'kegiatan.*.nama'     => 'required_with:kegiatan|string',
//             'kegiatan.*.jam'      => 'nullable|date_format:H:i',
//             'keterangan'          => 'nullable|string',
//             'jenis_skrining'      => 'required|array|min:1',
//             'jenis_skrining.*'    => 'integer|in:1,2,3',
//         ], [
//             'tanggal_pelaksanaan.after' => "Tanggal harus lebih dari {$minDate} (H+1 dari jadwal semula)",
//         ]);

//         $conflict = JadwalPosyandu::whereDate('tanggal_pelaksanaan', $request->tanggal_pelaksanaan)
//             ->where('id_jadwal_posyandu', '!=', $id)
//             ->exists();

//         if ($conflict) {
//             return $this->errorResponse($request, 'Jadwal pada tanggal tersebut sudah ada', 422);
//         }

//         DB::transaction(function () use ($request, $jadwal, $id) {
//             $jadwal->update([
//                 'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
//                 'tema'                => $request->tema,
//                 'lokasi'              => $request->lokasi,
//                 'kegiatan'            => $request->kegiatan ? json_encode($request->kegiatan) : null,
//                 'keterangan'          => $request->keterangan,
//             ]);

//             // Hapus detail lama, insert ulang
//             DB::table('detail_skrining')->where('id_jadwal_posyandu', $id)->delete();

//             $jenisList = collect($request->jenis_skrining)
//                 ->push(DetailSkrining::KUNJUNGAN_RUTIN)
//                 ->unique()
//                 ->values();

//             $inserts = $jenisList->map(fn($j) => [
//                 'id_jadwal_posyandu' => $id,
//                 'jenis_skrining'     => $j,
//                 'created_at'         => now(),
//                 'updated_at'         => now(),
//             ])->toArray();

//             DB::table('detail_skrining')->insert($inserts);
//         });

//         return $this->successResponse($request, 'Jadwal berhasil diperbarui!');
//     }

//     public function destroy(string $id)
//     {
//         DB::transaction(function () use ($id) {
//             DB::table('detail_skrining')->where('id_jadwal_posyandu', $id)->delete();
//             JadwalPosyandu::destroy($id);
//         });

//         return redirect()->route('jadwal_posyandu.index')->with('success', 'Jadwal berhasil dihapus!');
//     }

//     // ─── Private Helpers ──────────────────────────────────────────────────────

//     private function successResponse(Request $request, string $message)
//     {
//         if ($request->expectsJson()) {
//             return response()->json(['success' => true, 'message' => $message]);
//         }
//         return redirect()->route('jadwal_posyandu.index')->with('success', $message);
//     }

//     private function errorResponse(Request $request, string $message, int $status = 422)
//     {
//         if ($request->expectsJson()) {
//             return response()->json(['error' => $message], $status);
//         }
//         return redirect()->back()->with('error', $message);
//     }
// }

// app/Http/Controllers/JadwalPosyanduController.php

namespace App\Http\Controllers;

use App\Models\DetailSkrining;
use App\Models\JadwalPosyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalPosyanduController extends Controller
{
    public function index()
    {
        $jadwalPosyandu = JadwalPosyandu::with('detailSkrining')
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->get();

        $stats = [
            'total'       => JadwalPosyandu::count(),
            'terjadwal'   => JadwalPosyandu::where('status', JadwalPosyandu::STATUS_TERJADWAL)->count(),
            'berlangsung' => JadwalPosyandu::where('status', JadwalPosyandu::STATUS_BERLANGSUNG)->count(),
            'selesai'     => JadwalPosyandu::where('status', JadwalPosyandu::STATUS_SELESAI)->count(),
        ];

        return view('admin.petugas.jadwal_posyandu', compact('jadwalPosyandu', 'stats'));
    }

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
            'jenis_skrining'      => 'required|array|min:1',
            'jenis_skrining.*'    => 'in:1,2,3',  // 1=Utama, 2=PPOK, 3=Kunjungan
        ]);

        $conflict = JadwalPosyandu::whereDate('tanggal_pelaksanaan', $request->tanggal_pelaksanaan)->exists();
        if ($conflict) {
            return $this->errorResponse($request, 'Jadwal pada tanggal tersebut sudah ada', 422);
        }

        DB::transaction(function () use ($request) {
            $idPetugas = auth()->user()?->petugas?->id_petugas ?? 1;
$statusJadwal = ($request->tanggal_pelaksanaan == today()) 
    ? JadwalPosyandu::STATUS_BERLANGSUNG  // Jika hari ini → Berlangsung (1)
    : JadwalPosyandu::STATUS_TERJADWAL;
            $jadwal = JadwalPosyandu::create([
                'id_petugas'          => $idPetugas,
                'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
                'tema'                => $request->tema,
                'lokasi'              => $request->lokasi,
                'kegiatan'            => $request->kegiatan ? json_encode($request->kegiatan) : null,
                'keterangan'          => $request->keterangan,
                'status'              => $statusJadwal,
                 // 0
                 
            ]);

            // Selalu sertakan Kunjungan Rutin (3), tambah pilihan user
            $jenisList = collect($request->jenis_skrining)
                ->map(fn($j) => (int) $j)
                ->push(DetailSkrining::KUNJUNGAN_RUTIN) // 3
                ->unique()
                ->values();

            $inserts = $jenisList->map(fn($j) => [
                'id_jadwal_posyandu' => $jadwal->id_jadwal_posyandu,
                'jenis_skrining'     => $j,
                'created_at'         => now(),
                'updated_at'         => now(),
            ])->toArray();

            DB::table('detail_skrining')->insert($inserts);
        });

        // --- Kirim Notifikasi FCM ---
        try {
            $lansiaTokens = \App\Models\User::whereHas('lansia')
                ->whereNotNull('fcm_token')
                ->pluck('fcm_token')
                ->toArray();

            if (!empty($lansiaTokens)) {
                $title = "Jadwal Posyandu Baru!";
                $body = "Ada jadwal posyandu baru di " . $request->lokasi . " pada tanggal " . $request->tanggal_pelaksanaan;
                
                foreach ($lansiaTokens as $token) {
                    \App\Services\FcmService::sendNotification($token, $title, $body, [
                        'type' => 'jadwal_baru',
                        'id' => $request->tanggal_pelaksanaan // Sebagai referensi
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("FCM Store Error: " . $e->getMessage());
        }

        return $this->successResponse($request, 'Jadwal berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $jadwal = JadwalPosyandu::with('detailSkrining')->find($id);

        if (! $jadwal) {
            return $this->errorResponse(request(), 'Jadwal tidak ditemukan', 404);
        }

        $jadwal->kegiatan = $jadwal->kegiatan ? json_decode($jadwal->kegiatan) : [];

        if (request()->expectsJson()) {
            return response()->json($jadwal);
        }

        return view('jadwal.detail_jadwal', compact('jadwal'));
    }

    public function update(Request $request, string $id)
    {
        $jadwal = JadwalPosyandu::find($id);

        if (! $jadwal || $jadwal->status !== JadwalPosyandu::STATUS_TERJADWAL) {
            return $this->errorResponse($request, 'Hanya jadwal berstatus "Terjadwal" yang bisa diedit', 403);
        }

        $minDate = date('Y-m-d', strtotime($jadwal->tanggal_pelaksanaan . ' +1 day'));

        $request->validate([
            'tanggal_pelaksanaan' => ['required', 'date', "after:{$minDate}"],
            'tema'                => 'required|string|max:255',
            'lokasi'              => 'required|string|max:255',
            'kegiatan'            => 'nullable|array',
            'kegiatan.*.nama'     => 'required_with:kegiatan|string',
            'kegiatan.*.jam'      => 'nullable|date_format:H:i',
            'keterangan'          => 'nullable|string',
            'jenis_skrining'      => 'required|array|min:1',
            'jenis_skrining.*'    => 'in:1,2,3',
        ], [
            'tanggal_pelaksanaan.after' => "Tanggal harus lebih dari {$minDate} (H+1 dari jadwal semula)",
        ]);

        $conflict = JadwalPosyandu::whereDate('tanggal_pelaksanaan', $request->tanggal_pelaksanaan)
            ->where('id_jadwal_posyandu', '!=', $id)
            ->exists();

        if ($conflict) {
            return $this->errorResponse($request, 'Jadwal pada tanggal tersebut sudah ada', 422);
        }

        DB::transaction(function () use ($request, $jadwal, $id) {
            $jadwal->update([
                'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
                'tema'                => $request->tema,
                'lokasi'              => $request->lokasi,
                'kegiatan'            => $request->kegiatan ? json_encode($request->kegiatan) : null,
                'keterangan'          => $request->keterangan,
            ]);

            DB::table('detail_skrining')->where('id_jadwal_posyandu', $id)->delete();

            $jenisList = collect($request->jenis_skrining)
                ->map(fn($j) => (int) $j)
                ->push(DetailSkrining::KUNJUNGAN_RUTIN) // 3
                ->unique()
                ->values();

            $inserts = $jenisList->map(fn($j) => [
                'id_jadwal_posyandu' => $id,
                'jenis_skrining'     => $j,
                'created_at'         => now(),
                'updated_at'         => now(),
            ])->toArray();

            DB::table('detail_skrining')->insert($inserts);
        });

        return $this->successResponse($request, 'Jadwal berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            DB::table('detail_skrining')->where('id_jadwal_posyandu', $id)->delete();
            JadwalPosyandu::destroy($id);
        });

        return redirect()->route('jadwal_posyandu.index')->with('success', 'Jadwal berhasil dihapus!');
    }

    private function successResponse(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->route('jadwal_posyandu.index')->with('success', $message);
    }

    private function errorResponse(Request $request, string $message, int $status = 422)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $message], $status);
        }
        return redirect()->back()->with('error', $message);
    }
}