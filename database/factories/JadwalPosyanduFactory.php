<?php

namespace Database\Factories;

use App\Models\Petugas;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalPosyanduFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_petugas' => Petugas::factory(),
            'tanggal_pelaksanaan' => $this->faker->dateTimeBetween('-1 month', '+2 months')->format('Y-m-d'),
            'lokasi' => 'Posyandu ' . $this->faker->streetName(),
            'tema' => $this->faker->randomElement([
                'Pemeriksaan Rutin Lansia',
                'Sosialisasi Gizi Seimbang',
                'Senam Sehat Bersama',
                'Vaksinasi & Vitamin',
                'Penyuluhan Hidup Sehat'
            ]),
            'kegiatan' => json_encode([
                ['jam' => '08:00', 'nama' => 'Pendaftaran'],
                ['jam' => '09:00', 'nama' => 'Pemeriksaan Kesehatan'],
                ['jam' => '10:30', 'nama' => 'Pemberian PMT'],
            ]),
            'status' => $this->faker->randomElement([1, 2]), // 1=Terjadwal, 2=Selesai
        ];
    }
}
