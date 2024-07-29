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
        Schema::create('prestasi_provinsi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_prestasi', 255)->nullable();
            $table->string('peringkat_prestasi', 255)->nullable();
            $table->string('nominal_hadiah', 255)->nullable();
            $table->string('jenis_medali', 255)->nullable();
            $table->uuid('id_siswa')->nullable();
            $table->uuid('id_ajang')->nullable();
            $table->smallInteger('tahun')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->uuid('updated_by')->nullable();

            $table->foreign('id_ajang')->references('id')->on('ajang_lomba')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id')->on('siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_provinsi');
    }
};
