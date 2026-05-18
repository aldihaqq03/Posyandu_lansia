<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's try to convert any string like "3x1" to just "3"
        $details = DB::table('detail_resep')->get();
        foreach ($details as $detail) {
            $val = (int) $detail->frekuensi;
            if ($val <= 0) $val = 1; // fallback
            DB::table('detail_resep')
                ->where('id_detail_resep', $detail->id_detail_resep)
                ->update(['frekuensi' => (string) $val]);
        }

        // Now modify the column. Use raw statement for safety if doctrine is not installed.
        DB::statement('ALTER TABLE detail_resep MODIFY frekuensi INT UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE detail_resep MODIFY frekuensi VARCHAR(100) NOT NULL');
    }
};
