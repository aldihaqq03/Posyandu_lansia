<?php

namespace App\Http\Controllers;
use App\Models\Lansia;
use App\Models\Keluarga;
use App\Models\Skrining;
use App\Models\SkriningUtama;
use App\Models\SkriningPPOK;
use App\Models\Saran;
use App\Helpers\SkriningHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                'kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,berat_badan,tinggi_badan,imt,lingkar_perut,keluhan',
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
            ->with('kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,berat_badan,tinggi_badan,imt')
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
                        'nama_lansia'       => 'required|string|min:3|max:100',
                        'jenis_kelamin'     => 'required|in:L,P',
                        'tempat_lahir'      => 'nullable|string|max:50',
                        'tanggal_lahir'     => 'nullable|date',
                        'alamat'            => 'required|string|min:5',
                        'no_hp'             => 'nullable|digits_between:10,13',
                        'status_perkawinan' => 'nullable|string|max:20',
                        'riwayat_penyakit'  => 'nullable|string',
                        'tanggal_daftar'    => 'nullable|date',
                        'keterangan'        => 'nullable|string',
                        'email'             => 'nullable|email|max:100',
                        'keluarga'          => 'nullable|array',
                        'keluarga.*.nama_keluarga' => 'nullable|string|min:3|max:100',
                        'keluarga.*.no_sama' => 'nullable|string|max:15',
                        'keluarga.*.alamat' => 'nullable|string|max:255',
                    ]);

                    // Validasi umur >= 40 tahun
                    if ($request->tanggal_lahir) {
                        $birthDate = Carbon::createFromFormat('Y-m-d', $request->tanggal_lahir);
                        $age = $birthDate->diffInYears(Carbon::now());
                        if ($age < 40) {
                            return redirect()->back()
                                ->withErrors(['tanggal_lahir' => "Umur harus minimal 40 tahun (Saat ini umur Anda $age tahun)"])
                                ->withInput();
                        }
                    }

                    DB::transaction(function () use ($validated, $request) {
                        $defaultPassword = $request->no_hp ?: $request->nik;

                        $user = \App\Models\User::create([
                            'email'    => $validated['email'] ?? null,
                            'whatsapp' => $request->no_hp ?? '',
                            'password' => bcrypt($defaultPassword),
                        ]);

                        $validated['id_user'] = $user->id;
                        $lansia = Lansia::create($validated);

                        // Simpan data keluarga jika ada
                        if (!empty($validated['keluarga'])) {
                            foreach ($validated['keluarga'] as $keluargaData) {
                                // Hanya simpan jika nama_keluarga tidak kosong
                                if (!empty($keluargaData['nama_keluarga'])) {
                                    Keluarga::create([
                                        'id_lansia' => $lansia->id_lansia,
                                        'nama_keluarga' => $keluargaData['nama_keluarga'],
                                        'no_sama' => $keluargaData['no_sama'] ?? null,
                                        'alamat' => $keluargaData['alamat'] ?? null,
                                    ]);
                                }
                            }
                        }
                    });

                    return redirect()->route('data_lansia')
                        ->with('success', 'Data lansia berhasil ditambahkan.');
                }  



            // ================================================================
            // UPDATE method healthHistory() di LansiaController
            // Tambahkan lingkar_perut ke dalam select dan map
            // ================================================================

            public function healthHistory(Lansia $lansia)
            {
                $skrinings = $lansia->skrinings()
                    ->with([
                        // Tambah lingkar_perut di sini
                        'kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,berat_badan,tinggi_badan,imt,lingkar_perut',
                        'utama:id_skrining_utama,id_skrining,gula_darah,kolesterol',
                    ])
                    ->orderBy('tanggal_skrining')
                    ->get(['id_skrining', 'tanggal_skrining']);

                $data = $skrinings
                    ->filter(function ($s) {
                        return $s->kunjungan?->td_sistolik
                            || $s->kunjungan?->td_diastolik
                            || $s->kunjungan?->berat_badan
                            || $s->kunjungan?->lingkar_perut
                            || $s->utama?->gula_darah
                            || $s->utama?->kolesterol;
                    })
                    ->map(function ($s) {
                        return [
                            'tanggal'       => $s->tanggal_skrining,   // format Y-m-d dari DB
                            'td_sistolik'   => $s->kunjungan?->td_sistolik   ?? null,
                            'td_diastolik'  => $s->kunjungan?->td_diastolik  ?? null,
                            'berat_badan'   => $s->kunjungan?->berat_badan   ?? null,
                            'lingkar_perut' => $s->kunjungan?->lingkar_perut ?? null,
                            'gula_darah'    => $s->utama?->gula_darah        ?? null,
                            'kolesterol'    => $s->utama?->kolesterol         ?? null,
                        ];
                    })
                    ->values();

                return response()->json(['data' => $data]);
}

// ================================================================
// TIDAK ADA PERUBAHAN lain — method lain tetap sama
// ================================================================
    public function keluhanHistory(Lansia $lansia)
    {
        $skrinings = $lansia->skrinings()
            ->with('kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,berat_badan,tinggi_badan,imt')
            ->orderByDesc('tanggal_skrining')
            ->get(['id_skrining', 'tanggal_skrining', 'keluhan']);

        $data = $skrinings->map(function ($s) {
            return [
                'tanggal_skrining' => $s->tanggal_skrining,
                'keluhan'          => $s->keluhan ?? 'Tidak ada keluhan',
                'td_sistolik'      => $s->kunjungan?->td_sistolik  ?? null,
                'td_diastolik'     => $s->kunjungan?->td_diastolik ?? null,
                'berat_badan'      => $s->kunjungan?->berat_badan  ?? null,
            ];
        })->values();

        return response()->json(['data' => $data]);
    }

            public function monitoring(Lansia $lansia)
            {
            return view('lansia.monitoringKesehatan', compact('lansia'));
            }

    // ============================================================
    // UPDATE – Perbarui data lansia
    // ============================================================
        public function update(Request $request, Lansia $lansia)
    {
        $validated = $request->validate([
            'nik'               => 'required|digits:16|unique:lansia,nik,' . $lansia->id_lansia . ',id_lansia',
            'nama_lansia'       => 'required|string|min:3|max:100',
            'jenis_kelamin'     => 'required|in:L,P',
            'tempat_lahir'      => 'nullable|string|max:50',
            'tanggal_lahir'     => 'required|date',
            'alamat'            => 'required|string|min:5',
            'no_hp'             => 'nullable|digits_between:10,13',
            'status_perkawinan' => 'nullable|string|max:20',
            'riwayat_penyakit'  => 'nullable|string',
            'tanggal_daftar'    => 'nullable|date',
            'keterangan'        => 'nullable|string',
            'email'             => 'nullable|email|max:100',
            
            // Keluarga pertama WAJIB
            'keluarga.0.nama_keluarga' => 'required|string|min:3|max:100',
            'keluarga.0.no_sama'       => 'nullable|string|max:15',
            'keluarga.0.alamat'        => 'nullable|string|max:255',
            
            // Keluarga ke-2 dst opsional
            'keluarga'                       => 'required|array|min:1',
            'keluarga.*.nama_keluarga'       => 'nullable|string|min:3|max:100',
            'keluarga.*.no_sama'             => 'nullable|string|max:15',
            'keluarga.*.alamat'              => 'nullable|string|max:255',
        ], [
            'keluarga.0.nama_keluarga.required' => 'Nama anggota keluarga pertama wajib diisi.',
            'keluarga.required'                 => 'Minimal satu anggota keluarga wajib diisi.',
        ]);

        // Validasi umur >= 40 tahun
        $birthDate = Carbon::createFromFormat('Y-m-d', $request->tanggal_lahir);
        $age = $birthDate->diffInYears(Carbon::now());
        if ($age < 40) {
            return redirect()->back()
                ->withErrors(['tanggal_lahir' => "Umur harus minimal 40 tahun (saat ini $age tahun)"])
                ->withInput();
        }

        // Pisahkan data keluarga dari data lansia
        $keluargaData = $validated['keluarga'] ?? [];
        $lansiaData = collect($validated)->except('keluarga')->toArray();

        $idLansia = $lansia->id_lansia; // Simpan ID sebelum transaksi

        DB::transaction(function () use ($lansiaData, $keluargaData, $lansia, $idLansia) {
            // Update data lansia
            $lansia->update($lansiaData);

            // Hapus keluarga lama
            Keluarga::where('id_lansia', $idLansia)->delete();

            // Simpan keluarga baru — jika ada
            if (is_array($keluargaData) && count($keluargaData) > 0) {
                foreach ($keluargaData as $item) {
                    // Pastikan $item adalah array sebelum akses
                    if (!is_array($item)) continue;
                    
                    // Hanya simpan jika nama_keluarga tidak kosong
                    $namaKeluarga = trim($item['nama_keluarga'] ?? '');
                    if (!empty($namaKeluarga)) {
                        Keluarga::create([
                            'id_lansia'     => $idLansia,
                            'nama_keluarga' => $namaKeluarga,
                            'no_sama'       => trim($item['no_sama'] ?? '') ?: null,
                            'alamat'        => trim($item['alamat'] ?? '') ?: null,
                        ]);
                    }
                }
            }
        });

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

    // ============================================================
    // KELUARGA – Ambil data keluarga via AJAX
    // ============================================================
    public function getKeluarga(Lansia $lansia)
    {
        $keluarga = $lansia->keluargas()
            ->select('id_keluarga', 'nama_keluarga', 'no_sama', 'alamat')
            ->get();

        return response()->json(['keluarga' => $keluarga]);
    }
}