<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDetailResepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_resep', function (Blueprint $table) {
            if (! Schema::hasColumn('detail_resep', 'jenis_jadwal')) {
                $table->enum('jenis_jadwal', ['harian','hari_tertentu'])->default('harian')->after('dosis');
            }
            if (! Schema::hasColumn('detail_resep', 'hari_konsumsi')) {
                $table->json('hari_konsumsi')->nullable()->after('jenis_jadwal');
            }
            if (! Schema::hasColumn('detail_resep', 'durasi_hari')) {
                $table->integer('durasi_hari')->nullable()->after('hari_konsumsi');
            }
            if (! Schema::hasColumn('detail_resep', 'jumlah_obat')) {
                $table->unsignedInteger('jumlah_obat')->nullable()->after('durasi_hari');
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
            if (Schema::hasColumn('detail_resep', 'jumlah_obat')) {
                $table->dropColumn('jumlah_obat');
            }
            if (Schema::hasColumn('detail_resep', 'durasi_hari')) {
                $table->dropColumn('durasi_hari');
            }
            if (Schema::hasColumn('detail_resep', 'hari_konsumsi')) {
                $table->dropColumn('hari_konsumsi');
            }
            if (Schema::hasColumn('detail_resep', 'jenis_jadwal')) {
                $table->dropColumn('jenis_jadwal');
            }
        });
    }
}
