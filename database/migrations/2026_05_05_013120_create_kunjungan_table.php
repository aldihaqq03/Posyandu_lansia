<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id('id_kunjungan');

            $table->unsignedBigInteger('id_lansia');
            $table->unsignedBigInteger('id_user');

            $table->date('tanggal_kunjungan');
            $table->text('keluhan')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('id_lansia')
                ->references('id_lansia')
                ->on('lansia')
                ->onDelete('cascade');

            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};