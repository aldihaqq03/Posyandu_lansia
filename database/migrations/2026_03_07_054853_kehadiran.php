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
        Schema::create('kehadiran_posyandu', function (Blueprint $table) {
        $table->id('id_kehadiran');
        $table->unsignedBigInteger('id_jadwal_posyandu');
        $table->unsignedBigInteger('id_lansia');
        // 1=Hadir, 2=Tidak Hadir, 3=Izin
        $table->tinyInteger('status_kehadiran')->unsigned()->default(1);
        $table->string('keterangan', 255)->nullable();
        $table->timestamps();

        $table->unique(['id_jadwal_posyandu', 'id_lansia']);

        $table->foreign('id_jadwal_posyandu')->references('id_jadwal_posyandu')->on('jadwal_posyandu')->onDelete('cascade');
        $table->foreign('id_lansia')->references('id_lansia')->on('lansia')->onDelete('cascade');
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
