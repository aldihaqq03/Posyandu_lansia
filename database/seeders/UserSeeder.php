<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT AKUN PETUGAS (KADER)
        $waPetugas = '08123456789';
        $userPetugas = User::updateOrCreate(
            ['whatsapp' => $waPetugas],
            [
                'email' => 'petugas@gmail.com',
                'password' => Hash::make($waPetugas), // Password sama dengan No HP
            ]
        );

        Petugas::updateOrCreate(
            ['id_user' => $userPetugas->id],
            [
                'nama' => 'Petugas Utama',
                'nik' => '1234567890123456',
                'jabatan' => 'kepala_kader',
                'status' => 'aktif'
            ]
        );

        // Tambahkan beberapa kader random
        for ($i = 1; $i <= 3; $i++) {
            $waKader = '08122233344' . $i;
            $u = User::create([
                'whatsapp' => $waKader,
                'email' => 'kader' . $i . '@gmail.com',
                'password' => Hash::make($waKader),
            ]);

            Petugas::create([
                'id_user' => $u->id,
                'nama' => 'Kader ' . $i,
                'nik' => '123456789012345' . $i,
                'jabatan' => 'kader',
                'status' => 'aktif'
            ]);
        }
    }
}
