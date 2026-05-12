<?php

namespace Database\Factories;

use App\Models\Obat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Obat>
 */
class ObatFactory extends Factory
{
    protected $model = Obat::class;

    public function definition(): array
    {
        return [
            'id_obat' => 'OBT-' . $this->faker->unique()->numberBetween(100, 999),
            'nama_obat' => $this->faker->randomElement([
                'Paracetamol', 'Amoxicillin', 'Metformin', 'Amlodipine', 
                'Simvastatin', 'Omeprazole', 'Lansoprazole', 'Captopril',
                'Metoprolol', 'Atorvastatin', 'Albuterol', 'Gabapentin'
            ]),
            'tipe_obat' => $this->faker->randomElement(['Tablet', 'Kapsul', 'Sirup', 'Salep']),
            'stock' => $this->faker->numberBetween(10, 500),
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
