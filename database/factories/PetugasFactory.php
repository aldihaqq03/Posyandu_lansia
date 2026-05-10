<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetugasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'nama' => $this->faker->name(),
            'nik' => $this->faker->unique()->numerify('################'),
            'jabatan' => $this->faker->randomElement(['kepala_kader', 'kader']),
            'status' => 'aktif',
        ];
    }
}
