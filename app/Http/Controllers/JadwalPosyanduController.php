<?php

namespace App\Http\Controllers;

use App\Models\DetailSkrining;
use App\Models\JadwalPosyandu;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JadwalPosyanduController extends Controller
{
    public function index()
    {
        $this->autoUpdateStatus();
        $jadwalPosyandu = JadwalPosyandu::with('detailSkrining')
            ->whereIn('status', [JadwalPosyandu::STATUS_TERJADWAL, JadwalPosyandu::STATUS_BERLANGSUNG])
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->get();

        $availableYears = JadwalPosyandu::selectRaw('YEAR(tanggal_pelaksanaan) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $stats = [
            'total' => JadwalPosyandu::count(),
            'terjadwal' => JadwalPosyandu::where('status', JadwalPosyandu::STATUS_TERJADWAL)->count(),
            'berlangsung' => JadwalPosyandu::where('status', JadwalPosyandu::STATUS_BERLANGSUNG)->count(),
            'selesai' => JadwalPosyandu::where('status', JadwalPosyandu::STATUS_SELESAI)->count(),
        ];

        return view('admin.petugas.jadwal_posyandu', compact('jadwalPosyandu', 'stats', 'availableYears'));
    }

    public function store(Request $request)
    {
        $minDate = now('Asia/Jakarta')->addDays(3)->format('Y-m-d');

        $request->validate([
            'tanggal_pelaksanaan' => ['required', 'date', "after_or_equal:{$minDate}"],
            'tema' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kegiatan' => 'nullable|array',
            'kegiatan.*.nama' => 'required_with:kegiatan|string',
            'kegiatan.*.jam' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string',
            'jenis_skrining' => 'required|array|min:1',
            'jenis_skrining.*' => 'in:1,2,3',  // 1=Utama, 2=PPOK, 3=Kunjungan
        ], [
            'tanggal_pelaksanaan.after_or_equal' => "Tanggal minimal {$minDate} (H+3 dari hari ini)",
        ]);

        $conflict = JadwalPosyandu::whereDate('tanggal_pelaksanaan', $request->tanggal_pelaksanaan)->exists();
        if ($conflict) {
            return $this->errorResponse($request, 'Jadwal pada tanggal tersebut sudah ada', 422);
        }

        DB::transaction(function () use ($request) {
            $idPetugas = Auth::user()?->petugas?->id_petugas ?? 1;

            // 1. Ambil tanggal hari ini versi WIB
            $todayWIB = now('Asia/Jakarta')->format('Y-m-d');

            // 2. Bandingkan sesama teks/string agar akurat
            $statusJadwal = ($request->tanggal_pelaksanaan === $todayWIB)
                ? JadwalPosyandu::STATUS_BERLANGSUNG  // Jika hari ini → Berlangsung (1)
                : JadwalPosyandu::STATUS_TERJADWAL;

            $jadwal = JadwalPosyandu::create([
                'id_petugas' => $idPetugas,
                'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
                'tema' => $request->tema,
                'lokasi' => $request->lokasi,
                'kegiatan' => $request->kegiatan ? json_encode($request->kegiatan) : null,
                'keterangan' => $request->keterangan,
                'status' => $statusJadwal,
            ]);

            // Selalu sertakan Kunjungan Rutin (3), tambah pilihan user
            $jenisList = collect($request->jenis_skrining)
                ->map(fn ($j) => (int) $j)
                ->push(DetailSkrining::KUNJUNGAN_RUTIN) // 3
                ->unique()
                ->values();

            $inserts = $jenisList->map(fn ($j) => [
                'id_jadwal_posyandu' => $jadwal->id_jadwal_posyandu,
                'jenis_skrining' => $j,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            DB::table('detail_skrining')->insert($inserts);
        });

        // --- Kirim Notifikasi FCM ---
        try {
            $lansiaTokens = User::whereHas('lansia')
                ->whereNotNull('fcm_token')
                ->pluck('fcm_token')
                ->toArray();

            if (! empty($lansiaTokens)) {
                $title = 'Jadwal Posyandu Baru!';
                $body = 'Ada jadwal posyandu baru di '.$request->lokasi.' pada tanggal '.$request->tanggal_pelaksanaan;

                foreach ($lansiaTokens as $token) {
                    FcmService::sendNotification($token, $title, $body, [
                        'type' => 'jadwal_baru',
                        'id' => $request->tanggal_pelaksanaan, // Sebagai referensi
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('FCM Store Error: '.$e->getMessage());
        }

        return $this->successResponse($request, 'Jadwal berhasil ditambahkan!');
    }

    public function show(Request $request, string $id)
    {
        $jadwal = JadwalPosyandu::with('detailSkrining')->find($id);

        if (! $jadwal) {
            return $this->errorResponse($request, 'Jadwal tidak ditemukan', 404);
        }

        $jadwal->kegiatan = $jadwal->kegiatan ? json_decode($jadwal->kegiatan) : [];

        if ($request->expectsJson()) {
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

        $minDate = now('Asia/Jakarta')->addDays(3)->format('Y-m-d');

        $request->validate([
            'tanggal_pelaksanaan' => ['required', 'date', "after_or_equal:{$minDate}"],
            'tema' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kegiatan' => 'nullable|array',
            'kegiatan.*.nama' => 'required_with:kegiatan|string',
            'kegiatan.*.jam' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string',
            'jenis_skrining' => 'required|array|min:1',
            'jenis_skrining.*' => 'in:1,2,3',
        ], [
            'tanggal_pelaksanaan.after_or_equal' => "Tanggal minimal {$minDate} (H+3 dari hari ini)",
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
                'tema' => $request->tema,
                'lokasi' => $request->lokasi,
                'kegiatan' => $request->kegiatan ? json_encode($request->kegiatan) : null,
                'keterangan' => $request->keterangan,
            ]);

            DB::table('detail_skrining')->where('id_jadwal_posyandu', $id)->delete();

            $jenisList = collect($request->jenis_skrining)
                ->map(fn ($j) => (int) $j)
                ->push(DetailSkrining::KUNJUNGAN_RUTIN) // 3
                ->unique()
                ->values();

            $inserts = $jenisList->map(fn ($j) => [
                'id_jadwal_posyandu' => $id,
                'jenis_skrining' => $j,
                'created_at' => now(),
                'updated_at' => now(),
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

    private function autoUpdateStatus()
    {
        // Gunakan Asia/Jakarta agar sinkron dengan waktu Jember (WIB)
        $today = now('Asia/Jakarta')->format('Y-m-d');

        // Update yang sudah lewat menjadi Selesai
        JadwalPosyandu::whereIn('status', [
            JadwalPosyandu::STATUS_TERJADWAL,
            JadwalPosyandu::STATUS_BERLANGSUNG,
        ])
            ->whereDate('tanggal_pelaksanaan', '<', $today)
            ->update(['status' => JadwalPosyandu::STATUS_SELESAI]);

        // Update hari ini menjadi Berlangsung
        JadwalPosyandu::where('status', JadwalPosyandu::STATUS_TERJADWAL)
            ->whereDate('tanggal_pelaksanaan', '=', $today)
            ->update(['status' => JadwalPosyandu::STATUS_BERLANGSUNG]);
    }

    public function selesai(string $id)
    {
        $jadwal = JadwalPosyandu::findOrFail($id);

        if ($jadwal->status !== JadwalPosyandu::STATUS_BERLANGSUNG) {
            return redirect()->back()->with('error', 'Hanya jadwal berlangsung yang bisa diselesaikan.');
        }

        $jadwal->update(['status' => JadwalPosyandu::STATUS_SELESAI]);

        return redirect()->route('jadwal_posyandu.index')->with('success', 'Jadwal berhasil ditandai selesai.');
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
