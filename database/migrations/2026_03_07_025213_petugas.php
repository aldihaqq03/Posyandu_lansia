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
    Schema::create('petugas', function (Blueprint $table) {
        $table->id('id_petugas');
        $table->string('nama');
        $table->string('nik',16)->unique();
        $table->enum('jabatan', ['kepala_kader', 'kader']);
        $table->string('wilayah');
        $table->string('no_hp');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('foto')->nullable();
        $table->string('status')->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
