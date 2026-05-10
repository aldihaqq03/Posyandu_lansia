<?php

namespace Database\Factories;

use App\Models\DetailResep;
use App\Models\Resep;
use App\Models\Obat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailResep>
 */
class DetailResepFactory extends Factory
{
    protected $model = DetailResep::class;

    public function definition(): array
    {
        return [
            'id_resep' => Resep::factory(),
            'id_obat' => Obat::factory(),
            'dosis' => $this->faker->randomElement(['500mg', '250mg', '10mg', '5mg', '20mg']),
            'frekuensi' => $this->faker->randomElement(['3x1 sehari', '2x1 sehari', '1x1 sehari', '3x2 sehari']),
            'keterangan' => $this->faker->randomElement(['Sesudah makan', 'Sebelum makan', 'Saat makan', 'Sebelum tidur']),
        ];
    }
}
