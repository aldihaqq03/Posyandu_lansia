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
        Schema::create('keluhan', function (Blueprint $table) {
        $table->id('id_keluhan');
        $table->unsignedBigInteger('id_skrining');
        $table->text('keluhan');
        $table->timestamps();

        $table->foreign('id_skrining')->references('id_skrining')->on('skrining')->onDelete('cascade');
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
