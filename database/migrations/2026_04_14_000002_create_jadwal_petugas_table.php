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
        Schema::create('jadwal_petugas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jadwal_posyandu');
            $table->unsignedBigInteger('id_petugas');
            $table->primary(['id_jadwal_posyandu', 'id_petugas']);

            $table->foreign('id_jadwal_posyandu')
                  ->references('id_jadwal_posyandu')
                  ->on('jadwal_posyandu')
                  ->onDelete('cascade');

            $table->foreign('id_petugas')
                  ->references('id_petugas')
                  ->on('petugas')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_petugas');
    }
};
