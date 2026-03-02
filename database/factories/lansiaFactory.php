<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\lansia>
 */
class lansiaFactory extends Factory
{
 public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify('################'),
            'nama_lansia' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '1965-12-31'),
            'alamat' => $this->faker->address(),
            'no_hp' =>'08' . $this->faker->numerify('##########'),
            'status_perkawinan' => $this->faker->randomElement(['Menikah', 'Belum Menikah', 'Cerai']),
            'riwayat_penyakit' => $this->faker->randomElement([
                'Hipertensi',
                'Diabetes',
                'Asam Urat',
                'Kolesterol',
                'Tidak Ada'
            ]),
            'tanggal_daftar' => now(),
            'keterangan' => $this->faker->sentence(),
        ];
    }
}