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
        Schema::create('skrining', function (Blueprint $table) {
        $table->id('id_skrining');
        $table->unsignedBigInteger('id_lansia');
        $table->unsignedBigInteger('id_petugas');
        $table->unsignedBigInteger('id_jadwal_posyandu')->nullable();
        $table->date('tanggal_skrining');
        $table->timestamps();

        $table->foreign('id_lansia')->references('id_lansia')->on('lansia')->onDelete('cascade');
        $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('cascade');
        $table->foreign('id_jadwal_posyandu')->references('id_jadwal_posyandu')->on('jadwal_posyandu')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
