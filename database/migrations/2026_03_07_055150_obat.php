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
        Schema::create('obat', function (Blueprint $table) {
        $table->id('id_obat');
        $table->string('nama_obat', 150);
        // 1=Tablet, 2=Kapsul, 3=Sirup, 4=Salep, 5=Tetes
        $table->tinyInteger('tipe_obat')->unsigned();
        $table->text('keterangan')->nullable();
        $table->timestamps();
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
