<?php

namespace App\Helpers;

/**
 * SkriningHelper
 *
 * Helper pengkategorian & label untuk Skrining Utama dan PPOK.
 * Semua mapping divalidasi terhadap:
 *   - Struktur DB (posyandu_lansia.sql) — komentar kolom sebagai acuan nilai
 *   - Formulir resmi PDF (Instrumen Uji Coba PPOK 2023 Kemenkes)
 *   - Kartu Skrining POSBINDU PTM (DOCX)
 */
class SkriningHelper
{
    // ═══════════════════════════════════════════════════════════════
    // SKRINING UTAMA
    // ═══════════════════════════════════════════════════════════════

    /**
     * Mengembalikan struktur lengkap semua field Skrining Utama
     * yang dikelompokkan per seksi, lengkap dengan label tampilan
     * dan fungsi decode nilai → teks.
     *
     * Format: [ 'Nama Seksi' => [ field => ['label' => ..., 'decode' => fn($v)] ] ]
     */
    public static function utamaSchema(): array
    {
        return [

            // ──────────────────────────────────────────────────────
            // A. ANAMNESIS
            // DB: merokok (bool), merokok_kategori (1-2),
            //     paparan_asap_rokok (bool), paparan_asap_rokok_frekuensi (1-2),
            //     riwayat_penyakit_keluarga (json), riwayat_penyakit_sendiri (json)
            // ──────────────────────────────────────────────────────
            'A. Anamnesis' => [
                'merokok' => [
                    'label'  => 'Merokok',
                    'decode' => fn($v) => self::yaTidak($v),
                ],
                'merokok_kategori' => [
                    'label'  => 'Kategori Merokok',
                    // DB: 1 = 20-30 bungkus/tahun, 2 = >30 bungkus/tahun
                    // Kartu POSBINDU: "YA, 1. 20-30 bungkus/tahun 2. >30 bungkus/tahun"
                    'decode' => fn($v) => match((int)$v) {
                        1 => '20–30 bungkus/tahun',
                        2 => '>30 bungkus/tahun',
                        default => '-',
                    },
                ],
                'paparan_asap_rokok' => [
                    'label'  => 'Paparan Asap Rokok (keluarga serumah)',
                    'decode' => fn($v) => self::yaTidak($v),
                ],
                'paparan_asap_rokok_frekuensi' => [
                    'label'  => 'Frekuensi Paparan Asap Rokok',
                    // DB: 1 = Setiap Hari, 2 = Tidak Setiap Hari
                    // Kartu POSBINDU: "YA, 1. Setiap Hari 2. Tidak Setiap Hari"
                    'decode' => fn($v) => match((int)$v) {
                        1 => 'Setiap Hari',
                        2 => 'Tidak Setiap Hari',
                        default => '-',
                    },
                ],
                'riwayat_penyakit_keluarga' => [
                    'label'  => 'Riwayat Penyakit Keluarga',
                    // DB: json array — dm, hipertensi, jantung, stroke, asma, kanker,
                    //                  kolesterol, ppok, talasemia, lupus, g_penglihatan
                    'decode' => fn($v) => self::decodeRiwayatPenyakit($v, 'utama'),
                ],
                'riwayat_penyakit_sendiri' => [
                    'label'  => 'Riwayat Penyakit Sendiri',
                    // DB: json array — nilai sama dengan riwayat_penyakit_keluarga
                    'decode' => fn($v) => self::decodeRiwayatPenyakit($v, 'utama'),
                ],
            ],

            // ──────────────────────────────────────────────────────
            // B. GAYA HIDUP
            // DB: konsumsi_gula/garam/minyak/sayur_buah/alkohol → 1=Ya, 2=Tidak, 3=Tidak setiap hari
            //     aktivitas_fisik → 1=Ya (≥150 mnt/minggu), 2=Tidak, 3=Tidak setiap hari
            // Kartu POSBINDU no. 14-19
            // ──────────────────────────────────────────────────────
            'B. Gaya Hidup' => [
                'konsumsi_gula' => [
                    'label'  => 'Konsumsi Gula (>4 sdm/hari)',
                    'decode' => fn($v) => self::yaToSetiapHari($v),
                ],
                'konsumsi_garam' => [
                    'label'  => 'Konsumsi Garam (>1 sdt/hari)',
                    'decode' => fn($v) => self::yaToSetiapHari($v),
                ],
                'konsumsi_minyak' => [
                    'label'  => 'Konsumsi Minyak (>5 sdm/hari)',
                    'decode' => fn($v) => self::yaToSetiapHari($v),
                ],
                'konsumsi_sayur_buah' => [
                    'label'  => 'Konsumsi Sayur atau Buah 500gr/hari',
                    // Catatan: ini kebalikan — "Ya" berarti CUKUP (baik)
                    'decode' => fn($v) => self::yaToSetiapHari($v),
                ],
                'aktivitas_fisik' => [
                    'label'  => 'Aktivitas Fisik (>=150 menit/minggu)',
                    'decode' => fn($v) => match((int)$v) {
                        1 => 'Ya (>=150 menit/minggu)',
                        2 => 'Tidak',
                        3 => 'Tidak setiap hari',
                        default => '-',
                    },
                ],
                'konsumsi_alkohol' => [
                    'label'  => 'Konsumsi Alkohol',
                    'decode' => fn($v) => self::yaToSetiapHari($v),
                ],
            ],

            // ──────────────────────────────────────────────────────
            // C. PEMERIKSAAN FISIK
            // DB: berat_badan (decimal 5,1), tinggi_badan (decimal 5,1),
            //     imt (decimal 5,2), lingkar_perut (decimal 5,1),
            //     td_sistolik (smallint), td_diastolik (smallint)
            // ──────────────────────────────────────────────────────
            'C. Pemeriksaan Fisik' => [
                'tinggi_badan' => [
                    'label'  => 'Tinggi Badan',
                    'decode' => fn($v) => $v ? $v . ' cm' : '-',
                ],
                'berat_badan' => [
                    'label'  => 'Berat Badan',
                    'decode' => fn($v) => $v ? $v . ' kg' : '-',
                ],
                'imt' => [
                    'label'  => 'IMT (Indeks Massa Tubuh)',
                    // Kategori IMT Kemenkes Indonesia:
                    // <17 = Sangat Kurus, 17-<18.5 = Kurus, 18.5-25 = Normal,
                    // >25-27 = Gemuk/Overweight, >27 = Obesitas
                    'decode' => fn($v) => $v ? $v . ' kg/m² (' . self::kategoriImt((float)$v) . ')' : '-',
                ],
                'lingkar_perut' => [
                    'label'  => 'Lingkar Perut',
                    'decode' => fn($v) => $v ? $v . ' cm' : '-',
                ],
                'td_sistolik' => [
                    'label'  => 'Tekanan Darah Sistolik',
                    'decode' => fn($v) => $v ? $v . ' mmHg' : '-',
                ],
                'td_diastolik' => [
                    'label'  => 'Tekanan Darah Diastolik',
                    'decode' => fn($v) => $v ? $v . ' mmHg' : '-',
                ],
            ],

            // ──────────────────────────────────────────────────────
            // D. PEMERIKSAAN BIOKIMIA
            // DB: gula_darah (smallint), gula_darah_kategori (1-3),
            //     kolesterol (smallint), kolesterol_kategori (1-3)
            // Kartu POSBINDU no. 24-25 & DB komentar kolom
            // ──────────────────────────────────────────────────────
            'D. Pemeriksaan Biokimia' => [
                'gula_darah' => [
                    'label'  => 'Gula Darah',
                    'decode' => fn($v) => $v ? $v . ' mg/dL' : '-',
                ],
                'gula_darah_kategori' => [
                    'label'  => 'Kategori Gula Darah',
                    // DB: 1=Baik (80-144), 2=Sedang (145-199), 3=Tidak Baik (>=200)
                    // Kartu: "B = 80-144, S = 145-199, TB = >=200"
                    'decode' => fn($v) => match((int)$v) {
                        1 => 'Baik (80–144 mg/dL)',
                        2 => 'Sedang (145–199 mg/dL)',
                        3 => 'Tidak Baik (>=200 mg/dL)',
                        default => '-',
                    },
                ],
                'kolesterol' => [
                    'label'  => 'Kolesterol',
                    'decode' => fn($v) => $v ? $v . ' mg/dL' : '-',
                ],
                'kolesterol_kategori' => [
                    'label'  => 'Kategori Kolesterol',
                    // DB: 1=Baik (<150), 2=Sedang (150-189), 3=Tidak Baik (>=190)
                    // Kartu: "B = <150, S = 150-189, TB = >=190"
                    'decode' => fn($v) => match((int)$v) {
                        1 => 'Baik (<150 mg/dL)',
                        2 => 'Sedang (150–189 mg/dL)',
                        3 => 'Tidak Baik (>=190 mg/dL)',
                        default => '-',
                    },
                ],
            ],

            // ──────────────────────────────────────────────────────
            // E. KESEHATAN MENTAL (SRQ-20)
            // DB: srq_1..srq_20 (tinyint: 1=Ya, 0=Tidak)
            //     srq_total (tinyint: 0-20, skor ≥6 = indikasi gangguan jiwa)
            // DOCX: 20 pertanyaan dengan label resmi
            // ──────────────────────────────────────────────────────
            'E. Kesehatan Mental (SRQ-20)' => [
                'srq_1'  => ['label' => 'SRQ 1: Sering sakit kepala',                            'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_2'  => ['label' => 'SRQ 2: Tidak nafsu makan',                              'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_3'  => ['label' => 'SRQ 3: Sulit tidur',                                    'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_4'  => ['label' => 'SRQ 4: Mudah takut',                                    'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_5'  => ['label' => 'SRQ 5: Merasa tegang, cemas, atau kuatir',              'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_6'  => ['label' => 'SRQ 6: Tangan gemetar',                                 'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_7'  => ['label' => 'SRQ 7: Pencernaan terganggu',                           'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_8'  => ['label' => 'SRQ 8: Sulit berpikir jernih',                          'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_9'  => ['label' => 'SRQ 9: Merasa tidak bahagia',                           'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_10' => ['label' => 'SRQ 10: Menangis lebih sering',                         'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_11' => ['label' => 'SRQ 11: Sulit menikmati kegiatan sehari-hari',          'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_12' => ['label' => 'SRQ 12: Sulit mengambil keputusan',                     'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_13' => ['label' => 'SRQ 13: Pekerjaan sehari-hari terganggu',               'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_14' => ['label' => 'SRQ 14: Tidak mampu melakukan hal bermanfaat',          'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_15' => ['label' => 'SRQ 15: Kehilangan minat pada berbagai hal',            'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_16' => ['label' => 'SRQ 16: Merasa tidak berharga',                         'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_17' => ['label' => 'SRQ 17: Mempunyai pikiran untuk mengakhiri hidup',      'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_18' => ['label' => 'SRQ 18: Merasa lelah sepanjang waktu',                  'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_19' => ['label' => 'SRQ 19: Rasa tidak enak di perut',                      'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_20' => ['label' => 'SRQ 20: Mudah lelah',                                   'decode' => fn($v) => self::yaTidakBiner($v)],
                'srq_total' => [
                    'label'  => 'Total Skor SRQ-20',
                    // DB: skor ≥6 = indikasi gangguan jiwa
                    'decode' => fn($v) => $v !== null
                        ? $v . ' — ' . ((int)$v >= 6 ? '⚠ Indikasi gangguan jiwa' : 'Normal')
                        : '-',
                ],
            ],

            // ──────────────────────────────────────────────────────
            // F. PEMERIKSAAN LANJUTAN
            // DB: iva_sadanis (bool: true=Positif/Dilakukan, false=Negatif/Tidak)
            //     Nullable jika laki-laki.
            // Kartu POSBINDU no. 26
            // ──────────────────────────────────────────────────────
            'F. Pemeriksaan Lanjutan' => [
                'iva_sadanis' => [
                    'label'  => 'IVA / SADANIS',
                    'decode' => fn($v) => $v === null ? 'Tidak Dilakukan / N/A'
                                       : ($v ? 'Positif' : 'Negatif'),
                ],
            ],

            // ──────────────────────────────────────────────────────
            // G. SKRINING SENSORIK
            // DB: skrining_penglihatan (json array), skrining_pendengaran (json array)
            // DOCX: tabel penglihatan & pendengaran di bagian bawah kartu
            // ──────────────────────────────────────────────────────
            'G. Skrining Sensorik' => [
                'skrining_penglihatan' => [
                    'label'  => 'Skrining Penglihatan',
                    // DB array values: katarak, pteregium, kelainan_refraksi, ulkus,
                    //                  conjungtivitis, glaukoma, retinopati, normal
                    'decode' => fn($v) => self::decodeSensorik($v, [
                        'katarak'          => 'Katarak',
                        'pteregium'        => 'Pteregium',
                        'kelainan_refraksi'=> 'Kelainan Refraksi',
                        'ulkus'            => 'Ulkus',
                        'conjungtivitis'   => 'Konjungtivitis',
                        'glaukoma'         => 'Glaukoma',
                        'retinopati'       => 'Retinopati',
                        'normal'           => 'Normal',
                    ]),
                ],
                'skrining_pendengaran' => [
                    'label'  => 'Skrining Pendengaran',
                    // DB array values: serumen_prop, omp, omk, tajam_pendengaran,
                    //                  presbikusis, congek, normal
                    'decode' => fn($v) => self::decodeSensorik($v, [
                        'serumen_prop'      => 'Serumen Prop',
                        'omp'               => 'OMP',
                        'omk'               => 'OMK',
                        'tajam_pendengaran' => 'Tajam Pendengaran',
                        'presbikusis'       => 'Presbikusis',
                        'congek'            => 'Congek',
                        'normal'            => 'Normal',
                    ]),
                ],
            ],
        ];
    }

    // ═══════════════════════════════════════════════════════════════
    // SKRINING PPOK
    // ═══════════════════════════════════════════════════════════════

    /**
     * Mengembalikan struktur lengkap semua field Skrining PPOK
     * yang dikelompokkan per seksi sesuai formulir resmi PDF Kemenkes 2023.
     */
    public static function ppokSchema(): array
    {
        return [

            // ──────────────────────────────────────────────────────
            // A. IDENTITAS & RIWAYAT
            // DB: pekerjaan (1-6), status_vaksinasi_covid (1-3),
            //     riwayat_penyakit_keluarga (json), riwayat_penyakit_sendiri (json)
            // PDF: Bagian Identitas Responden & Wawancara Faktor Risiko PTM
            // ──────────────────────────────────────────────────────
            'A. Identitas & Riwayat' => [
                'pekerjaan' => [
                    'label'  => 'Pekerjaan',
                    // DB: 1=TNI/POLRI, 2=PNS, 3=Karyawan Swasta, 4=Buruh,
                    //     5=Petani/Nelayan, 6=Tidak Bekerja/IRT
                    // PDF: checkbox pekerjaan 6 pilihan
                    'decode' => fn($v) => match((int)$v) {
                        1 => 'TNI/POLRI',
                        2 => 'PNS',
                        3 => 'Karyawan Swasta',
                        4 => 'Buruh',
                        5 => 'Petani/Nelayan',
                        6 => 'Tidak Bekerja/IRT',
                        default => '-',
                    },
                ],
                'status_vaksinasi_covid' => [
                    'label'  => 'Status Vaksinasi COVID-19',
                    // DB: 1=Vaksinasi 1, 2=Vaksinasi 2, 3=Booster 1
                    // PDF: checkbox "Vaksinasi 1 / Vaksinasi 2 / Booster 1"
                    'decode' => fn($v) => match((int)$v) {
                        1 => 'Vaksinasi 1',
                        2 => 'Vaksinasi 2',
                        3 => 'Booster 1',
                        default => '-',
                    },
                ],
                'riwayat_penyakit_keluarga' => [
                    'label'  => 'Riwayat Penyakit Keluarga',
                    // DB: json array — diabetes, hipertensi, jantung, stroke,
                    //                  kanker, thalasemia
                    // PDF: 5 pilihan checkbox
                    'decode' => fn($v) => self::decodeRiwayatPenyakit($v, 'ppok'),
                ],
                'riwayat_penyakit_sendiri' => [
                    'label'  => 'Riwayat Penyakit Sendiri',
                    // DB: json array — diabetes, hipertensi, jantung, stroke, kanker,
                    //     asma, kolesterol_tinggi, ppok, thalasemia, lupus,
                    //     gangguan_penglihatan, gangguan_pendengaran, disabilitas
                    // PDF: semua checkbox riwayat penyakit sendiri
                    'decode' => fn($v) => self::decodeRiwayatPenyakit($v, 'ppok_sendiri'),
                ],
            ],

            // ──────────────────────────────────────────────────────
            // B. FAKTOR RISIKO PTM
            // DB: merokok (bool), jenis_rokok (1-4),
            //     kurang_aktivitas_fisik (bool), kurang_sayur_buah (bool),
            //     konsumsi_alkohol (bool), kadar_co_ppm (smallint),
            //     rapid_antigen (bool)
            // PDF: Wawancara Faktor Risiko PTM (nomor 1-4)
            // ──────────────────────────────────────────────────────
            'B. Faktor Risiko PTM' => [
                'kurang_aktivitas_fisik' => [
                    'label'  => 'Kurang Aktivitas Fisik (<150 mnt/minggu)',
                    // DB: true=Ya (kurang), false=Tidak
                    // PDF: no. 1 "Kurang aktivitas fisik: jika < 150 menit/minggu"
                    'decode' => fn($v) => self::yaTidak($v),
                ],
                'kurang_sayur_buah' => [
                    'label'  => 'Kurang Konsumsi Sayur & Buah (<5 porsi/hari)',
                    // DB: true=Ya (kurang), false=Tidak
                    // PDF: no. 2 "Kurang konsumsi sayur/buah: < 5 porsi/hari selama minimal 1 minggu"
                    'decode' => fn($v) => self::yaTidak($v),
                ],
                'merokok' => [
                    'label'  => 'Merokok',
                    // DB: true=Ya, false=Tidak
                    // PDF: no. 3 "Merokok: konsumsi rokok konvensional atau elektrik setiap hari/kadang"
                    'decode' => fn($v) => self::yaTidak($v),
                ],
                'jenis_rokok' => [
                    'label'  => 'Jenis Rokok',
                    // DB: 1=Konvensional, 2=Elektrik, 3=Keduanya, 4=Lainnya
                    // PDF: "Rokok Konvensional / Rokok Elektrik / Keduanya / Lainnya"
                    'decode' => fn($v) => match((int)$v) {
                        1 => 'Rokok Konvensional',
                        2 => 'Rokok Elektrik',
                        3 => 'Keduanya',
                        4 => 'Lainnya',
                        default => '-',
                    },
                ],
                'konsumsi_alkohol' => [
                    'label'  => 'Konsumsi Alkohol (≥1x/bulan)',
                    // DB: true=Ya, false=Tidak
                    // PDF: no. 4 "Konsumsi alkohol: minimal 1 kali dalam sebulan"
                    'decode' => fn($v) => self::yaTidak($v),
                ],
                'rapid_antigen' => [
                    'label'  => 'Rapid Antigen COVID-19',
                    // DB: true=Positif, false=Negatif
                    // PDF: Pemeriksaan Spirometri no. 1 "Hasil tes rapid antigen: Positif/Negatif"
                    'decode' => fn($v) => $v === null ? '-' : ($v ? 'Positif' : 'Negatif'),
                ],
                'kadar_co_ppm' => [
                    'label'  => 'Kadar CO Pernapasan',
                    // PDF: Pemeriksaan Spirometri no. 2 "Kadar CO Pernapasan: ___ ppm"
                    'decode' => fn($v) => $v !== null ? $v . ' ppm' : '-',
                ],
            ],

            // ──────────────────────────────────────────────────────
            // C. PEMERIKSAAN FISIK
            // DB: berat_badan, tinggi_badan, imt, lingkar_perut,
            //     td_sistolik, td_diastolik
            // PDF: Bagian "Pengukuran Faktor Risiko PTM"
            // ──────────────────────────────────────────────────────
            'C. Pemeriksaan Fisik' => [
                'berat_badan' => [
                    'label'  => 'Berat Badan',
                    'decode' => fn($v) => $v ? $v . ' kg' : '-',
                ],
                'tinggi_badan' => [
                    'label'  => 'Tinggi Badan',
                    'decode' => fn($v) => $v ? $v . ' cm' : '-',
                ],
                'imt' => [
                    'label'  => 'IMT (Indeks Massa Tubuh)',
                    'decode' => fn($v) => $v ? $v . ' kg/m² (' . self::kategoriImt((float)$v) . ')' : '-',
                ],
                'lingkar_perut' => [
                    'label'  => 'Lingkar Perut',
                    'decode' => fn($v) => $v ? $v . ' cm' : '-',
                ],
                'td_sistolik' => [
                    'label'  => 'Tekanan Darah Sistolik',
                    'decode' => fn($v) => $v ? $v . ' mmHg' : '-',
                ],
                'td_diastolik' => [
                    'label'  => 'Tekanan Darah Diastolik',
                    'decode' => fn($v) => $v ? $v . ' mmHg' : '-',
                ],
            ],

            // ──────────────────────────────────────────────────────
            // D. GEJALA RESPIRASI & WAWANCARA PUMA
            // DB: puma_napas_pendek (0/1), puma_sulit_dahak (0/1),
            //     puma_batuk_tanpa_flu (0/1), puma_pernah_spirometri (0/1)
            // PDF: Wawancara PUMA no. 4-7
            // ──────────────────────────────────────────────────────
            'D. Gejala Respirasi (PUMA)' => [
                'puma_napas_pendek' => [
                    'label'  => 'Napas Pendek Saat Jalan Cepat/Menanjak',
                    // DB: 0=Tidak, 1=Ya — PDF no. 4
                    'decode' => fn($v) => self::yaTidakBiner($v),
                ],
                'puma_sulit_dahak' => [
                    'label'  => 'Biasanya Sulit Mengeluarkan Dahak (bukan saat flu)',
                    // DB: 0=Tidak, 1=Ya — PDF no. 5
                    'decode' => fn($v) => self::yaTidakBiner($v),
                ],
                'puma_batuk_tanpa_flu' => [
                    'label'  => 'Biasanya Batuk Saat Tidak Menderita Flu',
                    // DB: 0=Tidak, 1=Ya — PDF no. 6
                    'decode' => fn($v) => self::yaTidakBiner($v),
                ],
                'puma_pernah_spirometri' => [
                    'label'  => 'Pernah Diminta Periksa Fungsi Paru (Spirometri)',
                    // DB: 0=Tidak, 1=Ya — PDF no. 7
                    'decode' => fn($v) => self::yaTidakBiner($v),
                ],
            ],

            // ──────────────────────────────────────────────────────
            // E. DATA SKORING PUMA LENGKAP
            // DB: puma_jenis_kelamin (0/1), puma_kategori_usia (0-2),
            //     puma_tidak_merokok (bool), puma_rokok_per_hari (smallint),
            //     puma_lama_merokok_tahun (smallint), puma_pack_years (decimal),
            //     puma_skor_merokok (0-2), puma_total_skor (tinyint),
            //     puma_kategori_hasil (0/1)
            // PDF: Wawancara PUMA no. 1-3 + Total Skor + Interpretasi
            // ──────────────────────────────────────────────────────
            'E. Skoring PUMA' => [
                'puma_jenis_kelamin' => [
                    'label'  => 'Jenis Kelamin (PUMA)',
                    // DB: 0=Perempuan (skor 0), 1=Laki-laki (skor 1)
                    // PDF: no. 1 "Jenis Kelamin: Perempuan/Laki-laki"
                    'decode' => fn($v) => match((int)$v) {
                        0 => 'Perempuan (skor: 0)',
                        1 => 'Laki-laki (skor: 1)',
                        default => '-',
                    },
                ],
                'puma_kategori_usia' => [
                    'label'  => 'Kategori Usia (PUMA)',
                    // DB: 0=40-49 tahun, 1=50-59 tahun, 2=≥60 tahun
                    // PDF: no. 2 "Usia dalam tahun: 0:40-49 / 1:50-59 / 2:≥60"
                    'decode' => fn($v) => match((int)$v) {
                        0 => '40–49 tahun (skor: 0)',
                        1 => '50–59 tahun (skor: 1)',
                        2 => '≥60 tahun (skor: 2)',
                        default => '-',
                    },
                ],
                'puma_tidak_merokok' => [
                    'label'  => 'Tidak Merokok / Merokok Sangat Ringan',
                    // DB: true=Tidak merokok (skor 0)
                    'decode' => fn($v) => $v ? 'Ya (tidak merokok)' : 'Tidak',
                ],
                'puma_rokok_per_hari' => [
                    'label'  => 'Rata-rata Rokok per Hari',
                    'decode' => fn($v) => $v !== null ? $v . ' batang/hari' : '-',
                ],
                'puma_lama_merokok_tahun' => [
                    'label'  => 'Lama Merokok',
                    'decode' => fn($v) => $v !== null ? $v . ' tahun' : '-',
                ],
                'puma_pack_years' => [
                    'label'  => 'Pack Years',
                    // PDF: "(lama merokok (tahun) × jumlah batang/hari) / 20"
                    'decode' => fn($v) => $v !== null ? $v . ' pack years' : '-',
                ],
                'puma_skor_merokok' => [
                    'label'  => 'Skor Merokok (PUMA)',
                    // DB: 0=Tidak/<20 bungkus seumur hidup,
                    //     1=20-30 bungkus/tahun, 2=>30 bungkus/tahun
                    // PDF: "0:<20 bungkus / 1:20-30 bungkus / 2:>30 bungkus"
                    'decode' => fn($v) => match((int)$v) {
                        0 => 'Tidak merokok / <20 bungkus seumur hidup (skor: 0)',
                        1 => '20–30 bungkus/tahun (skor: 1)',
                        2 => '>30 bungkus/tahun (skor: 2)',
                        default => '-',
                    },
                ],
                'puma_total_skor' => [
                    'label'  => 'Total Skor PUMA',
                    // DB: <6=Edukasi, ≥6=Risiko PPOK
                    // PDF: "Skor <6: Edukasi gaya hidup sehat, Skor ≥6: Risiko PPOK"
                    'decode' => fn($v) => $v !== null
                        ? $v . ' — ' . ((int)$v >= 6 ? '⚠ Risiko PPOK (perlu spirometri)' : 'Edukasi Gaya Hidup Sehat')
                        : '-',
                ],
                'puma_kategori_hasil' => [
                    'label'  => 'Kategori Hasil PUMA',
                    // DB: 0=Skor <6 (Edukasi), 1=Skor ≥6 (Risiko PPOK)
                    'decode' => fn($v) => match((int)$v) {
                        0 => 'Edukasi Gaya Hidup Sehat',
                        1 => 'Risiko PPOK — Lakukan Pemeriksaan Spirometri',
                        default => '-',
                    },
                ],
            ],

            // ──────────────────────────────────────────────────────
            // F. SPIROMETRI PRE-BRONKODILATOR
            // DB: vep1_pre (decimal), kvp_pre (decimal), rasio_vep1_kvp_pre (decimal)
            // PDF: Pemeriksaan Spirometri no. 3 (VEP1/KVP Pre)
            // ──────────────────────────────────────────────────────
            'F. Spirometri (Pre-Bronkodilator)' => [
                'vep1_pre' => [
                    'label'  => 'Nilai VEP1 (Pre)',
                    'decode' => fn($v) => $v !== null ? $v . ' L' : '-',
                ],
                'kvp_pre' => [
                    'label'  => 'Nilai KVP (Pre)',
                    'decode' => fn($v) => $v !== null ? $v . ' L' : '-',
                ],
                'rasio_vep1_kvp_pre' => [
                    'label'  => 'Rasio VEP1/KVP (Pre)',
                    'decode' => fn($v) => $v !== null ? $v . ' %' : '-',
                ],
            ],

            // ──────────────────────────────────────────────────────
            // G. SPIROMETRI POST-BRONKODILATOR
            // DB: pemberian_bronkodilator (bool), vep1_post, kvp_post,
            //     rasio_vep1_kvp_post, hasil_spirometri (text)
            // PDF: Pemeriksaan Spirometri no. 4 (Post + Hasil)
            // ──────────────────────────────────────────────────────
            'G. Spirometri (Post-Bronkodilator)' => [
                'pemberian_bronkodilator' => [
                    'label'  => 'Pemberian Bronkodilator',
                    // DB: true=Ya, false=Tidak
                    // PDF: "Pemberian Bronkodilator: Ya / Tidak"
                    'decode' => fn($v) => $v === null ? '-' : ($v ? 'Ya' : 'Tidak'),
                ],
                'vep1_post' => [
                    'label'  => 'Nilai VEP1 (Post)',
                    'decode' => fn($v) => $v !== null ? $v . ' L' : '-',
                ],
                'kvp_post' => [
                    'label'  => 'Nilai KVP (Post)',
                    'decode' => fn($v) => $v !== null ? $v . ' L' : '-',
                ],
                'rasio_vep1_kvp_post' => [
                    'label'  => 'Rasio VEP1/KVP (Post)',
                    'decode' => fn($v) => $v !== null ? $v . ' %' : '-',
                ],
                'hasil_spirometri' => [
                    'label'  => 'Hasil Pemeriksaan Spirometri',
                    // DB: text — kesimpulan oleh petugas
                    'decode' => fn($v) => $v ?? '-',
                ],
            ],
        ];
    }

    // ═══════════════════════════════════════════════════════════════
    // PUBLIC METHODS: Decode & Render
    // ═══════════════════════════════════════════════════════════════

    /**
     * Decode field model skrining menggunakan schema.
     * Mengembalikan teks siap tampil atau '-' jika null.
     *
     * @param  mixed   $value  Nilai field dari model
     * @param  string  $field  Nama field
     * @param  string  $type   'utama' atau 'ppok'
     */
    public static function decode(mixed $value, string $field, string $type = 'utama'): string
    {
        $schema = $type === 'ppok' ? self::ppokSchema() : self::utamaSchema();

        foreach ($schema as $fields) {
            if (isset($fields[$field])) {
                if ($value === null) return '-';
                return ($fields[$field]['decode'])($value);
            }
        }

        return $value ?? '-';
    }

    /**
     * Render semua data skrining model sebagai array [ label => nilai_decode ]
     * per seksi, siap dipakai di Blade/PDF.
     *
     * @param  object  $model  Instance SkriningUtama atau SkriningPPOK
     * @param  string  $type   'utama' atau 'ppok'
     * @return array   [ 'Nama Seksi' => [ ['label'=>..., 'value'=>...] ] ]
     */
    public static function renderAll(object $model, string $type = 'utama'): array
    {
        $schema = $type === 'ppok' ? self::ppokSchema() : self::utamaSchema();
        $result = [];

        foreach ($schema as $section => $fields) {
            $rows = [];
            foreach ($fields as $field => $def) {
                $rawValue = $model->$field ?? null;
                $rows[] = [
                    'label' => $def['label'],
                    'value' => $rawValue !== null ? ($def['decode'])($rawValue) : '-',
                    'raw'   => $rawValue,
                ];
            }
            $result[$section] = $rows;
        }

        return $result;
    }

    // ═══════════════════════════════════════════════════════════════
    // PRIVATE HELPERS: Utility decode functions
    // ═══════════════════════════════════════════════════════════════

    /** Bool Laravel (bisa int/string/bool) → Ya / Tidak */
    private static function yaTidak(mixed $v): string
    {
        if ($v === null) return '-';
        return filter_var($v, FILTER_VALIDATE_BOOLEAN) ? 'Ya' : 'Tidak';
    }

    /** Biner 0/1 → Ya / Tidak (untuk PUMA dan SRQ) */
    private static function yaTidakBiner(mixed $v): string
    {
        if ($v === null) return '-';
        return (int)$v === 1 ? 'Ya' : 'Tidak';
    }

    /** 1=Ya, 2=Tidak, 3=Tidak setiap hari */
    private static function yaToSetiapHari(mixed $v): string
    {
        return match((int)$v) {
            1 => 'Ya',
            2 => 'Tidak',
            3 => 'Tidak setiap hari',
            default => '-',
        };
    }

    /**
     * Decode JSON riwayat penyakit (sudah di-cast ke array di model).
     *
     * @param  mixed   $v    Nilai field (array atau null)
     * @param  string  $ctx  Konteks: 'utama' | 'ppok' | 'ppok_sendiri'
     */
    private static function decodeRiwayatPenyakit(mixed $v, string $ctx = 'utama'): string
    {
        if (empty($v)) return '-';

        // Label per key sesuai DB comment
        $labels = [
            // Utama: keluarga & sendiri
            'dm'                   => 'Diabetes Melitus',
            'hipertensi'           => 'Hipertensi',
            'jantung'              => 'Jantung',
            'stroke'               => 'Stroke',
            'asma'                 => 'Asma',
            'kanker'               => 'Kanker',
            'kolesterol'           => 'Kolesterol Tinggi',
            'ppok'                 => 'PPOK',
            'talasemia'            => 'Talasemia',
            'lupus'                => 'Lupus',
            'g_penglihatan'        => 'Gangguan Penglihatan',
            // PPOK sendiri (tambahan)
            'kolesterol_tinggi'    => 'Kolesterol Tinggi',
            'gangguan_penglihatan' => 'Gangguan Penglihatan',
            'gangguan_pendengaran' => 'Gangguan Pendengaran',
            'disabilitas'          => 'Disabilitas',
        ];

        $decoded = array_map(fn($key) => $labels[$key] ?? $key, (array)$v);
        return implode(', ', $decoded);
    }

    /** Decode JSON skrining sensorik dengan mapping key → label */
    private static function decodeSensorik(mixed $v, array $map): string
    {
        if (empty($v)) return '-';
        $result = [];
        foreach ((array)$v as $key) {
            $result[] = $map[$key] ?? $key;
        }
        return implode(', ', $result);
    }

    /**
     * Kategori IMT standar Kemenkes Indonesia
     * (bukan WHO, sesuai pedoman PTM Indonesia)
     */
    private static function kategoriImt(float $imt): string
    {
        return match(true) {
            $imt < 17.0   => 'Sangat Kurus',
            $imt < 18.5   => 'Kurus',
            $imt <= 25.0  => 'Normal',
            $imt <= 27.0  => 'Overweight',
            default       => 'Obesitas',
        };
    }
}
