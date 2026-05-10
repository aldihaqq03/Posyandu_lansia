<?php

namespace Database\Seeders;

use App\Models\Lansia;
use App\Models\Skrining;
use App\Models\Petugas;
use App\Models\Resep;
use App\Models\DetailResep;
use App\Models\Obat;
use Illuminate\Database\Seeder;

class ResepSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there are some medicines first
        if (Obat::count() === 0) {
            $this->call(ObatSeeder::class);
        }

        // Ensure there is at least one petugas
        $petugas = Petugas::first() ?? Petugas::factory()->create();

        // Get all lansia
        $allLansia = Lansia::all();

        // If no lansia, create some
        if ($allLansia->isEmpty()) {
            $allLansia = Lansia::factory()->count(10)->create();
        }

        foreach ($allLansia as $lansia) {
            // Get or create a screening for this lansia
            $skrining = $lansia->skrinings()->first() ?? Skrining::factory()->create([
                'id_lansia' => $lansia->id_lansia,
                'id_petugas' => $petugas->id_petugas,
            ]);

            // Create a resep for this screening if not exists
            $resep = Resep::where('id_skrining', $skrining->id_skrining)->first();
            
            if (!$resep) {
                $resep = Resep::create([
                    'id_skrining' => $skrining->id_skrining,
                    'id_petugas' => $petugas->id_petugas,
                    'catatan' => 'Resep rutin untuk kesehatan lansia.',
                ]);

                // Add some detail resep (medicines)
                $obats = Obat::inRandomOrder()->take(rand(1, 3))->get();
                foreach ($obats as $obat) {
                    DetailResep::create([
                        'id_resep' => $resep->id_resep,
                        'id_obat' => $obat->id_obat,
                        'dosis' => rand(1, 2) . ' tablet',
                        'frekuensi' => rand(1, 3) . 'x sehari',
                        'keterangan' => 'Sesudah makan',
                    ]);
                }
            }
        }
    }
}
