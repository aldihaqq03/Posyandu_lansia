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
        // Drop old structure
        Schema::dropIfExists('detail_skrining');

        // Create new structure
        Schema::create('detail_skrining', function (Blueprint $table) {
            $table->id('id_detail_skrining');
            $table->unsignedBigInteger('id_jadwal_posyandu');
            $table->tinyInteger('jenis_skrining')->comment('1=Utama, 2=PPOK, 3=Kunjungan');
            $table->timestamps();

            $table->foreign('id_jadwal_posyandu')
                  ->references('id_jadwal_posyandu')
                  ->on('jadwal_posyandu')
                  ->onDelete('cascade');

            $table->unique(['id_jadwal_posyandu', 'jenis_skrining']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_skrining');

        // Restore old structure
        Schema::create('detail_skrining', function (Blueprint $table) {
            $table->id('id_detail_skrining');
            $table->unsignedBigInteger('id_skrining');
            $table->timestamps();
            $table->foreign('id_skrining')
                  ->references('id_skrining')
                  ->on('skrining')
                  ->onDelete('cascade');
        });
    }
};
