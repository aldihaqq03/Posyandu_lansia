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
        Schema::create('saran', function (Blueprint $table) {
        $table->id('id_saran');
        $table->unsignedBigInteger('id_skrining');
        $table->unsignedBigInteger('id_petugas');
        // 1=Gizi, 2=Aktivitas Fisik, 3=Medis, 4=Psikologi, 5=Lainnya
        $table->tinyInteger('jenis_saran')->unsigned();
        $table->text('isi_saran');
        $table->timestamps();

        $table->foreign('id_skrining')->references('id_skrining')->on('skrining')->onDelete('cascade');
        $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('cascade');
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
