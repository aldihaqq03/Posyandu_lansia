<?php

namespace App\Helpers;

use App\Models\Obat;

class IdGenerator
{
    /**
     * Generate custom ID untuk Obat dengan format: obt + 3 digit random
     * Contoh: obt001, obt234, obt999
     */
    public static function generateObatId()
    {
        while (true) {
            $randomNumber = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $newId = 'obt' . $randomNumber;
            
            // Cek apakah ID sudah ada
            if (!Obat::where('id_obat', $newId)->exists()) {
                return $newId;
            }
        }
    }
}
