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
        Schema::table('saran', function (Blueprint $table) {
            $table->dropForeign(['id_petugas']);
            $table->dropColumn('id_petugas');
            $table->unsignedBigInteger('id_lansia')->after('id_saran');
            $table->foreign('id_lansia')->references('id_lansia')->on('lansia')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saran', function (Blueprint $table) {
            $table->dropForeign(['id_lansia']);
            $table->dropColumn('id_lansia');
            $table->unsignedBigInteger('id_petugas')->after('id_saran');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('cascade');
        });
    }
};
