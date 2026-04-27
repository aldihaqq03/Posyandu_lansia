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
            $table->index('tanggal_pelaksanaan');
        });

        Schema::table('skrining', function (Blueprint $table) {
            $table->unique(['id_lansia', 'id_jadwal_posyandu'], 'lansia_jadwal_unique');
        });
    }

    public function down(): void
    {
        Schema::table('skrining', function (Blueprint $table) {
            $table->dropUnique('lansia_jadwal_unique');
        });

        Schema::table('jadwal_posyandu', function (Blueprint $table) {
            $table->dropIndex(['tanggal_pelaksanaan']);
        });
    }
};
