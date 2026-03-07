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
           Schema::create('skrining_ppok', function (Blueprint $table) {
            $table->id('id_skrining_ppok');
            $table->unsignedBigInteger('id_skrining'); // FK ke tabel skrining

            // -------------------------------------------------------
            // IDENTITAS TAMBAHAN (yang belum ada di tabel lansia/skrining)
            // -------------------------------------------------------

            // Pekerjaan: 1=TNI/POLRI, 2=PNS, 3=Karyawan Swasta,
            //            4=Buruh, 5=Petani/Nelayan, 6=Tidak Bekerja/IRT
            $table->tinyInteger('pekerjaan')->unsigned()->nullable()
                ->comment('1=TNI/POLRI, 2=PNS, 3=Karyawan Swasta, 4=Buruh, 5=Petani/Nelayan, 6=Tidak Bekerja/IRT');

            // Status vaksinasi Covid-19: 1=Vaksinasi 1, 2=Vaksinasi 2, 3=Booster 1
            $table->tinyInteger('status_vaksinasi_covid')->unsigned()->nullable()
                ->comment('1 = Vaksinasi 1, 2 = Vaksinasi 2, 3 = Booster 1');

            // -------------------------------------------------------
            // WAWANCARA FAKTOR RISIKO PTM
            // (Sesuai form bagian "WAWANCARA FAKTOR RISIKO PTM")
            // -------------------------------------------------------

            // 1. Kurang aktivitas fisik (<150 menit/minggu, 5x30 mnt/hari)
            $table->boolean('kurang_aktivitas_fisik')->nullable()
                ->comment('true = Ya (kurang aktivitas fisik), false = Tidak');

            // 2. Kurang konsumsi sayur/buah (<5 porsi/hari selama minimal 1 minggu)
            $table->boolean('kurang_sayur_buah')->nullable()
                ->comment('true = Ya (kurang konsumsi sayur/buah), false = Tidak');

            // 3. Merokok (rokok konvensional atau elektrik, setiap hari atau kadang-kadang)
            $table->boolean('merokok')->nullable()
                ->comment('true = Ya (merokok), false = Tidak');

            // Jenis rokok jika merokok: 1=Konvensional, 2=Elektrik, 3=Keduanya, 4=Lainnya
            $table->tinyInteger('jenis_rokok')->unsigned()->nullable()
                ->comment('1 = Rokok Konvensional, 2 = Rokok Elektrik, 3 = Keduanya, 4 = Lainnya');

            // 4. Konsumsi alkohol (minimal 1 kali dalam sebulan)
            $table->boolean('konsumsi_alkohol')->nullable()
                ->comment('true = Ya (konsumsi alkohol ≥1x/bulan), false = Tidak');

            // 5. Riwayat penyakit keluarga (checkbox, bisa lebih dari 1)
            // Pilihan: diabetes, hipertensi, jantung, stroke, kanker, thalasemia
            $table->json('riwayat_penyakit_keluarga')->nullable()
                ->comment('Array: diabetes, hipertensi, jantung, stroke, kanker, thalasemia');

            // 6. Riwayat penyakit diri sendiri (checkbox, bisa lebih dari 1)
            // Pilihan: diabetes, hipertensi, jantung, stroke, kanker, asma,
            //          kolesterol_tinggi, ppok, thalasemia, lupus,
            //          gangguan_penglihatan, gangguan_pendengaran, disabilitas
            $table->json('riwayat_penyakit_sendiri')->nullable()
                ->comment('Array: diabetes, hipertensi, jantung, stroke, kanker, asma, kolesterol_tinggi, ppok, thalasemia, lupus, gangguan_penglihatan, gangguan_pendengaran, disabilitas');

            // -------------------------------------------------------
            // PENGUKURAN FAKTOR RISIKO PTM
            // -------------------------------------------------------

            // Berat badan (kg)
            $table->decimal('berat_badan', 5, 1)->nullable()
                ->comment('Berat badan dalam kg, contoh: 65.5');

            // Tinggi badan (cm)
            $table->decimal('tinggi_badan', 5, 1)->nullable()
                ->comment('Tinggi badan dalam cm, contoh: 170.0');

            // IMT (kg/m²) — dihitung dari BB dan TB
            $table->decimal('imt', 5, 2)->nullable()
                ->comment('Indeks Massa Tubuh dalam kg/m², dihitung otomatis dari BB dan TB');

            // Lingkar perut (cm)
            $table->decimal('lingkar_perut', 5, 1)->nullable()
                ->comment('Lingkar perut dalam cm');

            // Tekanan darah diastolik (mmHg)
            $table->unsignedSmallInteger('td_diastolik')->nullable()
                ->comment('Tekanan darah diastolik dalam mmHg, contoh: 80');

            // Tekanan darah sistolik (mmHg)
            $table->unsignedSmallInteger('td_sistolik')->nullable()
                ->comment('Tekanan darah sistolik dalam mmHg, contoh: 120');

            // -------------------------------------------------------
            // WAWANCARA PUMA
            // (Kuesioner deteksi dini PPOK - skor diakumulasikan)
            // -------------------------------------------------------

            // 1. Jenis kelamin: 0=Perempuan, 1=Laki-laki
            $table->tinyInteger('puma_jenis_kelamin')->unsigned()->nullable()
                ->comment('0 = Perempuan (skor 0), 1 = Laki-laki (skor 1)');

            // 2. Usia dalam tahun
            // Kategori skor: 0=40-49 th, 1=50-59 th, 2=≥60 th
            $table->tinyInteger('puma_kategori_usia')->unsigned()->nullable()
                ->comment('0 = 40-49 tahun, 1 = 50-59 tahun, 2 = ≥60 tahun');

            // 3a. Status merokok: tidak merokok atau <20 bungkus seumur hidup atau <1 rokok/hari selama 1 tahun
            $table->boolean('puma_tidak_merokok')->nullable()
                ->comment('true = Tidak merokok / merokok sangat ringan (nilai 0)');

            // 3b. Jika merokok: rata-rata jumlah rokok/hari
            $table->unsignedSmallInteger('puma_rokok_per_hari')->nullable()
                ->comment('Rata-rata jumlah batang rokok per hari');

            // 3b. Lama merokok (tahun)
            $table->unsignedSmallInteger('puma_lama_merokok_tahun')->nullable()
                ->comment('Lama merokok dalam tahun');

            // 3c. Pack years = (lama merokok × jumlah batang/hari) / 20
            $table->decimal('puma_pack_years', 6, 2)->nullable()
                ->comment('Perhitungan pack years: (tahun merokok × batang/hari) / 20');

            // Kategori pack years untuk skor PUMA:
            // 0 = tidak merokok / <20 bungkus seumur hidup
            // 1 = 20-30 bungkus/tahun
            // 2 = >30 bungkus/tahun
            $table->tinyInteger('puma_skor_merokok')->unsigned()->nullable()
                ->comment('0 = Tidak/<20 bungkus seumur hidup, 1 = 20-30 bungkus/tahun, 2 = >30 bungkus/tahun');

            // 4. Napas pendek saat jalan cepat/datar/menanjak: 0=Tidak, 1=Ya
            $table->tinyInteger('puma_napas_pendek')->unsigned()->nullable()
                ->comment('0 = Tidak, 1 = Ya — pernah merasa napas pendek saat jalan cepat/datar/menanjak');

            // 5. Kesulitan mengeluarkan dahak saat tidak flu: 0=Tidak, 1=Ya
            $table->tinyInteger('puma_sulit_dahak')->unsigned()->nullable()
                ->comment('0 = Tidak, 1 = Ya — biasanya sulit mengeluarkan dahak saat tidak flu');

            // 6. Batuk saat tidak flu: 0=Tidak, 1=Ya
            $table->tinyInteger('puma_batuk_tanpa_flu')->unsigned()->nullable()
                ->comment('0 = Tidak, 1 = Ya — biasanya batuk saat tidak menderita flu');

            // 7. Pernah diminta periksa spirometri/peakflow: 0=Tidak, 1=Ya
            $table->tinyInteger('puma_pernah_spirometri')->unsigned()->nullable()
                ->comment('0 = Tidak, 1 = Ya — pernah diminta dokter/nakes periksa fungsi paru');

            // Total skor PUMA (akumulasi jawaban 1–7)
            // Interpretasi: <6 = Edukasi gaya hidup; ≥6 = Risiko PPOK, lakukan spirometri
            $table->tinyInteger('puma_total_skor')->unsigned()->nullable()
                ->comment('Total skor PUMA. <6 = Edukasi gaya hidup sehat, ≥6 = Risiko PPOK (perlu spirometri)');

            // Kategori hasil PUMA: 0 = Skor <6, 1 = Skor ≥6
            $table->tinyInteger('puma_kategori_hasil')->unsigned()->nullable()
                ->comment('0 = Skor <6 (Edukasi), 1 = Skor ≥6 (Risiko PPOK)');

            // -------------------------------------------------------
            // PEMERIKSAAN SPIROMETRI
            // -------------------------------------------------------

            // 1. Hasil tes rapid antigen
            $table->boolean('rapid_antigen')->nullable()
                ->comment('true = Positif, false = Negatif');

            // 2. Kadar CO pernapasan (ppm)
            $table->unsignedSmallInteger('kadar_co_ppm')->nullable()
                ->comment('Kadar CO pernapasan dalam ppm (parts per million)');

            // 3. Sebelum bronkodilator
            // VEP1 = Volume Ekspirasi Paksa detik pertama (liter)
            $table->decimal('vep1_pre', 6, 2)->nullable()
                ->comment('Nilai VEP1 sebelum bronkodilator dalam liter');

            // KVP = Kapasitas Vital Paksa (liter)
            $table->decimal('kvp_pre', 6, 2)->nullable()
                ->comment('Nilai KVP sebelum bronkodilator dalam liter');

            // Rasio VEP1/KVP sebelum bronkodilator (%)
            $table->decimal('rasio_vep1_kvp_pre', 5, 2)->nullable()
                ->comment('Rasio VEP1/KVP sebelum bronkodilator dalam persen (%)');

            // 3. Pemberian bronkodilator
            $table->boolean('pemberian_bronkodilator')->nullable()
                ->comment('true = Ya (diberikan bronkodilator), false = Tidak');

            // 4. Setelah bronkodilator
            $table->decimal('vep1_post', 6, 2)->nullable()
                ->comment('Nilai VEP1 setelah bronkodilator dalam liter');

            $table->decimal('kvp_post', 6, 2)->nullable()
                ->comment('Nilai KVP setelah bronkodilator dalam liter');

            // Rasio VEP1/KVP setelah bronkodilator (%)
            $table->decimal('rasio_vep1_kvp_post', 5, 2)->nullable()
                ->comment('Rasio VEP1/KVP setelah bronkodilator dalam persen (%)');

            // Hasil/kesimpulan pemeriksaan spirometri (teks bebas dari petugas)
            $table->text('hasil_spirometri')->nullable()
                ->comment('Kesimpulan hasil pemeriksaan spirometri oleh petugas');

            // -------------------------------------------------------
            // RELASI & TIMESTAMPS
            // -------------------------------------------------------
            $table->timestamps();

            $table->foreign('id_skrining')
                ->references('id_skrining')
                ->on('skrining')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
