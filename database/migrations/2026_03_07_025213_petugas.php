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
            $table->string('nama', 100); 
            $table->string('email', 150)->unique(); 
            $table->string('password', 100); 
            $table->string('no_hp', 15)->nullable();
            $table->enum('role', ['admin', 'petugas']);
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
