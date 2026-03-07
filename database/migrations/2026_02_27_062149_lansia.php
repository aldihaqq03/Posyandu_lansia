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
        
        Schema::create('lansia', function (Blueprint $table) {
            $table->id('id_lansia');
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
