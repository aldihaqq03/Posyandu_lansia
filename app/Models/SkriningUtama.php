<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkriningUtama extends Model
{
    protected $table = 'skrining_utama';
    protected $primaryKey = 'id_skrining_utama';

    protected $fillable = [
        'id_skrining',
        'id_lansia',
        'merokok',
        'merokok_kategori',
        'paparan_asap_rokok',
        'paparan_asap_rokok_frekuensi',
        'konsumsi_gula',
        'konsumsi_garam',
        'konsumsi_minyak',
        'konsumsi_sayur_buah',
        'aktivitas_fisik',
        'konsumsi_alkohol',
        'riwayat_penyakit_keluarga',
        'riwayat_penyakit_sendiri',
        'tinggi_badan',
        'berat_badan',
        'imt',
        'lingkar_perut',
        'td_sistolik',
        'td_diastolik',
        'gula_darah',
        'gula_darah_kategori',
        'kolesterol',
        'kolesterol_kategori',
        'iva_sadanis',
        'srq_1', 'srq_2', 'srq_3', 'srq_4', 'srq_5', 'srq_6', 'srq_7', 'srq_8', 'srq_9', 'srq_10',
        'srq_11', 'srq_12', 'srq_13', 'srq_14', 'srq_15', 'srq_16', 'srq_17', 'srq_18', 'srq_19', 'srq_20',
        'srq_total',
        'skrining_penglihatan',
        'skrining_pendengaran'
    ];

    protected $casts = [
        'riwayat_penyakit_keluarga' => 'array',
        'riwayat_penyakit_sendiri' => 'array',
        'skrining_penglihatan' => 'array',
        'skrining_pendengaran' => 'array',
    ];

    public function skrining()
    {
        return $this->belongsTo(Skrining::class, 'id_skrining');
    }

    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'id_lansia');
    }
}
