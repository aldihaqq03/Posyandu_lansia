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
        // 1. Tambah kolom keluhan di skrining_kunjungan
        Schema::table('skrining_kunjungan', function (Blueprint $table) {
            $table->text('keluhan')->nullable()->after('td_diastolik');
        });

        // 2. Tambah password dan unique no_hp di lansia
        Schema::table('lansia', function (Blueprint $table) {
            $table->string('password', 255)->nullable()->after('email');
            $table->unique('no_hp');
        });

        // 3. Hapus tabel keluhan (sudah tidak dipakai)
        Schema::dropIfExists('keluhan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore tabel keluhan
        Schema::create('keluhan', function (Blueprint $table) {
            $table->id('id_keluhan');
            $table->unsignedBigInteger('id_skrining');
            $table->text('keluhan');
            $table->timestamps();
            $table->foreign('id_skrining')
                  ->references('id_skrining')
                  ->on('skrining')
                  ->onDelete('cascade');
        });

        // Hapus kolom yang ditambahkan
        Schema::table('lansia', function (Blueprint $table) {
            $table->dropUnique(['no_hp']);
            $table->dropColumn('password');
        });

        Schema::table('skrining_kunjungan', function (Blueprint $table) {
            $table->dropColumn('keluhan');
        });
    }
};
