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
        Schema::dropIfExists('surat_pegawais');
        Schema::dropIfExists('surat_mitras');

        Schema::create('surat_lampirans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surat_id');
            $table->unsignedInteger('peserta_id');
            $table->string('tipe_peserta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('surat_pegawais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('surat_id');
            $table->unsignedBigInteger('nominal')->nullable();
            $table->unsignedInteger('bukti_pembayaran_id')->nullable();
            $table->timestamps();
        });

        Schema::create('surat_mitras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mitra_id');
            $table->unsignedBigInteger('surat_id');
            $table->tinyInteger('is_pml')->nullable();
            $table->unsignedInteger('jumlah')->nullable();
            $table->unsignedBigInteger('honor')->nullable();
            $table->unsignedBigInteger('estimasi_honor')->nullable();
            $table->unsignedInteger('bukti_pembayaran_id')->nullable();
            $table->timestamps();
        });
    }
};
