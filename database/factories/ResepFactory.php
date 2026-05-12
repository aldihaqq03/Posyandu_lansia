<?php

namespace Database\Factories;

use App\Models\Resep;
use App\Models\Skrining;
use App\Models\Petugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resep>
 */
class ResepFactory extends Factory
{
    protected $model = Resep::class;

    public function definition(): array
    {
        return [
            'id_skrining' => Skrining::factory(),
            'id_petugas' => Petugas::factory(),
            'catatan' => $this->faker->paragraph(),
        ];
    }
}
