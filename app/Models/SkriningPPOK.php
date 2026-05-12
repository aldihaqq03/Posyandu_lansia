<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkriningPPOK extends Model
{
    protected $table = 'skrining_ppok';
    protected $primaryKey = 'id_skrining_ppok';

    protected $fillable = [
        'id_skrining',
        'pekerjaan',
        'status_vaksinasi_covid',
        'kurang_aktivitas_fisik',
        'kurang_sayur_buah',
        'merokok',
        'jenis_rokok',
        'konsumsi_alkohol',
        'riwayat_penyakit_keluarga',
        'riwayat_penyakit_sendiri',
        'berat_badan',
        'tinggi_badan',
        'imt',
        'lingkar_perut',
        'td_diastolik',
        'td_sistolik',
        'puma_jenis_kelamin',
        'puma_kategori_usia',
        'puma_tidak_merokok',
        'puma_rokok_per_hari',
        'puma_lama_merokok_tahun',
        'puma_pack_years',
        'puma_skor_merokok',
        'puma_napas_pendek',
        'puma_sulit_dahak',
        'puma_batuk_tanpa_flu',
        'puma_pernah_spirometri',
        'puma_total_skor',
        'puma_kategori_hasil',
        'rapid_antigen',
        'kadar_co_ppm',
        'vep1_pre',
        'kvp_pre',
        'rasio_vep1_kvp_pre',
        'pemberian_bronkodilator',
        'vep1_post',
        'kvp_post',
        'rasio_vep1_kvp_post',
        'hasil_spirometri'
    ];

    protected $casts = [
        'riwayat_penyakit_keluarga' => 'array',
        'riwayat_penyakit_sendiri' => 'array',
    ];

    public function skrining()
    {
        return $this->belongsTo(Skrining::class, 'id_skrining');
    }
}
