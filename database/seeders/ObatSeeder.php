<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        // Check if data already exists to avoid duplicates
        if (Obat::count() === 0) {
            Obat::factory()->count(20)->create();
        }
    }
}
