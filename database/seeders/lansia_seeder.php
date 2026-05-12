<?php

namespace Database\Seeders;

use App\Models\Lansia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class lansia_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $waLansia = '08987654321';
        $userTest = User::updateOrCreate(
            ['whatsapp' => $waLansia],
            [
                'email' => 'lansia@gmail.com',
                'password' => Hash::make($waLansia),
            ]
        );

        Lansia::updateOrCreate(
            ['id_user' => $userTest->id],
            [
                'nik' => '3210987654321098',
                'nama_lansia' => 'Bapak Budi',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1950-01-01',
                'alamat' => 'Jl. Mawar No. 123',
                'no_hp' => '08987654321',
                'status_perkawinan' => 'Menikah',
                'tanggal_daftar' => now(),
            ]
        );

        // Tambah lansia random lainnya menggunakan factory
        Lansia::factory()->count(10)->create();
    }
}
