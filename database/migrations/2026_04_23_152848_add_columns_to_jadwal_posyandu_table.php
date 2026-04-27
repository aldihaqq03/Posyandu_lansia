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
        Schema::table('jadwal_posyandu', function (Blueprint $table) {
            $table->text('kegiatan')->nullable()->after('tema');
            $table->boolean('ada_skrining_utama')->default(0)->after('kegiatan');
            $table->boolean('ada_skrining_ppok')->default(0)->after('ada_skrining_utama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_posyandu', function (Blueprint $table) {
            $table->dropColumn(['kegiatan', 'ada_skrining_utama', 'ada_skrining_ppok']);
        });
    }
};
