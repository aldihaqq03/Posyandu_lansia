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
        Schema::table('lansia', function (Blueprint $table) {
            if (!Schema::hasColumn('lansia', 'kode_unik')) {
                $table->string('kode_unik', 20)->unique()->nullable()->after('id_user');
            }
        });

        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lansia');
            $table->string('chat_id', 50);
            $table->string('nama_telegram', 100)->nullable();
            $table->timestamps();

            // Opsional: Foreign Key
            // $table->foreign('id_lansia')->references('id_lansia')->on('lansia')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');
        Schema::table('lansia', function (Blueprint $table) {
            if (Schema::hasColumn('lansia', 'kode_unik')) {
                $table->dropColumn('kode_unik');
            }
        });
    }
};
