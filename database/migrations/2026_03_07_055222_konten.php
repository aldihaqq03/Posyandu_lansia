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
        Schema::create('konten', function (Blueprint $table) {
            $table->id('id_konten');
            $table->string('judul', 255);
            // 1=Video, 2=Gambar, 3=Artikel, 4=Audio
            $table->tinyInteger('tipe_konten')->unsigned();
            // 1=Fisioterapi, 2=Gizi, 3=Senam, 4=Edukasi PTM, 5=Jiwa
            $table->tinyInteger('kategori_konten')->unsigned();
            $table->string('path_konten', 500)->nullable();
            $table->string('gambar', 500)->nullable();
            $table->string('video', 500)->nullable();
            $table->unsignedSmallInteger('durasi_detik')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
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
