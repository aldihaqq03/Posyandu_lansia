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
        Schema::table('skrining_kunjungan', function (Blueprint $table) {
            $table->decimal('imt', 5, 2)->nullable()->after('tinggi_badan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skrining_kunjungan', function (Blueprint $table) {
            $table->dropColumn('imt');
        });
    }
};
