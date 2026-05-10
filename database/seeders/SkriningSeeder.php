<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skrining;
use App\Models\SkriningUtama;
use App\Models\Lansia;
use App\Models\Petugas;
use App\Models\JadwalPosyandu;

class SkriningSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = Petugas::first();
        if (!$petugas) return;

        $jadwalSelesai = JadwalPosyandu::where('status', 2)->first();
        $allLansia = Lansia::all();

        foreach ($allLansia as $lansia) {
            // Bersihkan data lama
            Skrining::where('id_lansia', $lansia->id_lansia)->delete();

            // Skrining Lama
            $skriningLama = Skrining::create([
                'id_lansia' => $lansia->id_lansia,
                'id_petugas' => $petugas->id_petugas,
                'id_jadwal_posyandu' => $jadwalSelesai ? $jadwalSelesai->id_jadwal_posyandu : null,
                'tanggal_skrining' => now()->subDays(14)->toDateString(),
                'keluhan' => 'Pegal-pegal'
            ]);

            SkriningUtama::create([
                'id_skrining' => $skriningLama->id_skrining,
                'merokok' => false,
                'tinggi_badan' => 160.0,
                'berat_badan' => 65.0,
                'imt' => 25.39,
                'td_sistolik' => 135,
                'td_diastolik' => 85,
                'gula_darah' => 110,
                'kolesterol' => 190,
            ]);

            // Skrining Baru
            $skriningBaru = Skrining::create([
                'id_lansia' => $lansia->id_lansia,
                'id_petugas' => $petugas->id_petugas,
                'tanggal_skrining' => now()->toDateString(),
                'keluhan' => 'Sehat'
            ]);

            SkriningUtama::create([
                'id_skrining' => $skriningBaru->id_skrining,
                'merokok' => false,
                'tinggi_badan' => 160.0,
                'berat_badan' => 64.0,
                'imt' => 25.0,
                'td_sistolik' => 120,
                'td_diastolik' => 80,
                'gula_darah' => 100,
                'kolesterol' => 170,
            ]);
        }
    }
}
