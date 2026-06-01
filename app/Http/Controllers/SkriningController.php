<?php

namespace App\Http\Controllers;

use App\Models\DetailResep;
use App\Models\DetailSkrining;
use App\Models\JadwalPosyandu;
use App\Models\Lansia;
use App\Models\MutasiStokObat;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\Saran;
use App\Models\Skrining;
use App\Models\skrining_kunjungan;
use App\Models\SkriningPPOK;
use App\Models\SkriningUtama;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SkriningController extends Controller
{
    // ─── Index ────────────────────────────────────────────────────
    // ─── Index ────────────────────────────────────────────────────
    public function index()
    {
        $jadwal = $this->getJadwalHariIni();

        $aktifSkrining = $jadwal
            ? $jadwal->detailSkrining
                ->pluck('jenis_skrining')
                ->map(fn ($j) => (int) $j)
                ->toArray()
            : [];

        // ── Ambil semua lansia ──────────────────────────────────────
        $semua = Lansia::orderBy('nama_lansia')->get(['id_lansia', 'nama_lansia', 'nik', 'jenis_kelamin', 'tanggal_lahir', 'pekerjaan']);

        // ── ID lansia yang sudah skrining di jadwal hari ini ────────
        $sudahSkriningIds = [];
        if ($jadwal) {
            $sudahSkriningIds = Skrining::where('id_jadwal_posyandu', $jadwal->id_jadwal_posyandu)
                ->pluck('id_lansia')
                ->toArray();
        }

        // ── Pisahkan: belum vs sudah ────────────────────────────────
        $lansia = $semua->whereNotIn('id_lansia', $sudahSkriningIds)->values();
        $sudahSkrining = $semua->whereIn('id_lansia', $sudahSkriningIds)->values();

        // Ambil tinggi badan terakhir untuk lansia yang belum skrining hari ini
        $lansiaIds = $lansia->pluck('id_lansia')->toArray();
        $tbTerakhir = skrining_kunjungan::join('skrining', 'skrining.id_skrining', '=', 'skrining_kunjungan.id_skrining')
            ->whereIn('skrining.id_lansia', $lansiaIds)
            ->whereNotNull('skrining_kunjungan.tinggi_badan')
            ->select('skrining.id_lansia', 'skrining_kunjungan.tinggi_badan', 'skrining.created_at')
            ->orderBy('skrining.created_at', 'desc')
            ->get()
            ->groupBy('id_lansia')
            ->map(function ($items) {
                return $items->first()->tinggi_badan;
            });

        $obat = Obat::where('stock', '>', 0)->orderBy('nama_obat')->get();

        // 👇 TAMBAHKAN INI: Definisikan urutan langkah wizard
        // (Pastikan nama-nama ini sesuai dengan ID <div class="step"> di file Blade Anda)
        $stepIds = [
            'step-lansia',
            'step-riwayat',
            'step-pemeriksaan',
            'step-keluhan', // Hapus ini jika di form Anda tidak ada tab keluhan tersendiri
            'step-review',
        ];

        // 👇 TAMBAHKAN 'stepIds' ke dalam compact()
        return view('admin.skrining.index', compact(
            'jadwal',
            'aktifSkrining',
            'lansia',
            'sudahSkrining',
            'obat',
            'stepIds',
            'tbTerakhir'
        ));
    }
public function store(Request $request)
{
    $jadwal = $this->getJadwalHariIni();

    if (! $jadwal) {
        return $this->errorBack('Tidak ada jadwal posyandu aktif hari ini.');
    }

    $sudah = Skrining::where('id_jadwal_posyandu', $jadwal->id_jadwal_posyandu)
        ->where('id_lansia', $request->id_lansia)
        ->exists();

    if ($sudah) {
        return $this->errorBack('Lansia ini sudah melakukan skrining pada jadwal hari ini.');
    }

    $aktifSkrining = $jadwal->detailSkrining
        ->pluck('jenis_skrining')
        ->map(fn ($j) => (int) $j)
        ->toArray();

    // Validasi umum
    $request->validate([
        'id_lansia'  => 'required|exists:lansia,id_lansia',
        'keluhan'    => 'nullable|string|max:1000',
        'diagnosis'  => 'nullable|string|max:1000',
    ]);

    $lansiaTerpilih = Lansia::select('id_lansia', 'pekerjaan', 'tanggal_lahir', 'jenis_kelamin')
        ->findOrFail($request->id_lansia);

    $genderLansia     = $lansiaTerpilih->jenis_kelamin;
    $pumaJenisKelamin = $genderLansia === 'L' ? 1 : ($genderLansia === 'P' ? 0 : null);

    if ($pumaJenisKelamin === null) {
        throw ValidationException::withMessages([
            'id_lansia' => ['Jenis kelamin lansia tidak valid.'],
        ]);
    }

    // Validasi kunjungan rutin — SELALU ada karena selalu ada di form
    $request->validate([
        'berat_badan'   => 'required|numeric|min:1|max:300',
        'tinggi_badan'  => 'required|numeric|min:50|max:250',
        'lingkar_perut' => 'required|numeric|min:30|max:200',
        'td_sistolik'   => 'required|integer|min:50|max:300',
        'td_diastolik'  => 'required|integer|min:30|max:200',
    ]);

    if (in_array(DetailSkrining::SKRINING_UTAMA, $aktifSkrining)) {
        $request->validate([
            'gula_darah'                     => 'required|numeric|min:1|max:1000',
            'kolesterol'                     => 'required|numeric|min:1|max:1000',
            'merokok'                        => 'required|in:0,1',
            'merokok_kategori'               => 'required_if:merokok,1|nullable|in:1,2',
            'paparan_asap_rokok'             => 'required|in:0,1',
            'paparan_asap_rokok_frekuensi'   => 'required_if:paparan_asap_rokok,1|nullable|in:1,2',
            'konsumsi_alkohol'               => 'required|in:1,2,3',
            'konsumsi_gula'                  => 'required|in:1,2,3',
            'konsumsi_garam'                 => 'required|in:1,2,3',
            'konsumsi_minyak'                => 'required|in:1,2,3',
            'konsumsi_sayur_buah'            => 'required|in:1,2,3',
            'aktivitas_fisik'                => 'required|in:1,2,3',
            'riwayat_penyakit_keluarga'      => 'nullable|array',
            'riwayat_penyakit_keluarga.*'    => 'in:diabetes,hipertensi,jantung,stroke,asma,kanker,kolesterol,ppok,talasemia,lupus,gangguan_penglihatan',
            'riwayat_penyakit_sendiri'       => 'nullable|array',
            'riwayat_penyakit_sendiri.*'     => 'in:diabetes,hipertensi,jantung,stroke,asma,kanker,kolesterol,ppok,talasemia,lupus,gangguan_penglihatan,gangguan_pendengaran,disabilitas',
            'skrining_penglihatan'           => 'nullable|array',
            'skrining_penglihatan.*'         => 'in:katarak,pteregium,kelainan_refraksi,ulkus,conjungtivitis,glaukoma,retinopati,normal',
            'skrining_pendengaran'           => 'nullable|array',
            'skrining_pendengaran.*'         => 'in:serumen_prop,omp,omk,tajam_pendengaran,presbikusis,congek,normal',
        ]);

        if ($genderLansia === 'L' && $request->filled('iva_sadanis')) {
            throw ValidationException::withMessages([
                'iva_sadanis' => ['IVA / Sadanis tidak boleh diisi untuk lansia laki-laki.'],
            ]);
        }

        if ($genderLansia === 'P') {
            $request->validate(['iva_sadanis' => 'required|in:0,1']);
        }
    }

    if (in_array(DetailSkrining::SKRINING_PPOK, $aktifSkrining)) {
        $request->validate([
            'status_vaksinasi_covid'           => 'required|in:1,2,3',
            'kurang_aktivitas_fisik'            => 'required|in:0,1',
            'kurang_sayur_buah'                 => 'required|in:0,1',
            'merokok_ppok'                      => 'required|in:0,1',
            'jenis_rokok'                       => 'required_if:merokok_ppok,1|nullable|in:1,2,3,4',
            'konsumsi_alkohol_ppok'             => 'required|in:0,1',
            'rapid_antigen'                     => 'required|in:0,1',
            'kadar_co_ppm'                      => 'required|numeric|min:0|max:1000',
            'puma_rokok_per_hari'               => 'required_if:merokok_ppok,1|nullable|integer|min:1|max:200',
            'puma_lama_merokok_tahun'           => 'required_if:merokok_ppok,1|nullable|integer|min:1|max:80',
            'puma_napas_pendek'                 => 'required|in:0,1',
            'puma_sulit_dahak'                  => 'required|in:0,1',
            'puma_batuk_tanpa_flu'              => 'required|in:0,1',
            'puma_pernah_spirometri'            => 'required|in:0,1',
            'puma_kategori_usia'                => 'nullable|in:0,1,2',
            'puma_jenis_kelamin'                => 'nullable|in:0,1',
            'riwayat_penyakit_keluarga_ppok'    => 'nullable|array',
            'riwayat_penyakit_keluarga_ppok.*'  => 'in:diabetes,hipertensi,jantung,stroke,kanker,thalasemia',
            'riwayat_penyakit_sendiri_ppok'     => 'nullable|array',
            'riwayat_penyakit_sendiri_ppok.*'   => 'in:diabetes,hipertensi,jantung,stroke,kanker,asma,kolesterol_tinggi,ppok,thalasemia,lupus,gangguan_penglihatan,gangguan_pendengaran,disabilitas',
        ]);

        if ($request->filled('puma_jenis_kelamin') &&
            (int) $request->puma_jenis_kelamin !== $pumaJenisKelamin) {
            throw ValidationException::withMessages([
                'puma_jenis_kelamin' => ['Jenis kelamin PUMA tidak sesuai dengan data lansia.'],
            ]);
        }

        if (in_array(DetailSkrining::SKRINING_UTAMA, $aktifSkrining) &&
            $request->filled('merokok') && $request->filled('merokok_ppok') &&
            $request->merokok !== $request->merokok_ppok) {
            throw ValidationException::withMessages([
                'merokok_ppok' => ['Status merokok PPOK harus sama dengan Skrining Utama.'],
            ]);
        }
    }

    if ($request->filled('ada_resep')) {
        $request->validate([
            'resep.*.id_obat'        => 'required|exists:obat,id_obat',
            'resep.*.dosis'          => 'required|string|max:100',
            'resep.*.jenis_jadwal'   => 'required|in:harian,hari_tertentu',
            'resep.*.frekuensi'      => 'required|integer|min:1',
            'resep.*.hari_konsumsi'  => 'required_if:resep.*.jenis_jadwal,hari_tertentu|array',
            'resep.*.hari_konsumsi.*'=> 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'resep.*.jumlah_obat'    => 'required|integer|min:1',
        ]);
    }

    // ── Jalankan semua penyimpanan dalam satu transaksi ──────────
    // PENTING: $genderLansia dan $pumaJenisKelamin ikut di-capture
    DB::transaction(function () use (
        $request, $jadwal, $aktifSkrining,
        $lansiaTerpilih, $genderLansia, $pumaJenisKelamin   // ← fix utama
    ) {
        $idPetugas = auth()->user()?->petugas?->id_petugas;

        $bb  = $request->berat_badan;
        $tb  = $request->tinggi_badan;
        $lp  = $request->lingkar_perut;
        $tds = $request->td_sistolik;
        $tdd = $request->td_diastolik;
        $imt = ($bb && $tb) ? round($bb / pow($tb / 100, 2), 2) : null;

        // 1. Header Skrining
        $skrining = Skrining::create([
            'id_lansia'           => $request->id_lansia,
            'id_petugas'          => $idPetugas,
            'id_jadwal_posyandu'  => $jadwal->id_jadwal_posyandu,
            'tanggal_skrining'    => Carbon::today(),
            'keluhan'             => $request->keluhan,
        ]);

        // 2. KUNJUNGAN RUTIN — selalu disimpan (field selalu ada di form)
        skrining_kunjungan::create([
            'id_skrining'  => $skrining->id_skrining,
            'berat_badan'  => $bb,
            'tinggi_badan' => $tb,
            'imt'          => $imt,
            'lingkar_perut'=> $lp,
            'td_sistolik'  => $tds,
            'td_diastolik' => $tdd,
            'keluhan'      => $request->keluhan,
            'diagnosis'    => $request->diagnosis,
        ]);

        // Resep obat (opsional)
        if ($request->filled('ada_resep') && ! empty($request->resep)) {
            $resep = Resep::create([
                'id_skrining' => $skrining->id_skrining,
                'id_petugas'  => $idPetugas,
                'catatan'     => $request->catatan_resep,
            ]);

            $hariOrder = ['senin'=>1,'selasa'=>2,'rabu'=>3,'kamis'=>4,'jumat'=>5,'sabtu'=>6,'minggu'=>7];
            foreach ($request->resep as $r) {
                if (empty($r['id_obat'])) continue; // skip baris kosong

                $hariKonsumsi = null;
                if (($r['jenis_jadwal'] ?? 'harian') === 'hari_tertentu') {
                    $rawHari = array_map('strtolower', $r['hari_konsumsi'] ?? []);
                    usort($rawHari, fn($a,$b) => ($hariOrder[$a]??99) <=> ($hariOrder[$b]??99));
                    $hariKonsumsi = array_values(array_unique($rawHari));
                }

                $jumlahObat = $r['jumlah_obat'] ?? 1;
                $obat = Obat::find($r['id_obat']);

                if ($obat && $obat->stock < $jumlahObat) {
                    throw ValidationException::withMessages([
                        'stok' => ["Stok obat {$obat->nama_obat} tidak mencukupi (Sisa: {$obat->stock})."],
                    ]);
                }

                if ($obat) {
                    $obat->decrement('stock', $jumlahObat);
                }

                DetailResep::create([
                    'id_resep'     => $resep->id_resep,
                    'id_obat'      => $r['id_obat'],
                    'dosis'        => $r['dosis'],
                    'jenis_jadwal' => $r['jenis_jadwal'] ?? 'harian',
                    'frekuensi'    => $r['frekuensi'],
                    'durasi_hari'  => $r['durasi_hari'] ?? null,
                    'hari_konsumsi'=> $hariKonsumsi,
                    'jumlah_obat'  => $jumlahObat,
                    'keterangan'   => $r['keterangan'] ?? null,
                ]);

                $mutasi = MutasiStokObat::whereDate('created_at', now())
                    ->where('id_obat', $obat->id_obat)
                    ->where('tipe', 'keluar')
                    ->first();

                if ($mutasi) {
                    $mutasi->jumlah   += $jumlahObat;
                    $mutasi->id_resep  = $resep->id_resep;
                    $mutasi->save();
                } else {
                    MutasiStokObat::create([
                        'id_obat'    => $obat->id_obat,
                        'id_resep'   => $resep->id_resep,
                        'tipe'       => 'keluar',
                        'jumlah'     => $jumlahObat,
                        'keterangan' => 'Resep obat (Skrining)',
                    ]);
                }
            }
        }

        // 3. SKRINING UTAMA
        if (in_array(DetailSkrining::SKRINING_UTAMA, $aktifSkrining)) {
            $srqTotal = 0;
            $srqData  = [];
            for ($i = 1; $i <= 20; $i++) {
                $val             = $request->has("srq_{$i}") ? 1 : 0;
                $srqData["srq_{$i}"] = $val;
                $srqTotal       += $val;
            }

            $gulaDarah          = $request->gula_darah;
            $kolesterol         = $request->kolesterol;
            $gulaDarahKategori  = $gulaDarah  ? ($gulaDarah  < 145 ? 1 : ($gulaDarah  < 200 ? 2 : 3)) : null;
            $kolesterolKategori = $kolesterol ? ($kolesterol < 150 ? 1 : ($kolesterol < 190 ? 2 : 3)) : null;

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
                'riwayat_penyakit_keluarga' => $request->riwayat_penyakit_keluarga
                                                ? json_encode($request->riwayat_penyakit_keluarga) : null,
                'riwayat_penyakit_sendiri'  => $request->riwayat_penyakit_sendiri
                                                ? json_encode($request->riwayat_penyakit_sendiri)  : null,
                'gula_darah'                => $gulaDarah,
                'gula_darah_kategori'       => $gulaDarahKategori,
                'kolesterol'                => $kolesterol,
                'kolesterol_kategori'       => $kolesterolKategori,
                'iva_sadanis'               => $genderLansia === 'P' ? $request->iva_sadanis : null,
                'srq_total'                 => $srqTotal,
                'skrining_penglihatan'      => $request->skrining_penglihatan
                                                ? json_encode($request->skrining_penglihatan) : null,
                'skrining_pendengaran'      => $request->skrining_pendengaran
                                                ? json_encode($request->skrining_pendengaran) : null,
            ], $srqData));
        }

        // 4. SKRINING PPOK
        if (in_array(DetailSkrining::SKRINING_PPOK, $aktifSkrining)) {
            $rph       = (int) $request->puma_rokok_per_hari;
            $lmt       = (int) $request->puma_lama_merokok_tahun;
            $packYears = ($lmt > 0 && $rph > 0) ? round(($lmt * $rph) / 20, 2) : null;

            $skorMerokok = match (true) {
                $rph === 0                              => 0,
                $packYears !== null && $packYears <= 30 => 1,
                default                                 => 2,
            };

            $computedKategori = null;
            if (! empty($lansiaTerpilih->tanggal_lahir)) {
                try {
                    $age = Carbon::parse($lansiaTerpilih->tanggal_lahir)->age;
                    $computedKategori = $age >= 60 ? 2 : ($age >= 50 ? 1 : ($age >= 40 ? 0 : null));
                } catch (\Exception $e) {}
            }

            $pumaKategori = $request->filled('puma_kategori_usia')
                ? (int) $request->puma_kategori_usia
                : $computedKategori;

            $pumaSkor  = (int) $pumaJenisKelamin;
            $pumaSkor += (int) ($pumaKategori ?? 0);
            $pumaSkor += (int) $skorMerokok;
            $pumaSkor += (int) ($request->puma_napas_pendek      ?? 0);
            $pumaSkor += (int) ($request->puma_sulit_dahak       ?? 0);
            $pumaSkor += (int) ($request->puma_batuk_tanpa_flu   ?? 0);
            $pumaSkor += (int) ($request->puma_pernah_spirometri ?? 0);

            $rasioVepKvpPre  = ($request->vep1_pre  && $request->kvp_pre)
                ? round(($request->vep1_pre  / $request->kvp_pre)  * 100, 2) : null;
            $rasioVepKvpPost = ($request->vep1_post && $request->kvp_post)
                ? round(($request->vep1_post / $request->kvp_post) * 100, 2) : null;

            SkriningPPOK::create([
                'id_skrining'               => $skrining->id_skrining,
                'pekerjaan'                 => $lansiaTerpilih->pekerjaan,
                'status_vaksinasi_covid'    => $request->status_vaksinasi_covid,
                'kurang_aktivitas_fisik'    => $request->kurang_aktivitas_fisik,
                'kurang_sayur_buah'         => $request->kurang_sayur_buah,
                'merokok'                   => $request->merokok_ppok,
                'jenis_rokok'               => $request->jenis_rokok,
                'konsumsi_alkohol'          => $request->konsumsi_alkohol_ppok,
                'riwayat_penyakit_keluarga' => $request->riwayat_penyakit_keluarga_ppok
                                                ? json_encode($request->riwayat_penyakit_keluarga_ppok) : null,
                'riwayat_penyakit_sendiri'  => $request->riwayat_penyakit_sendiri_ppok
                                                ? json_encode($request->riwayat_penyakit_sendiri_ppok)  : null,
                'berat_badan'               => $bb,
                'tinggi_badan'              => $tb,
                'imt'                       => $imt,
                'lingkar_perut'             => $lp,
                'td_sistolik'               => $tds,
                'td_diastolik'              => $tdd,
                'puma_jenis_kelamin'        => $pumaJenisKelamin,
                'puma_kategori_usia'        => $pumaKategori,
                'puma_tidak_merokok'        => ($rph === 0) ? 1 : 0,
                'puma_rokok_per_hari'       => $rph,
                'puma_lama_merokok_tahun'   => $lmt,
                'puma_pack_years'           => $packYears,
                'puma_skor_merokok'         => $skorMerokok,
                'puma_napas_pendek'         => $request->puma_napas_pendek,
                'puma_sulit_dahak'          => $request->puma_sulit_dahak,
                'puma_batuk_tanpa_flu'      => $request->puma_batuk_tanpa_flu,
                'puma_pernah_spirometri'    => $request->puma_pernah_spirometri,
                'puma_total_skor'           => $pumaSkor,
                'puma_kategori_hasil'       => $pumaSkor >= 6 ? 1 : 0,
                'rapid_antigen'             => $request->rapid_antigen,
                'kadar_co_ppm'              => $request->kadar_co_ppm,
                'vep1_pre'                  => $request->vep1_pre,
                'kvp_pre'                   => $request->kvp_pre,
                'rasio_vep1_kvp_pre'        => $rasioVepKvpPre,
                'pemberian_bronkodilator'   => $request->pemberian_bronkodilator,
                'vep1_post'                 => $request->vep1_post,
                'kvp_post'                  => $request->kvp_post,
                'rasio_vep1_kvp_post'       => $rasioVepKvpPost,
                'hasil_spirometri'          => $request->hasil_spirometri,
            ]);
        }

        // 5. Saran baru
        if ($request->filled('saran')) {
            foreach ($request->saran as $s) {
                if (! empty($s['jenis_saran']) && ! empty($s['isi_saran'])) {
                    Saran::create([
                        'id_lansia'  => $request->id_lansia,
                        'jenis_saran'=> $s['jenis_saran'],
                        'isi_saran'  => $s['isi_saran'],
                    ]);
                }
            }
        }

        // 6. Update saran lama
        if ($request->filled('saran_edit')) {
            foreach ($request->saran_edit as $id => $data) {
                if (! empty($data['jenis_saran']) && ! empty($data['isi_saran'])) {
                    Saran::where('id_saran', $id)->update([
                        'jenis_saran' => $data['jenis_saran'],
                        'isi_saran'   => $data['isi_saran'],
                    ]);
                }
            }
        }

        // 7. Update status jadwal
        if ($jadwal->status === JadwalPosyandu::STATUS_TERJADWAL) {
            $jadwal->update(['status' => JadwalPosyandu::STATUS_BERLANGSUNG]);
        }

        // 8. FCM notification
        try {
            $userLansia = User::whereHas('lansia', fn($q) => $q->where('id_lansia', $request->id_lansia))->first();
            if ($userLansia && $userLansia->fcm_token) {
                $userLansia->notifyFcm(
                    'Hasil Pemeriksaan Tersedia',
                    'Hasil pemeriksaan Anda hari ini sudah dapat dilihat di aplikasi.',
                    ['type' => 'skrining_baru', 'id' => $skrining->id_skrining]
                );
            }
        } catch (\Exception $e) {
            Log::error('FCM Skrining Error: ' . $e->getMessage());
        }

        // 9. Clear cache
        foreach (['dash_total_lansia','dash_resiko_tinggi','dash_pemeriksaan_selesai',
                  'dash_penyakit_counts','dash_riwayat_terakhir','dash_lansia_checked'] as $key) {
            Cache::forget($key);
        }
    });

    return redirect()->route('skrining.index')->with('success', 'Skrining berhasil disimpan.');
}
    // ─── Helpers ──────────────────────────────────────────────────
    private function getJadwalHariIni(): ?JadwalPosyandu
    {
        // 1. Paksa sistem membaca tanggal hari ini berdasarkan zona waktu WIB (Jakarta)
        $today = now('Asia/Jakarta')->format('Y-m-d');

        return JadwalPosyandu::with('detailSkrining')
            // 2. Gunakan variabel $today yang sudah di-format
            ->whereDate('tanggal_pelaksanaan', $today)
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
