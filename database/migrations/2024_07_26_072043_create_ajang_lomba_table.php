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
        Schema::create('ajang_lomba', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_ajang', 255)->nullable();
            $table->string('nama_cabang')->nullable();
            $table->string('nama_jenjang')->nullable();
            $table->string('nama_rumpun')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->uuid('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajang_lomba');
    }
};
