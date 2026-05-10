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
            $table->dropForeign(['id_skrining']);
            $table->dropColumn('id_skrining');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saran', function (Blueprint $table) {
            $table->unsignedBigInteger('id_skrining')->after('id_saran');
            $table->foreign('id_skrining')->references('id_skrining')->on('skrining')->onDelete('cascade');
        });
    }
};
