<?php

namespace App\Http\Controllers;

use App\Models\Skrining;
use App\Models\SkriningUtama;
use App\Models\SkriningPPOK;
use App\Models\JadwalPosyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SkriningController extends Controller
{
    /**
     * Mendapatkan jadwal posyandu hari ini yang berstatus 'Terjadwal' atau 'Berlangsung'
     */
    private function getJadwalHariIni()
    {
        return JadwalPosyandu::whereDate('tanggal_pelaksanaan', Carbon::today())
            ->whereIn('status', [1, 2])
            ->first();
    }

    /**
     * Simpan Pemeriksaan Mingguan (Kunjungan Rutin)
     */
    public function storePemeriksaan(Request $request)
    {
        $jadwal = $this->getJadwalHariIni();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Gagal menyimpan: Tidak ada jadwal posyandu aktif untuk hari ini.');
        }

        $validated = $request->validate([
            'id_lansia' => 'required|exists:lansia,id_lansia',
            'tinggi_badan' => 'required|numeric',
            'berat_badan' => 'required|numeric',
            'lingkar_perut' => 'required|numeric',
            'td_sistolik' => 'required|integer',
            'td_diastolik' => 'required|integer',
            'edukasi_penyakit' => 'required|string',
            'resep_obat' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $jadwal) {
                $skrining = Skrining::create([
                    'id_lansia' => $validated['id_lansia'],
                    'id_petugas' => auth()->user()->petugas->id_petugas ?? null,
                    'id_jadwal_posyandu' => $jadwal->id_jadwal_posyandu,
                    'tanggal_skrining' => Carbon::now(),
                    'keluhan' => $validated['edukasi_penyakit'], // Simpan langsung ke skrining
                ]);

                // Hitung IMT
                $imt = null;
                if ($validated['tinggi_badan'] > 0) {
                    $imt = $validated['berat_badan'] / pow($validated['tinggi_badan'] / 100, 2);
                }

                $skrining->utama()->create(array_merge($validated, [
                    'id_lansia' => $validated['id_lansia'],
                    'imt' => $imt
                ]));
            });

            return redirect()->route('berhasil')->with('success', 'Pemeriksaan mingguan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Simpan Skrining Utama
     */
    public function storeSkriningUtama(Request $request)
    {
        $jadwal = $this->getJadwalHariIni();

        if (!$jadwal || !$jadwal->ada_skrining_utama) {
            return redirect()->back()->with('error', 'Gagal menyimpan: Jadwal hari ini tidak mencakup Skrining Utama.');
        }

        try {
            DB::transaction(function () use ($request, $jadwal) {
                $skrining = Skrining::create([
                    'id_lansia' => $request->id_lansia,
                    'id_petugas' => auth()->user()->petugas->id_petugas ?? null,
                    'id_jadwal_posyandu' => $jadwal->id_jadwal_posyandu,
                    'tanggal_skrining' => Carbon::now(),
                ]);

                $data = $request->all();

                // 1. Hitung IMT
                if (!empty($data['tinggi_badan']) && !empty($data['berat_badan']) && $data['tinggi_badan'] > 0) {
                    $data['imt'] = $data['berat_badan'] / pow($data['tinggi_badan'] / 100, 2);
                }

                // 2. Kategori Gula Darah
                if (!empty($data['gula_darah'])) {
                    $gd = $data['gula_darah'];
                    if ($gd < 145)
                        $data['gula_darah_kategori'] = 1; // Baik
                    elseif ($gd < 200)
                        $data['gula_darah_kategori'] = 2; // Sedang
                    else
                        $data['gula_darah_kategori'] = 3; // Tidak Baik
                }

                // 3. Kategori Kolesterol
                if (!empty($data['kolesterol'])) {
                    $kol = $data['kolesterol'];
                    if ($kol < 150)
                        $data['kolesterol_kategori'] = 1;
                    elseif ($kol < 190)
                        $data['kolesterol_kategori'] = 2;
                    else
                        $data['kolesterol_kategori'] = 3;
                }

                // 4. Hitung SRQ Total
                $srq_total = 0;
                for ($i = 1; $i <= 20; $i++) {
                    if ($request->has("srq_$i")) {
                        $data["srq_$i"] = 1;
                        $srq_total++;
                    } else {
                        $data["srq_$i"] = 0;
                    }
                }
                $data['srq_total'] = $srq_total;

                $skrining->utama()->create($data);
            });
            return redirect()->route('berhasil');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Simpan Skrining PPOK
     */
    public function storeSkriningPPOK(Request $request)
    {
        $jadwal = $this->getJadwalHariIni();

        if (!$jadwal || !$jadwal->ada_skrining_ppok) {
            return redirect()->back()->with('error', 'Gagal menyimpan: Jadwal hari ini tidak mencakup Skrining PPOK.');
        }

        try {
            DB::transaction(function () use ($request, $jadwal) {
                $skrining = Skrining::create([
                    'id_lansia' => $request->id_lansia,
                    'id_petugas' => auth()->user()->petugas->id_petugas ?? null,
                    'id_jadwal_posyandu' => $jadwal->id_jadwal_posyandu,
                    'tanggal_skrining' => Carbon::now(),
                ]);

                $skrining->ppok()->create($request->all());
            });
            return redirect()->route('berhasil')->with('success', 'Skrining PPOK berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
