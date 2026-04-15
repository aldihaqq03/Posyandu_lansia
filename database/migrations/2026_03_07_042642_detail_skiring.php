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
            Schema::create('detail_skrining', function (Blueprint $table) {
            $table->id('id_detail_skrining');
            $table->unsignedBigInteger('id_skrining');
            $table->timestamps();
            $table->foreign('id_skrining')
            ->references('id_skrining')
            ->on('skrining')
            ->onDelete('cascade');
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
