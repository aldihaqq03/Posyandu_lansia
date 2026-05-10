<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalPosyandu;
use App\Models\Petugas;

class JadwalPosyanduSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = Petugas::first();
        if (!$petugas) return;

        // Tambahkan beberapa jadwal random lainnya menggunakan factory
        JadwalPosyandu::factory()->count(5)->create([
            'id_petugas' => $petugas->id_petugas
        ]);
    }
}
