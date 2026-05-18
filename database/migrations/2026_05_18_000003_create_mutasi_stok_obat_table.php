<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutasiStokObatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('mutasi_stok_obat')) {
            Schema::create('mutasi_stok_obat', function (Blueprint $table) {
                $table->bigIncrements('id_mutasi');
                $table->string('id_obat', 20);
                $table->unsignedBigInteger('id_resep')->nullable();

                $table->enum('tipe', ['masuk','keluar']);
                $table->integer('jumlah');
                $table->text('keterangan')->nullable();

                $table->timestamps();

                $table->index('id_obat');
                $table->index('id_resep');

                $table->foreign('id_obat')->references('id_obat')->on('obat')->onDelete('cascade');
                $table->foreign('id_resep')->references('id_resep')->on('resep')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_stok_obat');
    }
}
