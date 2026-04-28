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
        // Hanya mengubah kolom menjadi nullable, 
        // JANGAN tambah ->unique() lagi karena sudah ada di database.
        $table->string('nama')->nullable()->change();
        $table->string('nik', 16)->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('petugas', function (Blueprint $table) {
        $table->string('nama')->nullable(false)->change();
        $table->string('nik', 16)->nullable(false)->change();
    });
}
};
