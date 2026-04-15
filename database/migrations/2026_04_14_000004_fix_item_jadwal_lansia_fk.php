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
        Schema::dropIfExists('item_jadwal_lansia');

        // Create new structure with correct FK
        Schema::create('item_jadwal_lansia', function (Blueprint $table) {
            $table->id('id_item_jadwal');
            $table->unsignedBigInteger('id_jadwal_posyandu'); // CHANGED from id_skrining
            $table->unsignedBigInteger('id_konten')->nullable();
            // 1=Olahraga, 2=Diet, 3=Terapi, 4=Sosial, 5=Istirahat
            $table->tinyInteger('jenis_aktivitas')->unsigned();
            $table->string('nama_aktivitas', 255);
            $table->text('deskripsi')->nullable();
            // 0=Senin, 1=Selasa, 2=Rabu, 3=Kamis, 4=Jumat, 5=Sabtu, 6=Minggu
            $table->tinyInteger('hari')->unsigned();
            $table->time('waktu_aktivitas')->nullable();
            $table->unsignedSmallInteger('durasi_menit')->nullable();
            $table->timestamps();

            $table->foreign('id_jadwal_posyandu')
                  ->references('id_jadwal_posyandu')
                  ->on('jadwal_posyandu')
                  ->onDelete('cascade');
            $table->foreign('id_konten')
                  ->references('id_konten')
                  ->on('konten')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_jadwal_lansia');

        // Restore old structure
        Schema::create('item_jadwal_lansia', function (Blueprint $table) {
            $table->id('id_item_jadwal');
            $table->unsignedBigInteger('id_skrining');
            $table->unsignedBigInteger('id_konten')->nullable();
            $table->tinyInteger('jenis_aktivitas')->unsigned();
            $table->string('nama_aktivitas', 255);
            $table->text('deskripsi')->nullable();
            $table->tinyInteger('hari')->unsigned();
            $table->time('waktu_aktivitas')->nullable();
            $table->unsignedSmallInteger('durasi_menit')->nullable();
            $table->timestamps();

            $table->foreign('id_skrining')->references('id_skrining')->on('skrining')->onDelete('cascade');
            $table->foreign('id_konten')->references('id_konten')->on('konten')->onDelete('set null');
        });
    }
};
