<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('skrining_utama', function (Blueprint $table) {
            $table->id('id_skrining_utama');
            $table->unsignedBigInteger('id_skrining'); // FK ke tabel skrining

            // -------------------------------------------------------
            // FAKTOR RISIKO PERILAKU
            // -------------------------------------------------------

            // No.8 - Merokok: null = belum diisi, false = tidak merokok
            $table->boolean('merokok')->nullable()
                ->comment('Apakah merokok: true=Ya, false=Tidak');

            // Jika merokok = Ya, pilihan: 1=20-30 bungkus/tahun, 2=>30 bungkus/tahun
            $table->tinyInteger('merokok_kategori')->unsigned()->nullable()
                ->comment('1 = 20-30 bungkus/tahun, 2 = >30 bungkus/tahun');

            // No.13 - Paparan asap rokok orang lain (keluarga serumah)
            $table->boolean('paparan_asap_rokok')->nullable()
                ->comment('Ada keluarga serumah yang merokok/terpapar asap rokok: true=Ya, false=Tidak');

            // Jika paparan = Ya: 1=Setiap Hari, 2=Tidak Setiap Hari
            $table->tinyInteger('paparan_asap_rokok_frekuensi')->unsigned()->nullable()
                ->comment('1 = Setiap Hari, 2 = Tidak Setiap Hari');

            // No.14 - Konsumsi gula >4 sdm/hari
            // 1=Ya, 2=Tidak, 3=Tidak setiap hari
            $table->tinyInteger('konsumsi_gula')->unsigned()->nullable()
                ->comment('1 = Ya, 2 = Tidak, 3 = Tidak setiap hari');

            // No.15 - Konsumsi garam >1 cth/hari
            $table->tinyInteger('konsumsi_garam')->unsigned()->nullable()
                ->comment('1 = Ya, 2 = Tidak, 3 = Tidak setiap hari');

            // No.16 - Konsumsi makanan diolah dengan minyak >1 sdm
            $table->tinyInteger('konsumsi_minyak')->unsigned()->nullable()
                ->comment('1 = Ya, 2 = Tidak, 3 = Tidak setiap hari');

            // No.17 - Konsumsi sayur/buah 500gr/hari
            $table->tinyInteger('konsumsi_sayur_buah')->unsigned()->nullable()
                ->comment('1 = Ya (cukup), 2 = Tidak, 3 = Tidak setiap hari');

            // No.18 - Aktivitas fisik ≥150 menit/minggu
            $table->tinyInteger('aktivitas_fisik')->unsigned()->nullable()
                ->comment('1 = Ya (≥150 menit/minggu), 2 = Tidak, 3 = Tidak setiap hari');

            // No.19 - Konsumsi alkohol
            $table->tinyInteger('konsumsi_alkohol')->unsigned()->nullable()
                ->comment('1 = Ya, 2 = Tidak, 3 = Tidak setiap hari');

            // -------------------------------------------------------
            // RIWAYAT PENYAKIT
            // -------------------------------------------------------

            // No.6 - Riwayat PTM pada keluarga (bisa lebih dari 1, simpan sebagai JSON array)
            // Pilihan: DM, Hipertensi, Jantung, Stroke, Asma, Kanker,
            //          Kolesterol, PPOK, Talasemia, Lupus, Gangguan Penglihatan
            $table->json('riwayat_penyakit_keluarga')->nullable()
                ->comment('Array pilihan: dm, hipertensi, jantung, stroke, asma, kanker, kolesterol, ppok, talasemia, lupus, g_penglihatan');

            // No.7 - Riwayat PTM pada diri sendiri (bisa lebih dari 1, simpan sebagai JSON array)
            $table->json('riwayat_penyakit_sendiri')->nullable()
                ->comment('Array pilihan: dm, hipertensi, jantung, stroke, asma, kanker, kolesterol, ppok, talasemia, lupus, g_penglihatan');

            // -------------------------------------------------------
            // PENGUKURAN FISIK
            // -------------------------------------------------------

            // No.20 - Tinggi badan (cm), range wajar 50–250 cm
            $table->decimal('tinggi_badan', 5, 1)->nullable()
                ->comment('Tinggi badan dalam cm, contoh: 165.5');

            // No.21 - Berat badan (kg), range wajar 10–300 kg
            $table->decimal('berat_badan', 5, 1)->nullable()
                ->comment('Berat badan dalam kg, contoh: 60.5');

            // IMT dihitung otomatis (BB/TB²), simpan hasil hitung
            $table->decimal('imt', 5, 2)->nullable()
                ->comment('Indeks Massa Tubuh (kg/m²), dihitung dari BB dan TB');

            // No.22 - Lingkar perut (cm)
            $table->decimal('lingkar_perut', 5, 1)->nullable()
                ->comment('Lingkar perut dalam cm');

            // No.23 - Tekanan darah sistolik (mmHg)
            $table->unsignedSmallInteger('td_sistolik')->nullable()
                ->comment('Tekanan darah sistolik dalam mmHg, contoh: 120');

            // No.23 - Tekanan darah diastolik (mmHg)
            $table->unsignedSmallInteger('td_diastolik')->nullable()
                ->comment('Tekanan darah diastolik dalam mmHg, contoh: 80');

            // -------------------------------------------------------
            // PEMERIKSAAN LABORATORIUM
            // -------------------------------------------------------

            // No.24 - Gula darah (mg/dL)
            // B=80-144, S=145-199, TB=≥200
            $table->unsignedSmallInteger('gula_darah')->nullable()
                ->comment('Kadar gula darah dalam mg/dL. Baik: 80-144, Sedang: 145-199, Tidak Baik: ≥200');

            // Kategori gula darah: 1=Baik, 2=Sedang, 3=Tidak Baik (dihitung otomatis atau diisi manual)
            $table->tinyInteger('gula_darah_kategori')->unsigned()->nullable()
                ->comment('1 = Baik (80-144), 2 = Sedang (145-199), 3 = Tidak Baik (≥200)');

            // No.25 - Kolesterol (mg/dL)
            // B=<150, S=150-189, TB=≥190
            $table->unsignedSmallInteger('kolesterol')->nullable()
                ->comment('Kadar kolesterol dalam mg/dL. Baik: <150, Sedang: 150-189, Tidak Baik: ≥190');

            $table->tinyInteger('kolesterol_kategori')->unsigned()->nullable()
                ->comment('1 = Baik (<150), 2 = Sedang (150-189), 3 = Tidak Baik (≥190)');

            // -------------------------------------------------------
            // PEMERIKSAAN KHUSUS PEREMPUAN
            // -------------------------------------------------------

            // No.26 - IVA / Sadanis
            $table->boolean('iva_sadanis')->nullable()
                ->comment('Hasil IVA/Sadanis: true=Positif/Dilakukan, false=Negatif/Tidak dilakukan. Nullable jika tidak relevan (laki-laki)');

            // -------------------------------------------------------
            // SKRINING JIWA - SRQ-20
            // Simpan setiap jawaban SRQ (1=Ya, 0=Tidak)
            // -------------------------------------------------------

            $table->tinyInteger('srq_1')->unsigned()->nullable()->comment('Sering sakit kepala? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_2')->unsigned()->nullable()->comment('Tidak nafsu makan? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_3')->unsigned()->nullable()->comment('Sulit tidur? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_4')->unsigned()->nullable()->comment('Mudah takut? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_5')->unsigned()->nullable()->comment('Merasa tegang/cemas/kuatir? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_6')->unsigned()->nullable()->comment('Tangan gemetar? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_7')->unsigned()->nullable()->comment('Pencernaan terganggu? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_8')->unsigned()->nullable()->comment('Sulit berpikir jernih? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_9')->unsigned()->nullable()->comment('Merasa tidak bahagia? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_10')->unsigned()->nullable()->comment('Menangis lebih sering? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_11')->unsigned()->nullable()->comment('Sulit menikmati kegiatan sehari-hari? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_12')->unsigned()->nullable()->comment('Sulit mengambil keputusan? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_13')->unsigned()->nullable()->comment('Pekerjaan sehari-hari terganggu? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_14')->unsigned()->nullable()->comment('Tidak mampu melakukan hal bermanfaat? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_15')->unsigned()->nullable()->comment('Kehilangan minat pada berbagai hal? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_16')->unsigned()->nullable()->comment('Merasa tidak berharga? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_17')->unsigned()->nullable()->comment('Pikiran untuk mengakhiri hidup? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_18')->unsigned()->nullable()->comment('Merasa lelah sepanjang waktu? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_19')->unsigned()->nullable()->comment('Rasa tidak enak di perut? 1=Ya, 0=Tidak');
            $table->tinyInteger('srq_20')->unsigned()->nullable()->comment('Mudah lelah? 1=Ya, 0=Tidak');

            // Total skor SRQ-20 (0–20), dihitung dari srq_1 s.d srq_20
            $table->tinyInteger('srq_total')->unsigned()->nullable()
                ->comment('Total skor SRQ-20 (0-20). Skor ≥6 = indikasi gangguan jiwa');

            // -------------------------------------------------------
            // SKRINING PENGLIHATAN & PENDENGARAN
            // -------------------------------------------------------

            // Penglihatan (bisa lebih dari 1 kondisi, simpan JSON array)
            // Pilihan: katarak, pteregium, kelainan_refraksi, ulkus, conjungtivitis, glaukoma, retinopati, normal
            $table->json('skrining_penglihatan')->nullable()
                ->comment('Array kondisi penglihatan: katarak, pteregium, kelainan_refraksi, ulkus, conjungtivitis, glaukoma, retinopati, normal');

            // Pendengaran (bisa lebih dari 1 kondisi, simpan JSON array)
            // Pilihan: serumen_prop, omp, omk, tajam_pendengaran, presbikusis, congek, normal
            $table->json('skrining_pendengaran')->nullable()
                ->comment('Array kondisi pendengaran: serumen_prop, omp, omk, tajam_pendengaran, presbikusis, congek, normal');

            // -------------------------------------------------------
            // RELASI & TIMESTAMPS
            // -------------------------------------------------------
            $table->timestamps();

            $table->foreign('id_skrining')
                ->references('id_skrining')
                ->on('skrining')
                ->onDelete('cascade');
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
