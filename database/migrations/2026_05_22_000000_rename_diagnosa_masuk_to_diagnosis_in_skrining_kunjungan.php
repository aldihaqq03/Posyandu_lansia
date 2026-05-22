<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('skrining_kunjungan', function (Blueprint $table) {
            if (! Schema::hasColumn('skrining_kunjungan', 'diagnosis')) {
                $table->text('diagnosis')->nullable()->after('keluhan');
            }
        });

        if (Schema::hasColumn('skrining_kunjungan', 'diagnosa_masuk')) {
            DB::table('skrining_kunjungan')->update([
                'diagnosis' => DB::raw('diagnosa_masuk'),
            ]);

            Schema::table('skrining_kunjungan', function (Blueprint $table) {
                $table->dropColumn('diagnosa_masuk');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skrining_kunjungan', function (Blueprint $table) {
            if (! Schema::hasColumn('skrining_kunjungan', 'diagnosa_masuk')) {
                $table->text('diagnosa_masuk')->nullable()->after('keluhan');
            }
        });

        if (Schema::hasColumn('skrining_kunjungan', 'diagnosis')) {
            DB::table('skrining_kunjungan')->update([
                'diagnosa_masuk' => DB::raw('diagnosis'),
            ]);

            Schema::table('skrining_kunjungan', function (Blueprint $table) {
                $table->dropColumn('diagnosis');
            });
        }
    }
};