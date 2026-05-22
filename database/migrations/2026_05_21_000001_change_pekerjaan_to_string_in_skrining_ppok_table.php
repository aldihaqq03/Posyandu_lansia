<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skrining_ppok', function (Blueprint $table) {
            $table->string('pekerjaan', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('skrining_ppok', function (Blueprint $table) {
            $table->tinyInteger('pekerjaan')->unsigned()->nullable()->change();
        });
    }
};