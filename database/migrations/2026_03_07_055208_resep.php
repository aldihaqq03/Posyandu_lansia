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
        Schema::create('resep', function (Blueprint $table) {
        $table->id('id_resep');
        $table->unsignedBigInteger('id_skrining');
        $table->unsignedBigInteger('id_petugas');
        $table->text('catatan')->nullable();
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
