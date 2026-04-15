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
        Schema::create('skrining_kunjungan', function (Blueprint $table) {
        $table->id('id_skrining_kunjungan');
        $table->unsignedBigInteger('id_skrining');

        $table->decimal('berat_badan', 5, 1)->nullable();
        $table->decimal('tinggi_badan', 5, 1)->nullable();
        $table->decimal('lingkar_perut', 5, 1)->nullable();
        $table->unsignedSmallInteger('td_sistolik')->nullable();
        $table->unsignedSmallInteger('td_diastolik')->nullable();

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
