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
        Schema::create('keluarga', function (Blueprint $table) {
            $table->id('id_keluarga');
            $table->unsignedBigInteger('id_lansia');
            $table->string('nama_keluarga', 100);
            $table->string('no_sama', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('id_lansia')->references('id_lansia')->on('lansia')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluarga');
    }
};
