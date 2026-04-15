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
        Schema::table('petugas', function (Blueprint $table) {
            if (Schema::hasColumn('petugas', 'wilayah')) {
                $table->dropColumn('wilayah');
            }
        });

        Schema::table('lansia', function (Blueprint $table) {
            if (Schema::hasColumn('lansia', 'wilayah')) {
                $table->dropColumn('wilayah');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petugas', function (Blueprint $table) {
            if (!Schema::hasColumn('petugas', 'wilayah')) {
                $table->string('wilayah')->nullable();
            }
        });

        Schema::table('lansia', function (Blueprint $table) {
            if (!Schema::hasColumn('lansia', 'wilayah')) {
                $table->string('wilayah')->nullable();
            }
        });
    }
};
