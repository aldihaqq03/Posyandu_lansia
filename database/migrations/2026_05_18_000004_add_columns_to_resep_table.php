<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToResepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resep', function (Blueprint $table) {
            if (! Schema::hasColumn('resep', 'id_lansia')) {
                $table->unsignedBigInteger('id_lansia')->nullable()->after('id_skrining');
                $table->index('id_lansia');
                $table->foreign('id_lansia')->references('id_lansia')->on('lansia')->onDelete('set null');
            }

            if (! Schema::hasColumn('resep', 'tanggal_resep')) {
                $table->date('tanggal_resep')->nullable()->after('id_petugas');
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
        Schema::table('resep', function (Blueprint $table) {
            if (Schema::hasColumn('resep', 'tanggal_resep')) {
                $table->dropColumn('tanggal_resep');
            }
            if (Schema::hasColumn('resep', 'id_lansia')) {
                $table->dropForeign(['id_lansia']);
                $table->dropIndex(['id_lansia']);
                $table->dropColumn('id_lansia');
            }
        });
    }
}
