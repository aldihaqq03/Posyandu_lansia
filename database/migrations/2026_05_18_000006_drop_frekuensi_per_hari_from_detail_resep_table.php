<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFrekuensiPerHariFromDetailResepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_resep', function (Blueprint $table) {
            if (Schema::hasColumn('detail_resep', 'frekuensi_per_hari')) {
                $table->dropColumn('frekuensi_per_hari');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_resep', function (Blueprint $table) {
            if (! Schema::hasColumn('detail_resep', 'frekuensi_per_hari')) {
                $table->integer('frekuensi_per_hari')->nullable()->after('jenis_jadwal');
            }
        });
    }
}