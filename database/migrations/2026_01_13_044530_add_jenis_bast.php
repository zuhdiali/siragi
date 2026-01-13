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
        Schema::table('surats', function (Blueprint $table) {
            $table->string('tipe_bast', 25)->nullable()->after('jenis_surat');
            $table->dropColumn('tipe_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn('tipe_bast');
            $table->string('tipe_surat', 25)->nullable()->after('jenis_surat');
        });
    }
};
