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
        
        Schema::create('lansia', function (Blueprint $table) {
            $table->id('id_lansia');
<<<<<<< Updated upstream
            $table->string('nik', 16)->unique();
            $table->string('nama', 100);
            $table->date('tanggal_lahir'); // date tidak pakai length
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('email', 100)->unique()->nullable();
            $table->string('password', 100)->nullable();
            $table->text('alamat'); // text tidak pakai length
            $table->string('no_hp', 15)->nullable();
            $table->string('pekerjaan', 50)->nullable();
            $table->enum('status_perkawinan', ['belum menikah', 'menikah', 'duda', 'janda'])->nullable(); // ganti ke enum
            $table->timestamps();
=======
            $table->string('nik', 20)->unique();
            $table->string('nama_lansia', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 13)->nullable();
            $table->string('status_perkawinan', 20)->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->date('tanggal_daftar')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('email', 30)->nullable();
            $table->timestamps(); // created_at & updated_at
>>>>>>> Stashed changes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
