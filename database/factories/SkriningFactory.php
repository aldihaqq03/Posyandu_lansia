<?php

namespace Database\Factories;

use App\Models\Lansia;
use App\Models\Petugas;
use App\Models\JadwalPosyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkriningFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_lansia' => Lansia::factory(),
            'id_petugas' => Petugas::factory(),
            'id_jadwal_posyandu' => JadwalPosyandu::factory(),
            'tanggal_skrining' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'keluhan' => $this->faker->randomElement([
                'Pusing', 'Lemas', 'Sakit Sendi', 'Batuk', 'Tidak ada keluhan', 'Nyeri punggung'
            ]),
        ];
    }
}
