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
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->date('tgl_mulai')->nullable()->change();
            $table->date('tgl_selesai')->nullable()->change();
            $table->unsignedInteger('kak6_program')->nullable()->change();
            $table->unsignedInteger('kak6_aktivitas')->nullable()->change();
            $table->unsignedInteger('kak6_kro')->nullable()->change();
            $table->unsignedInteger('kak6_ro')->nullable()->change();
            $table->unsignedInteger('kak6_komponen')->nullable()->change();
            $table->unsignedInteger('kak6_sub_komponen')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->date('tgl_mulai')->nullable(false)->change();
            $table->date('tgl_selesai')->nullable(false)->change();
            $table->unsignedInteger('kak6_program')->nullable(false)->change();
            $table->unsignedInteger('kak6_aktivitas')->nullable(false)->change();
            $table->unsignedInteger('kak6_kro')->nullable(false)->change();
            $table->unsignedInteger('kak6_ro')->nullable(false)->change();
            $table->unsignedInteger('kak6_komponen')->nullable(false)->change();
            $table->unsignedInteger('kak6_sub_komponen')->nullable(false)->change();
        });
    }
};
