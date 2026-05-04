<?php

namespace App\Http\Controllers;
use App\Models\Lansia;
use App\Models\Skrining;
use App\Models\SkriningUtama;
use App\Models\SkriningPPOK;
use App\Helpers\SkriningHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LansiaController extends Controller
{
    // ============================================================
    // INDEX – Daftar lansia (tabel ringkas)
    // ============================================================
    public function index()
    {
        $lansias = Lansia::with('latestSkriningUtama')
            ->latest()
            ->paginate(10);

        $total_lansia = Lansia::count();

        $penyakit_beresiko = ['Hipertensi', 'Diabetes', 'Jantung', 'Stroke', 'PPOK'];

        $resiko_tinggi = Lansia::where(function ($q) use ($penyakit_beresiko) {
                foreach ($penyakit_beresiko as $p) {
                    $q->orWhere('riwayat_penyakit', 'LIKE', "%$p%");
                }
            })
            ->orWhereHas('latestSkriningUtama', fn($q) =>
                $q->where('gula_darah_kategori', 3)->orWhere('kolesterol_kategori', 3)
            )
            ->count();

        $status_sehat = Lansia::whereDoesntHave('latestSkriningUtama', fn($q) =>
                $q->where('gula_darah_kategori', '>', 1)->orWhere('kolesterol_kategori', '>', 1)
            )
            ->whereHas('latestSkriningUtama')
            ->where(function ($q) use ($penyakit_beresiko) {
                foreach ($penyakit_beresiko as $p) {
                    $q->where('riwayat_penyakit', 'NOT LIKE', "%$p%");
                }
            })
            ->count();

        $jadwal_periksa = DB::table('jadwal_posyandu')
            ->whereIn('status', [1, 2])
            ->count();

        return view('admin.data_lansia', compact(
            'lansias',
            'total_lansia',
            'resiko_tinggi',
            'status_sehat',
            'jadwal_periksa',
        ));
    }

    // ============================================================
    // HISTORI SKRINING — 3 tabel terpisah: kunjungan, utama, ppok
    // ============================================================
    public function historiSkrining(Lansia $lansia)
    {
        // Skrining yang punya relasi kunjungan
        // Eager load semua field kunjungan agar tidak N+1
        $kunjungans = $lansia->skrinings()
            ->whereHas('kunjungan')
            ->with([
                'petugas:id_petugas,nama',
                'kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,berat_badan,tinggi_badan,lingkar_perut,keluhan',
            ])
            ->orderByDesc('tanggal_skrining')
            ->get(['id_skrining', 'id_petugas', 'tanggal_skrining', 'keluhan']);

        // Skrining yang punya relasi utama
        // Eager load SEMUA field utama (40+ field)
        $utamas = $lansia->skrinings()
            ->whereHas('utama')
            ->with([
                'petugas:id_petugas,nama',
                'utama', // Load ALL fields dari utama (tidak specify, ambil semua)
            ])
            ->orderByDesc('tanggal_skrining')
            ->get(['id_skrining', 'id_petugas', 'tanggal_skrining', 'keluhan']);

        // Skrining yang punya relasi ppok
        // Eager load SEMUA field ppok (50+ field)
        $ppoks = $lansia->skrinings()
            ->whereHas('ppok')
            ->with([
                'petugas:id_petugas,nama',
                'ppok', // Load ALL fields dari ppok (tidak specify, ambil semua)
            ])
            ->orderByDesc('tanggal_skrining')
            ->get(['id_skrining', 'id_petugas', 'tanggal_skrining', 'keluhan']);

        return view('lansia.show', compact(
            'lansia',
            'kunjungans',
            'utamas',
            'ppoks',
        ));
    }
    
    // ============================================================
    // DETAIL SKRINING UTAMA — AJAX untuk modal
    // ============================================================
    public function detailSkriningUtama(Lansia $lansia, $id_skrining)
    {
        $skrining = Skrining::findOrFail($id_skrining);
        
        // Validasi: skrining ini milik lansia ini?
        if ($skrining->id_lansia !== $lansia->id_lansia) {
            abort(403, 'Unauthorized');
        }
        
        $utama = $skrining->utama;
        
        // Render semua field per kategori menggunakan helper
        $data = SkriningHelper::renderAll($utama, 'utama');
        
        return response()->json($data);
    }

    // ============================================================
    // DETAIL SKRINING PPOK — AJAX untuk modal
    // ============================================================
    public function detailSkriningPPOK(Lansia $lansia, $id_skrining)
    {
        $skrining = Skrining::findOrFail($id_skrining);
        
        // Validasi: skrining ini milik lansia ini?
        if ($skrining->id_lansia !== $lansia->id_lansia) {
            abort(403, 'Unauthorized');
        }
        
        $ppok = $skrining->ppok;
        
        // Render semua field per kategori menggunakan helper
        $data = SkriningHelper::renderAll($ppok, 'ppok');
        
        return response()->json($data);
    }

    // ============================================================
    // HEALTH SUMMARY — AJAX untuk detail panel di halaman index
    // ============================================================
    public function healthSummary(Lansia $lansia)
    {
        $kunjungan = $lansia->skrinings()
            ->whereHas('kunjungan')
            ->with('kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik')
            ->orderByDesc('tanggal_skrining')
            ->first()?->kunjungan;

        $utama = $lansia->latestSkriningUtama;

        return response()->json([
            'sistolik'   => $kunjungan?->td_sistolik  ?? null,
            'diastolik'  => $kunjungan?->td_diastolik ?? null,
            'gula_darah' => $utama?->gula_darah       ?? null,
            'kolesterol' => $utama?->kolesterol        ?? null,
        ]);
    }

    // ============================================================
    // STORE – Tambah lansia baru
    // ============================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'               => 'required|digits:16|unique:lansia,nik',
            'nama_lansia'       => 'required|string|max:100',
            'jenis_kelamin'     => 'required|in:L,P',
            'tempat_lahir'      => 'nullable|string|max:50',
            'tanggal_lahir'     => 'nullable|date',
            'alamat'            => 'nullable|string',
            'no_hp'             => 'nullable|digits_between:10,13',
            'status_perkawinan' => 'nullable|string|max:20',
            'riwayat_penyakit'  => 'nullable|string',
            'tanggal_daftar'    => 'nullable|date',
            'keterangan'        => 'nullable|string',
            'email'             => 'nullable|email|max:100',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $defaultPassword = $request->no_hp ?: $request->nik;

            $user = \App\Models\User::create([
                'email'    => $validated['email'] ?? null,
                'whatsapp' => $request->no_hp ?? '',
                'password' => bcrypt($defaultPassword),
            ]);

            $validated['id_user'] = $user->id;
            Lansia::create($validated);
        });

        return redirect()->route('data_lansia')
            ->with('success', 'Data lansia berhasil ditambahkan.');
    }

    // ============================================================
    // UPDATE – Perbarui data lansia
    // ============================================================
    public function update(Request $request, Lansia $lansia)
    {
        $validated = $request->validate([
            'nik'               => 'required|digits:16|unique:lansia,nik,' . $lansia->id_lansia . ',id_lansia',
            'nama_lansia'       => 'required|string|max:100',
            'jenis_kelamin'     => 'required|in:L,P',
            'tempat_lahir'      => 'nullable|string|max:50',
            'tanggal_lahir'     => 'nullable|date',
            'alamat'            => 'nullable|string',
            'no_hp'             => 'nullable|digits_between:10,13',
            'status_perkawinan' => 'nullable|string|max:20',
            'riwayat_penyakit'  => 'nullable|string',
            'tanggal_daftar'    => 'nullable|date',
            'keterangan'        => 'nullable|string',
            'email'             => 'nullable|email|max:100',
        ]);

        $lansia->update($validated);

        return redirect()->route('data_lansia')
            ->with('success', 'Data lansia berhasil diperbarui.');
    }

    // ============================================================
    // DESTROY – Hapus lansia
    // ============================================================
    public function destroy(Lansia $lansia)
    {
        $lansia->delete();

        return redirect()->route('data_lansia')
            ->with('success', 'Data lansia berhasil dihapus.');
    }
}