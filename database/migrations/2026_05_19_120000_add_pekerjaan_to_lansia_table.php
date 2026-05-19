<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPekerjaanToLansiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lansia', function (Blueprint $table) {
            $table->string('pekerjaan')->nullable()->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lansia', function (Blueprint $table) {
            if (Schema::hasColumn('lansia', 'pekerjaan')) {
                $table->dropColumn('pekerjaan');
            }
        });
    }
}
