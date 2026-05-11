<?php

namespace App\Http\Controllers;

use App\Models\DetailSkrining;
use App\Models\JadwalPosyandu;
use App\Models\Lansia;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\DetailResep;
use App\Models\Skrining;
use App\Models\skrining_kunjungan;
use App\Models\SkriningUtama;
use App\Models\SkriningPPOK;
use App\Models\Saran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkriningController extends Controller
{
    // ─── Index ────────────────────────────────────────────────────
    public function index()
    {
        $jadwal = $this->getJadwalHariIni();

        $aktifSkrining = $jadwal
            ? $jadwal->detailSkrining
                ->pluck('jenis_skrining')
                ->map(fn($j) => (int) $j)
                ->toArray()
            : [];

        // ── Ambil semua lansia ──────────────────────────────────────
        $semua = Lansia::orderBy('nama_lansia')->get(['id_lansia', 'nama_lansia']);

        // ── ID lansia yang sudah skrining di jadwal hari ini ────────
        $sudahSkriningIds = [];
        if ($jadwal) {
            $sudahSkriningIds = Skrining::where('id_jadwal_posyandu', $jadwal->id_jadwal_posyandu)
                ->pluck('id_lansia')
                ->toArray();
        }

        // ── Pisahkan: belum vs sudah ────────────────────────────────
        $lansia       = $semua->whereNotIn('id_lansia', $sudahSkriningIds)->values();
        $sudahSkrining = $semua->whereIn('id_lansia', $sudahSkriningIds)->values();

        $obat = Obat::where('stock', '>', 0)->orderBy('nama_obat')->get();

        return view('admin.skrining.index', compact(
            'jadwal',
            'aktifSkrining',
            'lansia',
            'sudahSkrining',
            'obat'
        ));
    }

    // ─── Store ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $jadwal = $this->getJadwalHariIni();

        if (! $jadwal) {
            return $this->errorBack('Tidak ada jadwal posyandu aktif hari ini.');
        }

        // ── Cek apakah lansia sudah skrining di jadwal ini ──────────
        $sudah = Skrining::where('id_jadwal_posyandu', $jadwal->id_jadwal_posyandu)
            ->where('id_lansia', $request->id_lansia)
            ->exists();

        if ($sudah) {
            return $this->errorBack('Lansia ini sudah melakukan skrining pada jadwal hari ini.');
        }

        $aktifSkrining = $jadwal->detailSkrining
            ->pluck('jenis_skrining')
            ->map(fn($j) => (int) $j)
            ->toArray();

        // ── Validasi Umum ──────────────────────────────────────────
        $request->validate([
            'id_lansia' => 'required|exists:lansia,id_lansia',
            'keluhan'   => 'nullable|string|max:1000',
        ]);

        // ── Validasi Kunjungan Rutin ───────────────────────────────
        if (in_array(DetailSkrining::KUNJUNGAN_RUTIN, $aktifSkrining)) {
            $request->validate([
                'berat_badan'   => 'required|numeric|min:1|max:300',
                'tinggi_badan'  => 'required|numeric|min:50|max:250',
                'lingkar_perut' => 'required|numeric|min:30|max:200',
                'td_sistolik'   => 'required|integer|min:50|max:300',
                'td_diastolik'  => 'required|integer|min:30|max:200',
            ]);
        }

        // ── Validasi Resep (opsional) ──────────────────────────────
        if ($request->filled('ada_resep')) {
            $request->validate([
                'resep.*.id_obat'   => 'required|exists:obat,id_obat',
                'resep.*.dosis'     => 'required|string|max:100',
                'resep.*.frekuensi' => 'required|string|max:100',
            ]);
        }

        DB::transaction(function () use ($request, $jadwal, $aktifSkrining) {

            $idPetugas = auth()->user()?->petugas?->id_petugas;

            // ── Field fisik dasar ──────────────────────────────────
            $bb  = $request->berat_badan;
            $tb  = $request->tinggi_badan;
            $lp  = $request->lingkar_perut;
            $tds = $request->td_sistolik;
            $tdd = $request->td_diastolik;
            $imt = ($bb && $tb) ? round($bb / pow($tb / 100, 2), 2) : null;

            // 1. Header Skrining
            $skrining = Skrining::create([
                'id_lansia'          => $request->id_lansia,
                'id_petugas'         => $idPetugas,
                'id_jadwal_posyandu' => $jadwal->id_jadwal_posyandu,
                'tanggal_skrining'   => Carbon::today(),
                'keluhan'            => $request->keluhan,
            ]);

            // 2. KUNJUNGAN RUTIN (jenis_skrining = 3)
            if (in_array(DetailSkrining::KUNJUNGAN_RUTIN, $aktifSkrining)) {
                skrining_kunjungan::create([
                    'id_skrining'   => $skrining->id_skrining,
                    'berat_badan'   => $bb,
                    'tinggi_badan'  => $tb,
                    'imt'           => $imt,
                    'lingkar_perut' => $lp,
                    'td_sistolik'   => $tds,
                    'td_diastolik'  => $tdd,
                    'keluhan'       => $request->keluhan,
                ]);

                if ($request->filled('ada_resep') && ! empty($request->resep)) {
                    $resep = Resep::create([
                        'id_skrining' => $skrining->id_skrining,
                        'id_petugas'  => $idPetugas,
                        'catatan'     => $request->catatan_resep,
                    ]);

                    foreach ($request->resep as $r) {
                        DetailResep::create([
                            'id_resep'   => $resep->id_resep,
                            'id_obat'    => $r['id_obat'],
                            'dosis'      => $r['dosis'],
                            'frekuensi'  => $r['frekuensi'],
                            'keterangan' => $r['keterangan'] ?? null,
                        ]);
                    }
                }
            }

            // 3. SKRINING UTAMA (jenis_skrining = 1)
            if (in_array(DetailSkrining::SKRINING_UTAMA, $aktifSkrining)) {

                $srqTotal = 0;
                $srqData  = [];
                for ($i = 1; $i <= 20; $i++) {
                    $val             = $request->has("srq_{$i}") ? 1 : 0;
                    $srqData["srq_{$i}"] = $val;
                    $srqTotal += $val;
                }

                $gulaDarah  = $request->gula_darah;
                $kolesterol = $request->kolesterol;

                $gulaDarahKategori  = $gulaDarah  ? ($gulaDarah < 145  ? 1 : ($gulaDarah < 200  ? 2 : 3)) : null;
                $kolesterolKategori = $kolesterol ? ($kolesterol < 150  ? 1 : ($kolesterol < 190 ? 2 : 3)) : null;

                $riwayatKeluarga = $request->riwayat_penyakit_keluarga
                    ? json_encode($request->riwayat_penyakit_keluarga)
                    : null;
                $riwayatSendiri = $request->riwayat_penyakit_sendiri
                    ? json_encode($request->riwayat_penyakit_sendiri)
                    : null;
                $penglihatan = $request->skrining_penglihatan
                    ? json_encode($request->skrining_penglihatan)
                    : null;
                $pendengaran = $request->skrining_pendengaran
                    ? json_encode($request->skrining_pendengaran)
                    : null;

                SkriningUtama::create(array_merge([
                    'id_skrining'               => $skrining->id_skrining,
                    'berat_badan'               => $bb,
                    'tinggi_badan'              => $tb,
                    'imt'                       => $imt,
                    'lingkar_perut'             => $lp,
                    'td_sistolik'               => $tds,
                    'td_diastolik'              => $tdd,
                    'merokok'                   => $request->merokok,
                    'merokok_kategori'          => $request->merokok_kategori,
                    'paparan_asap_rokok'        => $request->paparan_asap_rokok,
                    'paparan_asap_rokok_frekuensi' => $request->paparan_asap_rokok_frekuensi,
                    'konsumsi_alkohol'          => $request->konsumsi_alkohol,
                    'konsumsi_gula'             => $request->konsumsi_gula,
                    'konsumsi_garam'            => $request->konsumsi_garam,
                    'konsumsi_minyak'           => $request->konsumsi_minyak,
                    'konsumsi_sayur_buah'       => $request->konsumsi_sayur_buah,
                    'aktivitas_fisik'           => $request->aktivitas_fisik,
                    'riwayat_penyakit_keluarga' => $riwayatKeluarga,
                    'riwayat_penyakit_sendiri'  => $riwayatSendiri,
                    'gula_darah'                => $gulaDarah,
                    'gula_darah_kategori'       => $gulaDarahKategori,
                    'kolesterol'                => $kolesterol,
                    'kolesterol_kategori'       => $kolesterolKategori,
                    'iva_sadanis'               => $request->iva_sadanis,
                    'srq_total'                 => $srqTotal,
                    'skrining_penglihatan'      => $penglihatan,
                    'skrining_pendengaran'      => $pendengaran,
                ], $srqData));
            }

            // 4. SKRINING PPOK (jenis_skrining = 2)
            if (in_array(DetailSkrining::SKRINING_PPOK, $aktifSkrining)) {

                $rph       = (int) $request->puma_rokok_per_hari;
                $lmt       = (int) $request->puma_lama_merokok_tahun;
                $packYears = ($lmt > 0 && $rph > 0)
                    ? round(($lmt * $rph) / 20, 2)
                    : null;

                $skorMerokok = match (true) {
                    $rph === 0                                      => 0,
                    $packYears !== null && $packYears <= 30         => 1,
                    default                                         => 2,
                };

                $pumaSkor = 0;
                $pumaSkor += (int) ($request->puma_jenis_kelamin    ?? 0);
                $pumaSkor += (int) ($request->puma_kategori_usia    ?? 0);
                $pumaSkor += (int) $skorMerokok;
                $pumaSkor += (int) ($request->puma_napas_pendek     ?? 0);
                $pumaSkor += (int) ($request->puma_sulit_dahak      ?? 0);
                $pumaSkor += (int) ($request->puma_batuk_tanpa_flu  ?? 0);
                $pumaSkor += (int) ($request->puma_pernah_spirometri ?? 0);

                $rasioVepKvpPre  = ($request->vep1_pre  && $request->kvp_pre)
                    ? round(($request->vep1_pre  / $request->kvp_pre)  * 100, 2) : null;
                $rasioVepKvpPost = ($request->vep1_post && $request->kvp_post)
                    ? round(($request->vep1_post / $request->kvp_post) * 100, 2) : null;

                $riwayatKeluargaPPOK = $request->riwayat_penyakit_keluarga
                    ? json_encode($request->riwayat_penyakit_keluarga) : null;
                $riwayatSendiriPPOK = $request->riwayat_penyakit_sendiri
                    ? json_encode($request->riwayat_penyakit_sendiri)  : null;

                SkriningPPOK::create([
                    'id_skrining'              => $skrining->id_skrining,
                    'pekerjaan'                => $request->pekerjaan,
                    'status_vaksinasi_covid'   => $request->status_vaksinasi_covid,
                    'kurang_aktivitas_fisik'   => $request->kurang_aktivitas_fisik,
                    'kurang_sayur_buah'        => $request->kurang_sayur_buah,
                    'merokok'                  => $request->merokok,
                    'jenis_rokok'              => $request->jenis_rokok,
                    'konsumsi_alkohol'         => $request->konsumsi_alkohol,
                    'riwayat_penyakit_keluarga' => $riwayatKeluargaPPOK,
                    'riwayat_penyakit_sendiri'  => $riwayatSendiriPPOK,
                    'berat_badan'              => $bb,
                    'tinggi_badan'             => $tb,
                    'imt'                      => $imt,
                    'lingkar_perut'            => $lp,
                    'td_sistolik'              => $tds,
                    'td_diastolik'             => $tdd,
                    'puma_jenis_kelamin'       => $request->puma_jenis_kelamin,
                    'puma_kategori_usia'       => $request->puma_kategori_usia,
                    'puma_tidak_merokok'       => ($rph === 0) ? 1 : 0,
                    'puma_rokok_per_hari'      => $rph,
                    'puma_lama_merokok_tahun'  => $lmt,
                    'puma_pack_years'          => $packYears,
                    'puma_skor_merokok'        => $skorMerokok,
                    'puma_napas_pendek'        => $request->puma_napas_pendek,
                    'puma_sulit_dahak'         => $request->puma_sulit_dahak,
                    'puma_batuk_tanpa_flu'     => $request->puma_batuk_tanpa_flu,
                    'puma_pernah_spirometri'   => $request->puma_pernah_spirometri,
                    'puma_total_skor'          => $pumaSkor,
                    'puma_kategori_hasil'      => $pumaSkor >= 6 ? 1 : 0,
                    'rapid_antigen'            => $request->rapid_antigen,
                    'kadar_co_ppm'             => $request->kadar_co_ppm,
                    'vep1_pre'                 => $request->vep1_pre,
                    'kvp_pre'                  => $request->kvp_pre,
                    'rasio_vep1_kvp_pre'       => $rasioVepKvpPre,
                    'pemberian_bronkodilator'  => $request->pemberian_bronkodilator,
                    'vep1_post'                => $request->vep1_post,
                    'kvp_post'                 => $request->kvp_post,
                    'rasio_vep1_kvp_post'      => $rasioVepKvpPost,
                    'hasil_spirometri'         => $request->hasil_spirometri,
                ]);
            }

            // 5. Simpan Saran baru
            if ($request->filled('saran')) {
                foreach ($request->saran as $s) {
                    if (!empty($s['jenis_saran']) && !empty($s['isi_saran'])) {
                        Saran::create([
                            'id_lansia'   => $request->id_lansia,
                            'jenis_saran' => $s['jenis_saran'],
                            'isi_saran'   => $s['isi_saran'],
                        ]);
                    }
                }
            }

            // 6. Update Saran Lama
            if ($request->filled('saran_edit')) {
                foreach ($request->saran_edit as $id => $data) {
                    if (!empty($data['jenis_saran']) && !empty($data['isi_saran'])) {
                        Saran::where('id_saran', $id)->update([
                            'jenis_saran' => $data['jenis_saran'],
                            'isi_saran'   => $data['isi_saran'],
                        ]);
                    }
                }
            }

            // 7. Update status jadwal → Berlangsung
            if ($jadwal->status === JadwalPosyandu::STATUS_TERJADWAL) {
                $jadwal->update(['status' => JadwalPosyandu::STATUS_BERLANGSUNG]);
            }
        });

        return redirect()->route('skrining.index')->with('success', 'Skrining berhasil disimpan.');
    }

    // ─── Helpers ──────────────────────────────────────────────────
    private function getJadwalHariIni(): ?JadwalPosyandu
    {
        return JadwalPosyandu::with('detailSkrining')
            ->whereDate('tanggal_pelaksanaan', Carbon::today())
            ->whereIn('status', [
                JadwalPosyandu::STATUS_TERJADWAL,
                JadwalPosyandu::STATUS_BERLANGSUNG,
            ])
            ->first();
    }

    private function errorBack(string $message)
    {
        if (request()->expectsJson()) {
            return response()->json(['error' => $message], 422);
        }
        return redirect()->back()->with('error', $message);
    }
}