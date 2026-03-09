<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<<< Updated upstream:database/migrations/2026_03_08_070648_create_sessions_table.php
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
========
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('nik');
            $table->string('whatsapp');
            $table->string('jabatan');
            $table->string('wilayah_kerja');
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });


>>>>>>>> Stashed changes:database/migrations/0001_01_01_000000_create_users_table.php
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< Updated upstream:database/migrations/2026_03_08_070648_create_sessions_table.php
        Schema::dropIfExists('sessions');
========
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');

>>>>>>>> Stashed changes:database/migrations/0001_01_01_000000_create_users_table.php
    }
};
