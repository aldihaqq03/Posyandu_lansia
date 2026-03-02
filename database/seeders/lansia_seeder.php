<?php

namespace Database\Seeders;

use App\Models\lansia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class lansia_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         lansia::factory(10)->create(); 
        lansia::factory(5)->create([
            'status_perkawinan' => 'Menikah',
              ]);
    }
}
