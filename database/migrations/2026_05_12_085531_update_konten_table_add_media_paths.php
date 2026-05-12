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
        Schema::table('konten', function (Blueprint $table) {
            if (!Schema::hasColumn('konten', 'gambar')) {
                $table->string('gambar', 500)->nullable()->after('kategori_konten');
            }
            if (!Schema::hasColumn('konten', 'video')) {
                $table->string('video', 500)->nullable()->after('gambar');
            }
            if (Schema::hasColumn('konten', 'url_file')) {
                $table->dropColumn('url_file');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konten', function (Blueprint $table) {
            $table->string('url_file', 500)->nullable();
            $table->dropColumn(['gambar', 'video']);
        });
    }
};
