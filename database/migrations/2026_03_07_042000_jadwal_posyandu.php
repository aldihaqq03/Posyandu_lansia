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
        Schema::create('jadwal_posyandu', function (Blueprint $table) {
         $table->id('id_jadwal_posyandu');
        $table->unsignedBigInteger('id_petugas');
        $table->date('tanggal_pelaksanaan');
        $table->string('lokasi', 255);
        $table->string('tema', 255);
        $table->json('kegiatan')->nullable();
        $table->text('keterangan')->nullable();
        // 1=Terjadwal, 2=Berlangsung, 3=Selesai, 4=Dibatalkan
        $table->tinyInteger('status')->unsigned()->default(1);
        $table->timestamps();

        $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_posyandu');
    }
};
