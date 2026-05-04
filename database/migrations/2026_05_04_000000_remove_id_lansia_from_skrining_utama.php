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
        Schema::table('skrining_utama', function (Blueprint $table) {
            // Drop foreign key constraint jika ada
            try {
                $table->dropForeign(['id_lansia']);
            } catch (\Exception $e) {
                // Foreign key mungkin tidak ada
            }

            // Drop column id_lansia
            $table->dropColumn('id_lansia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skrining_utama', function (Blueprint $table) {
            // Restore column
            $table->unsignedBigInteger('id_lansia')->nullable()->after('id_skrining');

            // Restore foreign key
            $table->foreign('id_lansia')
                ->references('id_lansia')
                ->on('lansia')
                ->onDelete('cascade');
        });
    }
};
