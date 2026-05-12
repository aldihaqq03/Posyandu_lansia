<?php

namespace Database\Factories;

use App\Models\Skrining;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkriningUtamaFactory extends Factory
{
    public function definition(): array
    {
        $sistolik = $this->faker->numberBetween(110, 160);
        $diastolik = $this->faker->numberBetween(70, 100);
        $gulaDarah = $this->faker->numberBetween(80, 200);
        $kolesterol = $this->faker->numberBetween(140, 250);

        return [
            'id_skrining' => Skrining::factory(),
            'merokok' => $this->faker->boolean(20),
            'konsumsi_gula' => $this->faker->randomElement([1, 2, 3]),
            'konsumsi_garam' => $this->faker->randomElement([1, 2, 3]),
            'konsumsi_minyak' => $this->faker->randomElement([1, 2, 3]),
            'konsumsi_sayur_buah' => $this->faker->randomElement([1, 2, 3]),
            'aktivitas_fisik' => $this->faker->randomElement([1, 2, 3]),
            'tinggi_badan' => $this->faker->randomFloat(1, 145, 175),
            'berat_badan' => $this->faker->randomFloat(1, 45, 85),
            'imt' => $this->faker->randomFloat(2, 18, 30),
            'lingkar_perut' => $this->faker->numberBetween(70, 100),
            'td_sistolik' => $sistolik,
            'td_diastolik' => $diastolik,
            'gula_darah' => $gulaDarah,
            'gula_darah_kategori' => $gulaDarah > 144 ? 2 : 1,
            'kolesterol' => $kolesterol,
            'kolesterol_kategori' => $kolesterol > 190 ? 3 : ($kolesterol > 150 ? 2 : 1),
            'srq_total' => $this->faker->numberBetween(0, 5),
            'skrining_penglihatan' => ['normal'],
            'skrining_pendengaran' => ['normal'],
        ];
    }
}
